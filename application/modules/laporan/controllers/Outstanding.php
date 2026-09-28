<?php

class Outstanding extends MX_Controller
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

    public function test()
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
        $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $src_000 = $ps->callOutstanding("persediaan_produk");
        // $src_000 = $ps->callOrderan("persediaan_produk");
    }

    // ---------------------------------
    /*-valid penjualan reguler doang-*/
    public function cekoutstandingseller_old()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // // "0"   => array("qty" => "0", "nilai" => "0",),
            // // "57"  => array("qty" => "0", "nilai" => "0",),
            // // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            // // "61"  => array("qty" => "44", "nilai" => "99561369",),
            // "65"  => array("qty" => "9", "nilai" => "21598185",),
            // // "65"  => array("qty" => "9", "nilai" => "18523185",),
            // // "69"  => array("qty" => "111936", "nilai" => "10243373229",),
            // // "69"  => array("qty" => "111936", "nilai" => "10457787651",),
            // "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            // "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // // "576" => array("qty" => "132", "nilai" => "449897306.72834",),
            // "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            // // "718" => array("qty" => "15", "nilai" => "119260509",),
            // // "286" => array("qty" => "2041", "nilai" => "0",),
            // // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // // "718" => array("qty" => "0", "nilai" => "0",),
            // // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "1", "nilai" => "4009010",),
            // // "825" => array("qty" => "0", "nilai" => "0",),
            // // "826" => array("qty" => "0", "nilai" => "0",),
            // // "712" => array("qty" => "0", "nilai" => "0",),
            // // "567" => array("qty" => "189", "nilai" => "0",),
            // // // "551" => array("qty" => "0", "nilai" => "0",),
            // // "205" => array("qty" => "28", "nilai" => "11886000",),
            // // "182" => array("qty" => "-2304", "nilai" => "2252939845",),
            // // "77"  => array("qty" => "-2", "nilai" => "-2342344",),
            // // "551"  => array("qty" => "8615", "nilai" => "5637500091",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        /* ---------------------------------------------------------------------
        * filter khusus
        * ---------------------------------------------------------------------*/
        // arrPrint($_GET);
        if (isset($_GET['ky'])) {
            $get_condites = array(
                $_GET['ky'] => $_GET[$_GET['ky']]
            );
            if (method_exists($dt, "getSellerId")) {

                // $dt->setSellerId('808');
            }
        }
        // cekHere(ipadd());
        if (ipadd() == "202.65.117.72") {
            // $dt->setSellerId('65');
            // $dt->setSellerId('73');
            // ------------------
            // $dt->setSellerId('61');
            // $dt->setSellerId('69');
        }
        // $dt->setSellerId('844');
        // $dt->setSellerId('65');
        // $dt->setSellerId('69');
        // $dt->setMasterId('100786');

        $src_00 = $dt->getSaldoSeller($date1, $date2);
        $src_mtd = $src_00['mtd'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        $src_koloms = $src_00['kolom'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // arrPrint($src_mtd);
        // arrPrint($src_00);
        // matiHere(__LINE__ . __FILE__);

        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        $sumSubjek = array();
        $arrSubjek = array();
        foreach ($src_mtd as $item) {

            $seller_id = $item['seller_id'];
            $subjek_id = $item['seller_id'];
            // $subjek_id = $item['master_id'];
            // $qty_debet = $item['qty_debet_reguler'];

            $debet = $item['debet'];
            $kredit = $item['kredit'];

            $rekening = $item['rekening'];
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            foreach ($src_koloms as $src_kolom) {
                $$src_kolom = $item[$src_kolom];

                $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
            }
            // $sumSubjek[$subjek_id]['now_debet_' . $rekening] = $debet;
            // //
            // // // ---------------------------------------------------------
            // // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
            // //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
            // // }
            // $sumSubjek[$subjek_id]['now_kredit_' . $rekening] = $kredit;


            //---------------------------------------------------------
            $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
            $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
            // $arrRekenings[$rekening] = $rekening;
            $arrSeller[$seller_id] = $item;
        }

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // arrPrintPink($src_yang_lalu);
        // $sumSubjek = array();
        foreach ($src_yang_lalu as $item) {

            $seller_id = $item['seller_id'];
            $subjek_id = $item['seller_id'];
            // $subjek_id = $item['master_id'];
            // $qty_debet = $item['qty_debet_reguler'];

            $debet = $item['debet'];
            $kredit = $item['kredit'];

            $rekening = $item['rekening'];
            foreach ($src_koloms as $src_kolom) {
                if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
                    $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
                }
                $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
            }
            // if (!isset($sumSubjek[$subjek_id]['prev_debet_' . $rekening])) {
            //     $sumSubjek[$subjek_id]['prev_debet_' . $rekening] = 0;
            // }
            // $sumSubjek[$subjek_id]['prev_debet_' . $rekening] += $debet;
            //
            // // ---------------------------------------------------------
            // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
            //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
            // }
            // $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] += $kredit;


            //---------------------------------------------------------
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
            $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
            // $arrRekenings[$rekening] = $rekening;
            $arrSeller[$seller_id] = $item;
        }
        // arrPrint($sumSubjek);
        // test_table($sumSubjek);
        // matiHere(__LINE__);

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        $qty_kirim = 0;
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            $rekening = $sbjDatas['rekening'];
            $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
            $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
            $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
            $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
            $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
            $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
            $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";
            $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";

            /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
            if ($now_saldo_order_582so > 0) {
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
                $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
            }
            else {
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
                $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
            }
            // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
            // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
            $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
            $last_kredit_582spd = 0;
            if ($now_kredit_582spd > 0) {
                $last_kredit_582spd = ($now_kredit_582spd * 1) . "***";
            }
            else {
                //     //$sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"]
                // cekHijau("$now_saldo_order_582so");
                $last_kredit_582spd = ($prev_kredit_582spd + $now_saldo_order_582so - $now_saldo_reject_582spd - $now_saldo_closed_582spd - $now_saldo_kirim_582spd);
            }
            $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;
        }

        // arrPrintWebs($sumSubjekSeller2);
        // arrPrintWebs($sumSubjekSeller);
        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // arrPrintHijau($arrSubjek);
        //   matiHere(__LINE__);
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];


            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumParams;
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            // "master_id"            => array(
            //     "label" => "mid",
            // ),
            "seller_id"               => array(
                "label"   => "sid",
                "rowspan" => "2",
            ),
            "seller_nama"             => array(
                "label"   => "salesman",
                "rowspan" => "2",
                // "links"   => array(
                //     // "target"  => "laporan/Crm/cekoutstandingraw",
                //     "target"  => "laporan/Crm/cekoutstandingtransaksi",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
                // "collapsible" => array(
                //     "layout" => false,
                //     "key"    => "seller_id",
                //     "target" => "laporan/Crm/cekoutstandingtransaksi",
                // ),
                "popup"   => array(
                    "key"    => "seller_id",
                    // "target" => "laporan/Outstanding/perindekshow/cekoutstandingtransaksi",
                    "target" => "laporan/Outstanding/viewemyoutstanding",
                ),
            ),
            // "customer_nama"        => array(
            //     "label" => "customer",
            //     "links" => array(
            //         // "target"  => "laporan/Crm/cekoutstandingraw",
            //         "target"  => "laporan/Crm/cekoutstandingtransaksi",
            //         "title"   => "Transaksi per salesman",
            //         "key"     => "seller_id",
            //         "heading" => "seller_nama",
            //     ),
            //     // "collapsible" => array(
            //     //     "layout" => false,
            //     //     "key"    => "seller_id",
            //     //     "target" => "laporan/Crm/cekoutstandingtransaksi",
            //     // ),
            // ),
            /*prevous outstanding*/
            // "prev_qty_order" => array(
            //     "label"      => "previous order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_saldo_order_582so" => array(
            //     "label"      => "previous order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "prev_kredit_582spd"      => array(
                "label"      => "previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            // "prev_kredit_7499"     => array(
            //     "label"      => "previous outstanding proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order rincian*/
            // "qty_debet_reguler_582so"      => array(
            //     "label"      => "new order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_582so"          => array(
            //     "label"      => "new order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_order_582so"   => array(
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
            "now_saldo_reject_582spd" => array(
                "label"      => "new reject value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_saldo_closed_582spd" => array(
                "label"      => "new close value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_582spd"         => array(
            //     "label"      => "packing list local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_batal"         => array(
            //     "label"      => "qty_kirim_batal",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            // "qty_kirim_netto"      => array(
            //     "label"      => "new netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_kirim_582spd"  => array(
                "label"      => "new netto packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*outstanding*/
            // "qty_outstanding"      => array(
            //     "label"      => "new outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_kredit_582spd"       => array(
                "label"      => "new outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "logics"     => array(
                    "nilai" => "<0",
                    "text"  => "termasuk pengiriman dari outstanding sebelumnya",
                ),
                "summary"    => true,
            ),
            /*kirim old*/
            // "qty_kirim_netto_old"  => array(
            //     "label"      => "netto pl qty previous",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto_old"        => array(
            //     "label"      => "netto pl value previous",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_netto_all"  => array(
            //     "label"      => "all netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto_all"        => array(
            //     "label"      => "all netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),

            /*last outstanding*/
            // "ytd_qty_outstanding"  => array(
            //     "label"      => "last outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            "last_kredit_582spd"      => array(
                "label"      => "outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            // "seller_id"      => array(
            //     "label"      => "sID",
            // ),
        );
        // arrPrintKuning($sumSubjek);
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
            "title"       => "Laporan Salesman Outstanding " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
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
        $this->load->view("outstanding", $data);
    }

    public function cekoutstandingtransaksi_old()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // "113414" => array("qty" => "2", "nilai" => "0",),
            // "0"   => array("qty" => "0", "nilai" => "0",),
            // "57"  => array("qty" => "0", "nilai" => "0",),
            // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            // "65"  => array("qty" => "9", "nilai" => "21622194",),
            // "69"  => array("qty" => "111125", "nilai" => "9940808792",),
            // "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            // "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            // "718" => array("qty" => "15", "nilai" => "119260509",),
            // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // "718" => array("qty" => "0", "nilai" => "0",),
            // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "0", "nilai" => "0",),
            // "825" => array("qty" => "0", "nilai" => "0",),
            // "826" => array("qty" => "0", "nilai" => "0",),
            // "712" => array("qty" => "0", "nilai" => "0",),
            // "567" => array("qty" => "189", "nilai" => "0",),
            // // "551" => array("qty" => "0", "nilai" => "0",),
            // "205" => array("qty" => "28", "nilai" => "11886000",),
            // "182" => array("qty" => "-2304", "nilai" => "-2252939845",),
            // "127003" => array("qty" => "165", "nilai" => "400000000",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        /* -----------------------------------------------------------------
         * filter khusus buat seller, berlaku diluar pusat
         * -----------------------------------------------------------------*/
        if (my_cabang_id() > 0) {
            // $date1 = "2021-01-01";
            $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "2021-01-01";
            $dt->setSellerId(my_id());
            // $dt->setMasterId("153293");
            // $dt->setMasterId("153291");
            // $dt->setMasterId("113488");
        }
        else {
            if (isset($_GET['ky'])) {
                switch ($_GET['ky']) {
                    case "seller_id":
                        $dt->setSellerId($_GET[$_GET['ky']]);
                        break;
                    case "master_id":
                        $dt->setMasterId($_GET[$_GET['ky']]);
                        break;
                }
            }
        }

        // $dt->setSellerId('65');
        // $dt->setSellerId('77');
        // $dt->setMasterId("113488");
        // $dt->setMasterId("127359");
        // $dt->setMasterId("126305");
        // $dt->setMasterId("123515");
        // $dt->setMasterId("91060 ");
        // $dt->setMasterId("100786 ");

        $src_00 = $dt->getSaldoSellerTransaksi($date1, $date2);
        //        arrPrintKuning($src_00);
        //        showLast_query("kuning");
        $src_mtd = $src_00['mtd'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $src_ytd = $src_00['ytd'];
        $src_ytd_pluss = $src_00['ytd_pluss'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        $src_tr = $src_00['transaksi'];
        $src_koloms = $src_00['kolom'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // arrPrint($src_mtd);
        // arrPrint($src_yang_lalu);
        // arrPrintPink($src_ytd);
        // arrPrint($src_ytd_pluss);
        foreach ($src_ytd_pluss as $src_ytd_pluss) {
            $subjek_id = $src_ytd_pluss['master_id'];
            $tr_datas[$subjek_id] = $src_ytd_pluss;
        }
        // matiDisini(__LINE__);
        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        $arrSubjek = array();
        $sumSubjek = array();
        foreach ($src_mtd as $item) {

            $subjek_id = $item['master_id'];
            $transaksi_id = $item['transaksi_id'];

            $seller_id = $item['seller_id'];
            $seller_nama = $item['seller_nama'];
            $rekening = $item['rekening'];
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            foreach ($src_koloms as $src_kolom) {
                $$src_kolom = $item[$src_kolom];

                $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
            }
            // cekBiru("$rekening");

            //---------------------------------------------------------
            // $arrSubjek[$subjek_id]['seller_id'] = $seller_id;
            // $arrSubjek[$subjek_id]['seller_nama'] = $seller_nama;
            $arrSubjek[$subjek_id] = $item;
            // $arrRekenings[$rekening] = $rekening;
            $arrDatas[$subjek_id] = $item;
        }

        // arrPrint($sumSubjek);
        // matiHere(__LINE__);

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // arrPrintPink($src_yang_lalu);
        // $sumSubjek = array();
        $rekening_subjek_2 = array(
            "582spd", "7499", "382spd"
        );
        $sumSubjek2 = array();
        foreach ($src_yang_lalu as $item) {

            $seller_id = $item['seller_id'];
            $subjek_id = $item['master_id'];
            // $subjek_id = $item['master_id'];
            // $qty_debet = $item['qty_debet_reguler'];

            $debet = $item['debet'];
            $kredit = $item['kredit'];

            $rekening = $item['rekening'];
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            foreach ($src_koloms as $src_kolom) {
                $nilai = $item[$src_kolom] * 1;

                // if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
                //     $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
                // }
                $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $nilai;

                $sumSubjek2[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $nilai;
            }


            //---------------------------------------------------------
            $sumSubjek[$subjek_id]['rekening'] = $rekening;

            // $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
            // $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];

            // $arrSubjek[$subjek_id] = $item;
            // if($rekening == "582spd"){
            if (in_array($rekening, $rekening_subjek_2)) {
                $arrSubjek_2[$subjek_id][] = $item;
            }

            // $arrRekenings[$rekening] = $rekening;
            $arrSeller[$seller_id] = $item;
        }

        // cekHijau(sizeof($sumSubjek));
        // arrPrintHijau($sumSubjek);
        // arrPrint($arrSubjek_2);
        // test_table($sumSubjek);
        // matiHere(__LINE__);

        /* ----------------------------------------------------------------
         * filter untuk membuang prevous outstandinf yg dibawah nilai 1
         * ----------------------------------------------------------------*/
        foreach ($arrSubjek_2 as $mst_id => $item) {
            $arrSubjek_3[$mst_id] = end($item);
        }
        foreach ($arrSubjek_3 as $mast_id => $item) {
            // if ($item['rekening'] == "582spd" && $item["kredit"] >= 1) {
            //     $arrSubjek[$mast_id] = $item;
            // }
            // if ($item['rekening'] == "7499" && $item["kredit"] >= 1) {
            //     $arrSubjek[$mast_id] = $item;
            // }
            if (in_array($rekening, $rekening_subjek_2) && ($item["kredit"] >= 1)) {
                $arrSubjek[$mast_id] = $item;
            }
        }
        // $arrSubjek = $arrSubjek_2;

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        $qty_kirim = 0;
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            $rekening = $sbjDatas['rekening'];
            $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
            $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
            $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
            $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
            $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
            $now_saldo_kirim_582spd_old = isset($sbjDatas['now_saldo_kirim_582spd_old']) ? $sbjDatas['now_saldo_kirim_582spd_old'] : "0";
            $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";
            $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";
            $now_saldo_kirim_582spd_new = isset($sbjDatas['now_saldo_kirim_582spd_new']) ? $sbjDatas['now_saldo_kirim_582spd_new'] : "0";

            /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
            if ($now_saldo_order_582so > 0) {
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
                $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
            }
            else {
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
                $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
            }
            // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
            // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
            $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
            $last_kredit_582spd = 0;
            // if ($now_kredit_582spd > 0) {
            //     $last_kredit_582spd = ($now_kredit_582spd * 1);
            // }
            // else {
            // $last_kredit_582spd = $prev_kredit_582spd - $now_saldo_kirim_582spd_old - 0 + $now_kredit_582spd;
            $last_kredit_582spd = (($prev_kredit_582spd + $now_saldo_order_582so) - $now_saldo_reject_582spd - $now_saldo_kirim_582spd - $now_saldo_closed_582spd);
            // }

            // if($last_kredit_582spd > 0){

            // cekMerah("$sbj_id || $last_kredit_582spd = (($prev_kredit_582spd + $now_saldo_order_582so) - $now_saldo_reject_582spd - $now_saldo_kirim_582spd - $now_saldo_closed_582spd);");
            // }
            $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;

        }

        // arrPrintWebs($sumSubjekSeller2);
        // arrPrintWebs($sumSubjekSeller);
        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // arrPrintHijau($arrSubjek);
        //   matiHere(__LINE__);

        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];
            $transParams = $tr_datas[$subj_id];

            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumParams + $transParams;
            // $hasilOlahan_1[] = $itemParam;
        }
        // arrPrintKuning($hasilOlahan_1);
        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            "master_id"                  => array(
                "label"   => "mid",
                "rowspan" => "2",
                "topth"   => true,
            ),
            // "seller_id"            => array(
            //     "label"   => "sid",
            //     "rowspan" => "2",
            // ),
            // "seller_nama"          => array(
            //     "label"   => "salesman",
            //     "rowspan" => "2",
            //     "links"   => array(
            //         // "target"  => "laporan/Crm/cekoutstandingraw",
            //         "target"  => "laporan/Crm/cekoutstandingtransaksi",
            //         "title"   => "Transaksi per salesman",
            //         "key"     => "seller_id",
            //         "heading" => "seller_nama",
            //     ),
            //     // "collapsible" => array(
            //     //     "layout" => false,
            //     //     "key"    => "seller_id",
            //     //     "target" => "laporan/Crm/cekoutstandingtransaksi",
            //     // ),
            // ),
            "customer_nama"              => array(
                "label"   => "customer",
                "rowspan" => "2",
                "topth"   => true,
            ),
            "dtime"                      => array(
                "label"      => "tanggal",
                "rowspan"    => "2",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
                "topth"      => true,
            ),
            "extern_nama"                => array(
                "label"      => "nomer so",
                "rowspan"    => "2",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
                "topth"      => true,
            ),
            /*---yg urut pakai ini ya gais--*/
            // "_company_cabangID_modul_subModul_jenisTr_stepCode_olehID" => array(
            //     "label"   => "counter oleh",
            //     "rowspan" => "2",
            //     "attr"    => "class='text-right'",
            // ),
            // "_company_cabangID_modul_subModul_jenisTr_stepCode_sellerID" => array(
            //     "label"   => "counter",
            //     "rowspan" => "2",
            //     "attr"    => "class='text-right'",
            // ),

            /*previous rinci*/
            // "prev_qty_order"       => array(
            //     "label"      => "previous order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_saldo_debet_582so"                                     => array(
            //     "label"      => "order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_qty_kirim"       => array(
            //     "label"      => "previous kirim qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kirim"           => array(
            //     "label"      => "previous kirim value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*prevous outstanding*/
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "summary"    => true,
            // ),
            "prev_kredit_582spd"         => array(
                "label"      => "outstanding loc. value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            // "prev_kredit_7499"                                         => array(
            //     "label"      => "outstanding proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kredit_382spd"                                         => array(
            //     "label"      => "outstanding exp. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_order_582so"      => array(
                "label"      => "local value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            // "now_saldo_order_588so"                                      => array(
            //     "label"      => "project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_382so"                                      => array(
            //     "label"      => "export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_reject_582spd"    => array(
                "label"      => "local reject value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_saldo_closed_582spd"    => array(
                "label"      => "local closed value",
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
            "now_saldo_kirim_582spd_new" => array(
                "label"      => "new local",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "now_saldo_kirim_582spd_old" => array(
                "label"      => "last local",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            // "now_saldo_kirim_7499"                                 => array(
            //     "label"      => "project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_382so"                                 => array(
            //     "label"      => "export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),

            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            // "now_saldo_kirim_582spd"      => array(
            //     "label"      => "total kirim",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_total"          => array(
            //     "label"      => "new packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_batal"      => array(
            //     "label"      => "new canceled packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_batal"          => array(
            //     "label"      => "new canceled packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_netto"      => array(
            //     "label"      => "new netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto"          => array(
            //     "label"      => "new netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*outstanding*/
            // "qty_outstanding"      => array(
            //     "label"      => "new outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "summary"    => true,
            // ),
            "now_kredit_582spd_new"      => array(
                "label"      => "new value",
                "format"     => "formatField_he_format",
                "format_key" => "netto",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "logics"     => array(
                    "nilai" => "<0",
                    // "text"  => "termasuk pengiriman dari outstanding sebelumnya",
                ),
                "summary"    => true,
            ),
            /*kiriman sebelumnya*/
            // "qty_kirim_netto_old"  => array(
            //     "label"      => "netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_kredit_582spd_old"      => array(
                "label"      => "last value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            // "now_kredit_7499"                                      => array(
            //     "label"      => "last project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_382spd"                                      => array(
            //     "label"      => "last value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*ytd rinci*/
            // "ytd_qty_order"        => array(
            //     "label"      => "order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_order"            => array(
            //     "label"      => "order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_qty_kirim"        => array(
            //     "label"      => "kirim qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_kirim"            => array(
            //     "label"      => "kirim value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            /*last outstanding*/
            // "ytd_qty_outstanding"  => array(
            //     "label"      => "outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            "last_kredit_582spd"         => array(
                "label"      => "total value",
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
            $judul_lap = "MTD " . dtimeNow('d F Y H:i');
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
            "mode"        => "langsung",
            "title"       => "Laporan Sales Order ($seller_nama)" . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // "arrTopHeaders"  => $arrTopHeaders,
            "modal_size"  => true,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "loader_div"  => "sum_dua",
        );
        $this->load->view("outstanding", $data);
    }

    /*dengan project */
    public function cekoutstandingseller_2()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // // "0"   => array("qty" => "0", "nilai" => "0",),
            // // "57"  => array("qty" => "0", "nilai" => "0",),
            // // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            // // "61"  => array("qty" => "44", "nilai" => "99561369",),
            // "65"  => array("qty" => "9", "nilai" => "21598185",),
            // // "65"  => array("qty" => "9", "nilai" => "18523185",),
            // // "69"  => array("qty" => "111936", "nilai" => "10243373229",),
            // // "69"  => array("qty" => "111936", "nilai" => "10457787651",),
            // "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            // "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // // "576" => array("qty" => "132", "nilai" => "449897306.72834",),
            // "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            // // "718" => array("qty" => "15", "nilai" => "119260509",),
            // // "286" => array("qty" => "2041", "nilai" => "0",),
            // // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // // "718" => array("qty" => "0", "nilai" => "0",),
            // // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "1", "nilai" => "4009010",),
            // // "825" => array("qty" => "0", "nilai" => "0",),
            // // "826" => array("qty" => "0", "nilai" => "0",),
            // // "712" => array("qty" => "0", "nilai" => "0",),
            // // "567" => array("qty" => "189", "nilai" => "0",),
            // // // "551" => array("qty" => "0", "nilai" => "0",),
            // // "205" => array("qty" => "28", "nilai" => "11886000",),
            // // "182" => array("qty" => "-2304", "nilai" => "2252939845",),
            // // "77"  => array("qty" => "-2", "nilai" => "-2342344",),
            // // "551"  => array("qty" => "8615", "nilai" => "5637500091",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        /* ---------------------------------------------------------------------
        * filter khusus
        * ---------------------------------------------------------------------*/
        // arrPrint($_GET);
        if (isset($_GET['ky'])) {
            $get_condites = array(
                $_GET['ky'] => $_GET[$_GET['ky']]
            );
            if (method_exists($dt, "getSellerId")) {

                // $dt->setSellerId('808');
            }
        }
        // cekHere(ipadd());
        if (ipadd() == "202.65.117.72") {
            // $dt->setSellerId('65');
            // $dt->setSellerId('73');
            // ------------------
            // $dt->setSellerId('61');
            // $dt->setSellerId('69');
        }
        // $dt->setSellerId('844');
        // $dt->setSellerId('65');
        // $dt->setSellerId('69');
        // $dt->setMasterId('100786');

        $src_00 = $dt->getSaldoSeller($date1, $date2);
        $src_mtd = $src_00['mtd'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        $src_koloms = $src_00['kolom'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // arrPrint($src_mtd);
        // arrPrint($src_00);
        // matiHere(__LINE__ . __FILE__);

        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        $sumSubjek = array();
        $arrSubjek = array();
        foreach ($src_mtd as $item) {

            $seller_id = $item['seller_id'];
            $subjek_id = $item['seller_id'];
            // $subjek_id = $item['master_id'];
            // $qty_debet = $item['qty_debet_reguler'];

            $debet = $item['debet'];
            $kredit = $item['kredit'];

            $rekening = $item['rekening'];
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            foreach ($src_koloms as $src_kolom) {
                $$src_kolom = $item[$src_kolom];

                $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
            }
            // $sumSubjek[$subjek_id]['now_debet_' . $rekening] = $debet;
            // //
            // // // ---------------------------------------------------------
            // // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
            // //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
            // // }
            // $sumSubjek[$subjek_id]['now_kredit_' . $rekening] = $kredit;


            //---------------------------------------------------------
            $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
            $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
            // $arrRekenings[$rekening] = $rekening;
            $arrSeller[$seller_id] = $item;
        }

        /* ------------------------------------------------------------------------------------------
         * yang lalu dengan prefik prev
         * ------------------------------------------------------------------------------------------*/
        // arrPrintPink($src_yang_lalu);
        // $sumSubjek = array();
        foreach ($src_yang_lalu as $item) {

            $seller_id = $item['seller_id'];
            $subjek_id = $item['seller_id'];
            // $subjek_id = $item['master_id'];
            // $qty_debet = $item['qty_debet_reguler'];

            $debet = $item['debet'];
            $kredit = $item['kredit'];

            $rekening = $item['rekening'];
            foreach ($src_koloms as $src_kolom) {
                if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
                    $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
                }
                $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
            }
            // if (!isset($sumSubjek[$subjek_id]['prev_debet_' . $rekening])) {
            //     $sumSubjek[$subjek_id]['prev_debet_' . $rekening] = 0;
            // }
            // $sumSubjek[$subjek_id]['prev_debet_' . $rekening] += $debet;
            //
            // // ---------------------------------------------------------
            // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
            //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
            // }
            // $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] += $kredit;


            //---------------------------------------------------------
            $sumSubjek[$subjek_id]['rekening'] = $rekening;
            $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
            $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
            // $arrRekenings[$rekening] = $rekening;
            $arrSeller[$seller_id] = $item;
        }
        // arrPrint($sumSubjek);
        // test_table($sumSubjek);
        // matiHere(__LINE__);

        /* ----------------------------------------------------------------------------------------------------------
         * Rumus san order netto order - kirim - return_kirim
         * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
         * ----------------------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        $qty_kirim = 0;
        foreach ($sumSubjek as $sbj_id => $sbjDatas) {
            $rekening = $sbjDatas['rekening'];
            $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
            $now_saldo_order_588so = isset($sbjDatas['now_saldo_order_588so']) ? $sbjDatas['now_saldo_order_588so'] : "0";
            $now_saldo_order_382so = isset($sbjDatas['now_saldo_order_382so']) ? $sbjDatas['now_saldo_order_382so'] : "0";
            $now_saldo_order_all = $now_saldo_order_582so + $now_saldo_order_588so + $now_saldo_order_382so;

            $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
            $now_kredit_7499 = isset($sbjDatas['now_kredit_7499']) ? $sbjDatas['now_kredit_7499'] : "0";
            $now_kredit_382spd = isset($sbjDatas['now_kredit_382spd']) ? $sbjDatas['now_kredit_382spd'] : "0";
            $now_kredit_all = $now_kredit_582spd + $now_kredit_7499 + $now_kredit_382spd;

            $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
            $now_saldo_kirim_7499 = isset($sbjDatas['now_saldo_kirim_7499']) ? $sbjDatas['now_saldo_kirim_7499'] : "0";
            $now_saldo_kirim_382spd = isset($sbjDatas['now_saldo_kirim_382spd']) ? $sbjDatas['now_saldo_kirim_382spd'] : "0";
            $now_saldo_kirim_all = $now_saldo_kirim_582spd + $now_saldo_kirim_7499 + $now_saldo_kirim_382spd;

            $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
            $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";

            $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";

            $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";
            $prev_kredit_7499 = isset($sbjDatas['prev_kredit_7499']) ? $sbjDatas['prev_kredit_7499'] : "0";
            $prev_kredit_382spd = isset($sbjDatas['prev_kredit_382spd']) ? $sbjDatas['prev_kredit_382spd'] : "0";
            $prev_kredit_all = $prev_kredit_582spd + $prev_kredit_7499 + $prev_kredit_382spd;

            $sumSubjek[$sbj_id]["prev_kredit_all"] = $prev_kredit_all;
            $sumSubjek[$sbj_id]["now_saldo_order_all"] = $now_saldo_order_all;
            $sumSubjek[$sbj_id]["now_saldo_kirim_all"] = $now_saldo_kirim_all;
            $sumSubjek[$sbj_id]["now_kredit_all"] = $now_kredit_all;
            /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
            if ($now_saldo_order_582so > 0) {
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
                $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
            }
            else {
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
                $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
                $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
            }
            if ($now_saldo_order_all > 0) {
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = $now_saldo_kirim_all;
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_all_new"] = $now_kredit_all;
                $sumSubjek[$sbj_id]["now_kredit_all_old"] = 0;
            }
            else {
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = 0;
                $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = $now_saldo_kirim_all;
                $sumSubjek[$sbj_id]["now_kredit_all_new"] = 0;
                $sumSubjek[$sbj_id]["now_kredit_all_old"] = $now_kredit_all;
            }
            // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
            // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
            $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
            $last_kredit_582spd = 0;
            if ($now_kredit_582spd > 0) {
                $last_kredit_582spd = ($now_kredit_582spd * 1) . "***";
            }
            else {
                //     //$sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"]
                // cekHijau("$now_saldo_order_582so");
                $last_kredit_582spd = ($prev_kredit_582spd + $now_saldo_order_582so - $now_saldo_reject_582spd - $now_saldo_closed_582spd - $now_saldo_kirim_582spd);
            }
            $last_kredit_7499 = $prev_kredit_7499 + $now_kredit_7499;
            $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;
            $sumSubjek[$sbj_id]["last_kredit_allspd"] = $last_kredit_7499 + $last_kredit_582spd;
            $sumSubjek[$sbj_id]["last_kredit_all"] = $prev_kredit_all + $now_saldo_order_all - $now_saldo_closed_582spd - $now_saldo_reject_582spd - $now_saldo_kirim_all;
        }

        // arrPrintWebs($sumSubjekSeller2);
        // arrPrintWebs($sumSubjekSeller);
        // arrPrintPink($sumSubjek);
        // test_table($sumSubjek);
        // arrPrintPink($sumSubjek);
        // arrPrintHijau($arrSubjek);
        //   matiHere(__LINE__);
        // /* --------------------------------------------------------------------------------------------------
        //   * #3 pengumpulan data menjadi data siap tempur
        //   * --------------------------------------------------------------------------------------------------*/
        $hasilOlahan_1 = array();
        foreach ($arrSubjek as $subj_id => $itemParam) {
            $sumParams = $sumSubjek[$subj_id];


            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumParams;
            // $hasilOlahan[$customer_id] = $itemParam;
        }

        $masterData = $hasilOlahan_1;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            // "master_id"            => array(
            //     "label" => "mid",
            // ),
            "seller_id"               => array(
                "label"   => "sid",
                "rowspan" => "2",
            ),
            "seller_nama"             => array(
                "label"   => "salesman",
                "rowspan" => "2",
                // "links"   => array(
                //     // "target"  => "laporan/Crm/cekoutstandingraw",
                //     "target"  => "laporan/Crm/cekoutstandingtransaksi",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
                // "collapsible" => array(
                //     "layout" => false,
                //     "key"    => "seller_id",
                //     "target" => "laporan/Crm/cekoutstandingtransaksi",
                // ),
                "popup"   => array(
                    "key"    => "seller_id",
                    // "target" => "laporan/Outstanding/perindekshow/cekoutstandingtransaksi",
                    "target" => "laporan/Outstanding/viewemyoutstanding",
                ),
            ),
            // "customer_nama"        => array(
            //     "label" => "customer",
            //     "links" => array(
            //         // "target"  => "laporan/Crm/cekoutstandingraw",
            //         "target"  => "laporan/Crm/cekoutstandingtransaksi",
            //         "title"   => "Transaksi per salesman",
            //         "key"     => "seller_id",
            //         "heading" => "seller_nama",
            //     ),
            //     // "collapsible" => array(
            //     //     "layout" => false,
            //     //     "key"    => "seller_id",
            //     //     "target" => "laporan/Crm/cekoutstandingtransaksi",
            //     // ),
            // ),
            /*prevous outstanding*/
            // "prev_qty_order" => array(
            //     "label"      => "previous order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_saldo_order_582so" => array(
            //     "label"      => "previous order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "prev_kredit_all"         => array(
                "label"      => "previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "prev_kredit_582spd"      => array(
                "label"      => "previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "prev_kredit_7499"        => array(
                "label"      => "previous outstanding proj. value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*all*/
            "now_saldo_order_all"     => array(
                "label"      => "new order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*project*/
            "now_saldo_order_588so"   => array(
                "label"      => "new order proj. value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
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
            // "now_saldo_order_582so"          => array(
            //     "label"      => "new order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_order_582so"   => array(
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
            "now_saldo_reject_582spd" => array(
                "label"      => "new reject value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_saldo_closed_582spd" => array(
                "label"      => "new close value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),

            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_582spd"         => array(
            //     "label"      => "packing list local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_batal"         => array(
            //     "label"      => "qty_kirim_batal",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            // "qty_kirim_netto"      => array(
            //     "label"      => "new netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_kirim_all"     => array(
                "label"      => "new netto packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_saldo_kirim_582spd"  => array(
                "label"      => "new netto packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*outstanding*/
            // "qty_outstanding"      => array(
            //     "label"      => "new outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_kredit_all"          => array(
                "label"      => "new outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "logics"     => array(
                    "nilai" => "<0",
                    "text"  => "termasuk pengiriman dari outstanding sebelumnya",
                ),
                "summary"    => true,
            ),
            "now_kredit_582spd"       => array(
                "label"      => "new outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "logics"     => array(
                    "nilai" => "<0",
                    "text"  => "termasuk pengiriman dari outstanding sebelumnya",
                ),
                "summary"    => true,
            ),
            /*kirim old*/
            // "qty_kirim_netto_old"  => array(
            //     "label"      => "netto pl qty previous",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto_old"        => array(
            //     "label"      => "netto pl value previous",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_netto_all"  => array(
            //     "label"      => "all netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto_all"        => array(
            //     "label"      => "all netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*outstanding project*/
            "now_kredit_7499"         => array(
                "label"      => "new outstanding proj. value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "logics"     => array(
                    "nilai" => "<0",
                    "text"  => "termasuk pengiriman dari outstanding sebelumnya",
                ),
                "summary"    => true,
            ),
            /*last outstanding*/
            // "ytd_qty_outstanding"  => array(
            //     "label"      => "last outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            "last_kredit_582spd"      => array(
                "label"      => "outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            "last_kredit_allspd"      => array(
                "label"      => "all outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "last_kredit_all"         => array(
                "label"      => "all outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "summary"    => true,
            ),
            // "seller_id"      => array(
            //     "label"      => "sID",
            // ),
        );
        // arrPrintKuning($sumSubjek);
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
            "title"       => "Laporan Salesman Outstanding " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
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
        $this->load->view("outstanding", $data);
    }

    private function CreateStatik($bln_yg_dicari, $last_tr_id, $dtime_now, $coll_nama, $mg, $src_000, $perintah)
    {
        $bln_ini = formatTanggal($dtime_now, 'Y-m');
        $tmp_statik = array();
        if ($perintah == "delete") {
            /*delete mdb*/
            cekHijau("update");
            $mg->where("dtime_bulan", $bln_yg_dicari);
            // $mg->where("dtime_bulan", "2022-03");
            $mg->delete($coll_nama);
        }

        foreach ($src_000 as $item) {
            $dt_umum = array(
                "dtime_bulan" => $bln_yg_dicari,
                "dtime"       => $dtime_now,
                "dtime_stamp" => strtotime($dtime_now),
                "last_trid"   => $last_tr_id,
                "trash"       => 0,
            );
            $tmp_statik = $item + $dt_umum;
            // arrPrintKuning($tmp_statik);

            if ($bln_ini == $bln_yg_dicari) {
                // if($perintah == "delete"){
                //     /*delete mdb*/
                //     cekHijau("update");
                //     $mg->where("dtime_bulan", $bln_yg_dicari);
                //     // $mg->where("dtime_bulan", "2022-03");
                //     $mg->delete($coll_nama);
                // }
            }
            else {
            }
            /*
             * untuk buat data baru bl yg dicari
             * */
            // cekKuning("insert");
            $mg->insert($coll_nama, $tmp_statik);


        }
    }

    public function cekoutstandingseller()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // // "0"   => array("qty" => "0", "nilai" => "0",),
            // // "57"  => array("qty" => "0", "nilai" => "0",),
            // // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            // // "61"  => array("qty" => "44", "nilai" => "99561369",),
            // "65"  => array("qty" => "9", "nilai" => "21598185",),
            // // "65"  => array("qty" => "9", "nilai" => "18523185",),
            // // "69"  => array("qty" => "111936", "nilai" => "10243373229",),
            // // "69"  => array("qty" => "111936", "nilai" => "10457787651",),
            // "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            // "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // // "576" => array("qty" => "132", "nilai" => "449897306.72834",),
            // "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            // // "718" => array("qty" => "15", "nilai" => "119260509",),
            // // "286" => array("qty" => "2041", "nilai" => "0",),
            // // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // // "718" => array("qty" => "0", "nilai" => "0",),
            // // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "1", "nilai" => "4009010",),
            // // "825" => array("qty" => "0", "nilai" => "0",),
            // // "826" => array("qty" => "0", "nilai" => "0",),
            // // "712" => array("qty" => "0", "nilai" => "0",),
            // // "567" => array("qty" => "189", "nilai" => "0",),
            // // // "551" => array("qty" => "0", "nilai" => "0",),
            // // "205" => array("qty" => "28", "nilai" => "11886000",),
            // // "182" => array("qty" => "-2304", "nilai" => "2252939845",),
            // // "77"  => array("qty" => "-2", "nilai" => "-2342344",),
            // // "551"  => array("qty" => "8615", "nilai" => "5637500091",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        $bln_ini = dtimeNow("Y-m");
        // --------------------------LAST-TRANSAKSI-ID-----------------------------------------------
        $last_tr_id = $this->cekLastTransaksiId();
        // $last_tr_id = "194881";
        // $last_tr_id = "195049";
        // ---------------------------CONFIG-heWEB--------------------------------------------------
        $mdb_connection = $this->MdbConnection();
        $bln_yg_dicari = formatTanggal($date1, "Y-m");

        cekHijau("bln ini: $bln_ini bln dicari: $bln_yg_dicari");
        // ---------------------------MONGO-DB------------------------------------------------
        if ($mdb_connection == true) {
            $this->load->library("Mongo_db");
            $mg = new Mongo_db();
            $coll_nama = "outstanding_seller_blnan";
            // ---------------------------------------------------------------------------
            // $mg->limit(1);
            // $mg->order_by(array("_id" => "desc"));
            $mg->order_by(array("seller_nama" => "asc"));
            $mdb_condites = array(
                "dtime_bulan" => $bln_yg_dicari
            );
            $mg->where($mdb_condites);
            // $mg->
            $src_mdb = $mg->get($coll_nama);
            $jml_data_mdb = sizeof($src_mdb);
            $tmps = $src_mdb[0];
            // arrPrintPink($tmps["last_trid"]);
        }
        // view the last query
        // $command = new MongoDB\Driver\Command(["currentOp" => true]);
        // $cursor = $manager->executeCommand("admin", $command);
        // $lastOp = current($cursor->toArray());
        // $query = end($lastOp->inprog)->query;
        // echo "Last query executed: " . json_encode($query);
        // ---------------------------------------------------------------------
        // $mg->where("dtime_bulan", $bln_yg_dicari);
        // // $mg->where("dtime_bulan", "2022-03");
        // $mg->delete($coll_nama);
        // mati_disini(__LINE__);

        // ---------------------------------------------------------------------
        $key_mdb = "last_trid";
        $mdb_last_trid = key_exists($key_mdb, $tmps) ? $tmps[$key_mdb] : 0;
        $dtime_now = dtimeNow();
        cekHijau("mdb:$mdb_last_trid == db:$last_tr_id");
        // if (($jml_data_mdb > 0) && ($mdb_last_trid == $last_tr_id)) {

        $pakai_ini = 1;
        if($pakai_ini == 1){
            if (($jml_data_mdb > 0)) {

                cekHijau("data statik " . __LINE__);

                if (($bln_ini == $bln_yg_dicari) && ($mdb_last_trid == $last_tr_id)) {
                    $src_000 = $src_mdb;
                    cekPink("bln ini statik");
                }
                elseif (($bln_ini == $bln_yg_dicari) && ($mdb_last_trid != $last_tr_id)) {

                    cekKuning("perbarui dat bl ini " . __LINE__ . " ($bln_ini == $bln_yg_dicari) && ($mdb_last_trid != $last_tr_id)");
                    $src_000 = array();
                    $src_000 = $dt->callPerSeller($date1, $date2);
                    if ($mdb_connection == true) {
                        // $perintah = "delete";
                        // $this->CreateStatik($bln_yg_dicari, $last_tr_id, $dtime_now, $coll_nama, $mg, $src_000, $perintah);
                    }
                }
                else {
                    cekBiru("statik " . __LINE__);
                    $src_000 = $src_mdb;
                }


                //     cekKuning();
                // }
                //
                // if (($jml_data_mdb > 0)) {
            }
            else {
                cekHitam("ada pembaharuan data " . __LINE__);
                $perintah = "create";
                // mati_disini(__LINE__);
                /* ---------------------------------------------------------------------
                * filter khusus
                * ---------------------------------------------------------------------*/
                // arrPrint($_GET);
                if (isset($_GET['ky'])) {
                    $get_condites = array(
                        $_GET['ky'] => $_GET[$_GET['ky']]
                    );
                    if (method_exists($dt, "getSellerId")) {

                        // $dt->setSellerId('808');
                    }
                }
                // cekHere(ipadd());
                if (ipadd() == "202.65.117.72") {
                    // $dt->setSellerId('65');
                    // $dt->setSellerId('73');
                    // ------------------
                    // $dt->setSellerId('61');
                    // $dt->setSellerId('69');
                }
                // $dt->setSellerId('844');
                // $dt->setSellerId('65');
                // $dt->setSellerId('69');
                // $dt->setMasterId('100786');
                // mati_disini(__LINE__);
                // $src_00 = $dt->getSaldoSeller($date1, $date2);
                // $src_000[] = array("gg" => 11);
                $src_000 = array();
                $src_000 = $dt->callPerSeller($date1, $date2);
                // $src_000 = $dt->getSaldoSellerTahun($date1, $date2);
                /**/
                // $src_mtd = $src_00['mtd'];
                // $src_yang_lalu = $src_00['ytd_previous'];
                // $arrRekenings = $src_00['rekening'];
                // $arrTransaksiTipes = $src_00['transaksi_tipe'];
                // $src_koloms = $src_00['kolom'];
                // // cekBiru(sizeof($src_ytd));
                // // arrPrint($src_ytd);
                // // arrPrint($src_mtd);
                // // arrPrint($src_00);
                // // matiHere(__LINE__ . __FILE__);
                //
                // /* ------------------------------------------------------------------------------------------
                //  * saat ini
                //  * ------------------------------------------------------------------------------------------*/
                // $sumSubjek = array();
                // $arrSubjek = array();
                // foreach ($src_mtd as $item) {
                //
                //     $seller_id = $item['seller_id'];
                //     $subjek_id = $item['seller_id'];
                //     // $subjek_id = $item['master_id'];
                //     // $qty_debet = $item['qty_debet_reguler'];
                //
                //     $debet = $item['debet'];
                //     $kredit = $item['kredit'];
                //
                //     $rekening = $item['rekening'];
                //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
                //     foreach ($src_koloms as $src_kolom) {
                //         $$src_kolom = $item[$src_kolom];
                //
                //         $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
                //     }
                //     // $sumSubjek[$subjek_id]['now_debet_' . $rekening] = $debet;
                //     // //
                //     // // // ---------------------------------------------------------
                //     // // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
                //     // //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
                //     // // }
                //     // $sumSubjek[$subjek_id]['now_kredit_' . $rekening] = $kredit;
                //
                //
                //     //---------------------------------------------------------
                //     $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
                //     $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
                //     // $arrRekenings[$rekening] = $rekening;
                //     $arrSeller[$seller_id] = $item;
                // }
                //
                // /* ------------------------------------------------------------------------------------------
                //  * yang lalu dengan prefik prev
                //  * ------------------------------------------------------------------------------------------*/
                // // arrPrintPink($src_yang_lalu);
                // // $sumSubjek = array();
                // foreach ($src_yang_lalu as $item) {
                //
                //     $seller_id = $item['seller_id'];
                //     $subjek_id = $item['seller_id'];
                //     // $subjek_id = $item['master_id'];
                //     // $qty_debet = $item['qty_debet_reguler'];
                //
                //     $debet = $item['debet'];
                //     $kredit = $item['kredit'];
                //
                //     $rekening = $item['rekening'];
                //     foreach ($src_koloms as $src_kolom) {
                //         if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
                //             $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
                //         }
                //         $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
                //     }
                //     // if (!isset($sumSubjek[$subjek_id]['prev_debet_' . $rekening])) {
                //     //     $sumSubjek[$subjek_id]['prev_debet_' . $rekening] = 0;
                //     // }
                //     // $sumSubjek[$subjek_id]['prev_debet_' . $rekening] += $debet;
                //     //
                //     // // ---------------------------------------------------------
                //     // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
                //     //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
                //     // }
                //     // $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] += $kredit;
                //
                //
                //     //---------------------------------------------------------
                //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
                //     $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
                //     $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
                //     // $arrRekenings[$rekening] = $rekening;
                //     $arrSeller[$seller_id] = $item;
                // }
                // // arrPrint($sumSubjek);
                // // test_table($sumSubjek);
                // // matiHere(__LINE__);
                //
                // /* ----------------------------------------------------------------------------------------------------------
                //  * Rumus san order netto order - kirim - return_kirim
                //  * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
                //  * ----------------------------------------------------------------------------------------------------------*/
                // // $sumSubjek = array();
                // $qty_kirim = 0;
                // foreach ($sumSubjek as $sbj_id => $sbjDatas) {
                //     $rekening = $sbjDatas['rekening'];
                //     $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
                //     $now_saldo_order_588so = isset($sbjDatas['now_saldo_order_588so']) ? $sbjDatas['now_saldo_order_588so'] : "0";
                //     $now_saldo_order_382so = isset($sbjDatas['now_saldo_order_382so']) ? $sbjDatas['now_saldo_order_382so'] : "0";
                //     $now_saldo_order_all = $now_saldo_order_582so + $now_saldo_order_588so + $now_saldo_order_382so;
                //
                //     $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
                //     $now_saldo_reject_7499 = isset($sbjDatas['now_saldo_reject_7499']) ? $sbjDatas['now_saldo_reject_7499'] : "0";
                //     $now_saldo_reject_382spd = isset($sbjDatas['now_saldo_reject_382spd']) ? $sbjDatas['now_saldo_reject_382spd'] : "0";
                //     $now_saldo_reject_all = $now_saldo_reject_582spd + $now_saldo_reject_7499 + $now_saldo_reject_382spd;
                //
                //     $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
                //     $now_saldo_closed_7499 = isset($sbjDatas['now_saldo_closed_7499']) ? $sbjDatas['now_saldo_closed_7499'] : "0";
                //     $now_saldo_closed_382spd = isset($sbjDatas['now_saldo_closed_382spd']) ? $sbjDatas['now_saldo_closed_382spd'] : "0";
                //     $now_saldo_closed_all = $now_saldo_closed_582spd + $now_saldo_closed_7499 + $now_saldo_closed_382spd;
                //
                //     $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
                //     $now_kredit_7499 = isset($sbjDatas['now_kredit_7499']) ? $sbjDatas['now_kredit_7499'] : "0";
                //     $now_kredit_382spd = isset($sbjDatas['now_kredit_382spd']) ? $sbjDatas['now_kredit_382spd'] : "0";
                //     $now_kredit_all = $now_kredit_582spd + $now_kredit_7499 + $now_kredit_382spd;
                //
                //     $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
                //     $now_saldo_kirim_7499 = isset($sbjDatas['now_saldo_kirim_7499']) ? $sbjDatas['now_saldo_kirim_7499'] : "0";
                //     $now_saldo_kirim_382spd = isset($sbjDatas['now_saldo_kirim_382spd']) ? $sbjDatas['now_saldo_kirim_382spd'] : "0";
                //     $now_saldo_kirim_all = $now_saldo_kirim_582spd + $now_saldo_kirim_7499 + $now_saldo_kirim_382spd;
                //
                //     $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
                //     $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
                //
                //     $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";
                //
                //     $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";
                //     $prev_kredit_7499 = isset($sbjDatas['prev_kredit_7499']) ? $sbjDatas['prev_kredit_7499'] : "0";
                //     $prev_kredit_382spd = isset($sbjDatas['prev_kredit_382spd']) ? $sbjDatas['prev_kredit_382spd'] : "0";
                //     $prev_kredit_all = $prev_kredit_582spd + $prev_kredit_7499 + $prev_kredit_382spd;
                //     $now_saldo_order_netto_all = $now_saldo_order_all - $now_saldo_reject_all - $now_saldo_closed_all;
                //     // $now_saldo_order_netto_all = $now_saldo_order_all;
                //
                //     $sumSubjek[$sbj_id]["prev_kredit_all"] = $prev_kredit_all;
                //     $sumSubjek[$sbj_id]["now_saldo_order_all"] = $now_saldo_order_all;
                //     $sumSubjek[$sbj_id]["now_saldo_kirim_all"] = $now_saldo_kirim_all;
                //     $sumSubjek[$sbj_id]["now_kredit_all"] = $now_kredit_all;
                //     $sumSubjek[$sbj_id]["now_saldo_reject_all"] = $now_saldo_reject_all;
                //     $sumSubjek[$sbj_id]["now_saldo_closed_all"] = $now_saldo_closed_all;
                //     $sumSubjek[$sbj_id]["now_saldo_order_netto_all"] = $now_saldo_order_netto_all;
                //     /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
                //     if ($now_saldo_order_582so > 0) {
                //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
                //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
                //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
                //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
                //     }
                //     else {
                //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
                //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
                //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
                //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
                //     }
                //     if ($now_saldo_order_all > 0) {
                //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = $now_saldo_kirim_all;
                //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = 0;
                //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = $now_kredit_all;
                //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = 0;
                //     }
                //     else {
                //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = 0;
                //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = $now_saldo_kirim_all;
                //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = 0;
                //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = $now_kredit_all;
                //     }
                //     // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
                //     // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
                //     $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
                //     $last_kredit_582spd = 0;
                //     if ($now_kredit_582spd > 0) {
                //         $last_kredit_582spd = ($now_kredit_582spd * 1) . "***";
                //     }
                //     else {
                //         //     //$sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"]
                //         // cekHijau("$now_saldo_order_582so");
                //         $last_kredit_582spd = ($prev_kredit_582spd + $now_saldo_order_582so - $now_saldo_reject_582spd - $now_saldo_closed_582spd - $now_saldo_kirim_582spd);
                //     }
                //     $last_kredit_7499 = $prev_kredit_7499 + $now_kredit_7499;
                //     $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;
                //     $sumSubjek[$sbj_id]["last_kredit_allspd"] = $last_kredit_7499 + $last_kredit_582spd;
                //     $sumSubjek[$sbj_id]["last_kredit_all"] = $prev_kredit_all + $now_saldo_order_all - $now_saldo_closed_582spd - $now_saldo_reject_582spd - $now_saldo_kirim_all;
                // }
                //
                // // arrPrintWebs($sumSubjekSeller2);
                // // arrPrintWebs($sumSubjekSeller);
                // // arrPrintPink($sumSubjek);
                // // test_table($sumSubjek);
                // // arrPrintPink($sumSubjek);
                // // arrPrintHijau($arrSubjek);
                // //   matiHere(__LINE__);
                // // /* --------------------------------------------------------------------------------------------------
                // //   * #3 pengumpulan data menjadi data siap tempur
                // //   * --------------------------------------------------------------------------------------------------*/
                // $hasilOlahan_1 = array();
                // foreach ($arrSubjek as $subj_id => $itemParam) {
                //     $sumParams = $sumSubjek[$subj_id];
                //
                //
                //     // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
                //     $hasilOlahan_1[] = $itemParam + $sumParams;
                //     // $hasilOlahan[$customer_id] = $itemParam;
                // }
                //
                // $masterData = $hasilOlahan_1;

                /*---------NYIMPEN DATA KE TABLE DASHBOARD BIAR LOADNYA CEPAT---------------------*/

                if ($mdb_connection == true) {
                    cekBiru();
                    if ($bln_ini != $bln_yg_dicari) {
                        $last_tr_id = "0";
                    }

                    $this->CreateStatik($bln_yg_dicari, $last_tr_id, $dtime_now, $coll_nama, $mg, $src_000, $perintah);
                    // $tmp_statik = array();
                    // foreach ($src_000 as $item) {
                    //     $dt_umum = array(
                    //         "dtime_bulan" => $bln_yg_dicari,
                    //         "dtime"       => $dtime_now,
                    //         "dtime_stamp" => strtotime($dtime_now),
                    //         "last_trid"   => $last_tr_id,
                    //         "trash"       => 0,
                    //     );
                    //     $tmp_statik = $item + $dt_umum;
                    //     arrPrintKuning($tmp_statik);
                    //
                    //     if ($bln_ini == $bln_yg_dicari) {
                    //         /*update mdb*/
                    //         cekHijau("update");
                    //         // $mg->where("dtime_bulan", $bln_yg_dicari);
                    //         // $mg->set($tmp_statik);
                    //         // $mg->update($coll_nama);
                    //
                    //         $mg->where("dtime_bulan", $bln_yg_dicari);
                    //         // $mg->where("dtime_bulan", "2022-03");
                    //         $mg->delete($coll_nama);
                    //     }
                    //     else {
                    //     }
                    //     /*
                    //      * untuk buat data baru bl yg dicari
                    //      * */
                    //     cekKuning("insert");
                    //     $mg->insert($coll_nama, $tmp_statik);
                    //
                    //
                    // }
                }
                else {
                    cekHitam("done");
                }
                // -------------------------------------------------------------------------------
            }
        }
        else{
            cekHere("pakai yang ini");
            $src_000 = $dt->callPerSeller($date1, $date2);
        }

        // mati_disini(__LINE__);
        /**/
        $masterData = $src_000;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            // "master_id"            => array(
            //     "label" => "mid",
            // ),
            "seller_id"                  => array(
                "label"   => "sid",
                "rowspan" => "2",
            ),
            "seller_nama"                => array(
                "label"   => "salesman",
                "rowspan" => "2",
                // "links"   => array(
                //     // "target"  => "laporan/Crm/cekoutstandingraw",
                //     "target"  => "laporan/Crm/cekoutstandingtransaksi",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
                // "collapsible" => array(
                //     "layout" => false,
                //     "key"    => "seller_id",
                //     "target" => "laporan/Crm/cekoutstandingtransaksi",
                // ),
                "popup"   => array(
                    "key"    => "seller_id",
                    // "target" => "laporan/Outstanding/perindekshow/cekoutstandingtransaksi",
                    "target" => "laporan/Outstanding/viewemyoutstanding",
                ),
            ),
            // "customer_nama"        => array(
            //     "label" => "customer",
            //     "links" => array(
            //         // "target"  => "laporan/Crm/cekoutstandingraw",
            //         "target"  => "laporan/Crm/cekoutstandingtransaksi",
            //         "title"   => "Transaksi per salesman",
            //         "key"     => "seller_id",
            //         "heading" => "seller_nama",
            //     ),
            //     // "collapsible" => array(
            //     //     "layout" => false,
            //     //     "key"    => "seller_id",
            //     //     "target" => "laporan/Crm/cekoutstandingtransaksi",
            //     // ),
            // ),
            /*prevous outstanding*/
            // "prev_qty_order" => array(
            //     "label"      => "previous order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_saldo_order_582so" => array(
            //     "label"      => "previous order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "prev_kredit_all"            => array(
                "label"      => "previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            // "prev_kredit_582spd"     => array(
            //     "label"      => "previous outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kredit_7499"     => array(
            //     "label"      => "previous outstanding proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*all*/
            "now_saldo_order_all"        => array(
                "label"      => "new order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*project*/
            // "now_saldo_order_588so"  => array(
            //     "label"      => "new order proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order rincian*/
            // "qty_debet_reguler_582so"      => array(
            //     "label"      => "new order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_582so"          => array(
            //     "label"      => "new order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_582so"  => array(
            //     "label"      => "new netto order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            "now_saldo_reject_582spd"    => array(
                "label"      => "new reject value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_saldo_closed_582spd"    => array(
                "label"      => "new close value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_batal_nilai_9912"       => array(
                "label"      => "cancel value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            "now_saldo_order_return_all" => array(
                "label"      => "return value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            "now_saldo_order_netto_all"  => array(
                "label"      => "new order netto value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_582spd"         => array(
            //     "label"      => "packing list local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_batal"         => array(
            //     "label"      => "qty_kirim_batal",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            // "qty_kirim_netto"      => array(
            //     "label"      => "new netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_all_new"     => array(
            //     "label"      => "new netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_all_old"     => array(
            //     "label"      => "last netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_kirim_all"        => array(
                "label"      => "packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_saldo_batal_kirim_all"  => array(
                "label"      => "cancel value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            "now_saldo_return_kirim_all" => array(
                "label"      => "return value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            "now_saldo_kirim_netto_all"  => array(
                "label"      => "netto packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            // "now_saldo_kirim_582spd" => array(
            //     "label"      => "new netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*outstanding*/
            // "qty_outstanding"      => array(
            //     "label"      => "new outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_all"          => array(
            //     "label"      => "new outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            // "now_kredit_582spd"      => array(
            //     "label"      => "new outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*kirim old*/
            // "qty_kirim_netto_old"  => array(
            //     "label"      => "netto pl qty previous",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto_old"        => array(
            //     "label"      => "netto pl value previous",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_netto_all"  => array(
            //     "label"      => "all netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto_all"        => array(
            //     "label"      => "all netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*outstanding project*/
            // "now_kredit_7499"      => array(
            //     "label"      => "new outstanding proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*last outstanding*/
            // "ytd_qty_outstanding"  => array(
            //     "label"      => "last outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "last_kredit_582spd"      => array(
            //     "label"      => "outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "last_kredit_allspd"      => array(
            //     "label"      => "all outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            "last_kredit_all"            => array(
                "label"      => "all outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            // "seller_id"      => array(
            //     "label"      => "sID",
            // ),
        );
        // arrPrintKuning($sumSubjek);
        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
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
            // "title"       => "Laporan Sales Outstanding " . $judul_lap, // TIDAK COCOK diganti tg 12/12/2022
            "title"       => "Laporan Outstanding By Salesman " . $judul_lap . " <small><br>(regular, export, project)</small>",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
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
        $this->load->view("outstanding", $data);
    }

    public function cekoutstandingseller_ori()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // // "0"   => array("qty" => "0", "nilai" => "0",),
            // // "57"  => array("qty" => "0", "nilai" => "0",),
            // // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            // // "61"  => array("qty" => "44", "nilai" => "99561369",),
            // "65"  => array("qty" => "9", "nilai" => "21598185",),
            // // "65"  => array("qty" => "9", "nilai" => "18523185",),
            // // "69"  => array("qty" => "111936", "nilai" => "10243373229",),
            // // "69"  => array("qty" => "111936", "nilai" => "10457787651",),
            // "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            // "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // // "576" => array("qty" => "132", "nilai" => "449897306.72834",),
            // "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            // // "718" => array("qty" => "15", "nilai" => "119260509",),
            // // "286" => array("qty" => "2041", "nilai" => "0",),
            // // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // // "718" => array("qty" => "0", "nilai" => "0",),
            // // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "1", "nilai" => "4009010",),
            // // "825" => array("qty" => "0", "nilai" => "0",),
            // // "826" => array("qty" => "0", "nilai" => "0",),
            // // "712" => array("qty" => "0", "nilai" => "0",),
            // // "567" => array("qty" => "189", "nilai" => "0",),
            // // // "551" => array("qty" => "0", "nilai" => "0",),
            // // "205" => array("qty" => "28", "nilai" => "11886000",),
            // // "182" => array("qty" => "-2304", "nilai" => "2252939845",),
            // // "77"  => array("qty" => "-2", "nilai" => "-2342344",),
            // // "551"  => array("qty" => "8615", "nilai" => "5637500091",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        /* ---------------------------------------------------------------------
        * filter khusus
        * ---------------------------------------------------------------------*/
        // arrPrint($_GET);
        if (isset($_GET['ky'])) {
            $get_condites = array(
                $_GET['ky'] => $_GET[$_GET['ky']]
            );
            if (method_exists($dt, "getSellerId")) {

                // $dt->setSellerId('808');
            }
        }
        // cekHere(ipadd());
        if (ipadd() == "202.65.117.72") {
            // $dt->setSellerId('65');
            // $dt->setSellerId('73');
            // ------------------
            // $dt->setSellerId('61');
            // $dt->setSellerId('69');
        }
        // $dt->setSellerId('844');
        // $dt->setSellerId('65');
        // $dt->setSellerId('69');
        // $dt->setMasterId('100786');

        // $src_00 = $dt->getSaldoSeller($date1, $date2);
        $src_000 = $dt->callPerSeller($date1, $date2);
        // $src_000 = $dt->getSaldoSellerTahun($date1, $date2);
        /**/
        // $src_mtd = $src_00['mtd'];
        // $src_yang_lalu = $src_00['ytd_previous'];
        // $arrRekenings = $src_00['rekening'];
        // $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // $src_koloms = $src_00['kolom'];
        // // cekBiru(sizeof($src_ytd));
        // // arrPrint($src_ytd);
        // // arrPrint($src_mtd);
        // // arrPrint($src_00);
        // // matiHere(__LINE__ . __FILE__);
        //
        // /* ------------------------------------------------------------------------------------------
        //  * saat ini
        //  * ------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        // $arrSubjek = array();
        // foreach ($src_mtd as $item) {
        //
        //     $seller_id = $item['seller_id'];
        //     $subjek_id = $item['seller_id'];
        //     // $subjek_id = $item['master_id'];
        //     // $qty_debet = $item['qty_debet_reguler'];
        //
        //     $debet = $item['debet'];
        //     $kredit = $item['kredit'];
        //
        //     $rekening = $item['rekening'];
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //     foreach ($src_koloms as $src_kolom) {
        //         $$src_kolom = $item[$src_kolom];
        //
        //         $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
        //     }
        //     // $sumSubjek[$subjek_id]['now_debet_' . $rekening] = $debet;
        //     // //
        //     // // // ---------------------------------------------------------
        //     // // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
        //     // //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
        //     // // }
        //     // $sumSubjek[$subjek_id]['now_kredit_' . $rekening] = $kredit;
        //
        //
        //     //---------------------------------------------------------
        //     $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
        //     $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
        //     // $arrRekenings[$rekening] = $rekening;
        //     $arrSeller[$seller_id] = $item;
        // }
        //
        // /* ------------------------------------------------------------------------------------------
        //  * yang lalu dengan prefik prev
        //  * ------------------------------------------------------------------------------------------*/
        // // arrPrintPink($src_yang_lalu);
        // // $sumSubjek = array();
        // foreach ($src_yang_lalu as $item) {
        //
        //     $seller_id = $item['seller_id'];
        //     $subjek_id = $item['seller_id'];
        //     // $subjek_id = $item['master_id'];
        //     // $qty_debet = $item['qty_debet_reguler'];
        //
        //     $debet = $item['debet'];
        //     $kredit = $item['kredit'];
        //
        //     $rekening = $item['rekening'];
        //     foreach ($src_koloms as $src_kolom) {
        //         if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
        //             $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
        //         }
        //         $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
        //     }
        //     // if (!isset($sumSubjek[$subjek_id]['prev_debet_' . $rekening])) {
        //     //     $sumSubjek[$subjek_id]['prev_debet_' . $rekening] = 0;
        //     // }
        //     // $sumSubjek[$subjek_id]['prev_debet_' . $rekening] += $debet;
        //     //
        //     // // ---------------------------------------------------------
        //     // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
        //     //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
        //     // }
        //     // $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] += $kredit;
        //
        //
        //     //---------------------------------------------------------
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //     $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
        //     $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
        //     // $arrRekenings[$rekening] = $rekening;
        //     $arrSeller[$seller_id] = $item;
        // }
        // // arrPrint($sumSubjek);
        // // test_table($sumSubjek);
        // // matiHere(__LINE__);
        //
        // /* ----------------------------------------------------------------------------------------------------------
        //  * Rumus san order netto order - kirim - return_kirim
        //  * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
        //  * ----------------------------------------------------------------------------------------------------------*/
        // // $sumSubjek = array();
        // $qty_kirim = 0;
        // foreach ($sumSubjek as $sbj_id => $sbjDatas) {
        //     $rekening = $sbjDatas['rekening'];
        //     $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
        //     $now_saldo_order_588so = isset($sbjDatas['now_saldo_order_588so']) ? $sbjDatas['now_saldo_order_588so'] : "0";
        //     $now_saldo_order_382so = isset($sbjDatas['now_saldo_order_382so']) ? $sbjDatas['now_saldo_order_382so'] : "0";
        //     $now_saldo_order_all = $now_saldo_order_582so + $now_saldo_order_588so + $now_saldo_order_382so;
        //
        //     $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
        //     $now_saldo_reject_7499 = isset($sbjDatas['now_saldo_reject_7499']) ? $sbjDatas['now_saldo_reject_7499'] : "0";
        //     $now_saldo_reject_382spd = isset($sbjDatas['now_saldo_reject_382spd']) ? $sbjDatas['now_saldo_reject_382spd'] : "0";
        //     $now_saldo_reject_all = $now_saldo_reject_582spd + $now_saldo_reject_7499 + $now_saldo_reject_382spd;
        //
        //     $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
        //     $now_saldo_closed_7499 = isset($sbjDatas['now_saldo_closed_7499']) ? $sbjDatas['now_saldo_closed_7499'] : "0";
        //     $now_saldo_closed_382spd = isset($sbjDatas['now_saldo_closed_382spd']) ? $sbjDatas['now_saldo_closed_382spd'] : "0";
        //     $now_saldo_closed_all = $now_saldo_closed_582spd + $now_saldo_closed_7499 + $now_saldo_closed_382spd;
        //
        //     $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
        //     $now_kredit_7499 = isset($sbjDatas['now_kredit_7499']) ? $sbjDatas['now_kredit_7499'] : "0";
        //     $now_kredit_382spd = isset($sbjDatas['now_kredit_382spd']) ? $sbjDatas['now_kredit_382spd'] : "0";
        //     $now_kredit_all = $now_kredit_582spd + $now_kredit_7499 + $now_kredit_382spd;
        //
        //     $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
        //     $now_saldo_kirim_7499 = isset($sbjDatas['now_saldo_kirim_7499']) ? $sbjDatas['now_saldo_kirim_7499'] : "0";
        //     $now_saldo_kirim_382spd = isset($sbjDatas['now_saldo_kirim_382spd']) ? $sbjDatas['now_saldo_kirim_382spd'] : "0";
        //     $now_saldo_kirim_all = $now_saldo_kirim_582spd + $now_saldo_kirim_7499 + $now_saldo_kirim_382spd;
        //
        //     $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
        //     $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
        //
        //     $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";
        //
        //     $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";
        //     $prev_kredit_7499 = isset($sbjDatas['prev_kredit_7499']) ? $sbjDatas['prev_kredit_7499'] : "0";
        //     $prev_kredit_382spd = isset($sbjDatas['prev_kredit_382spd']) ? $sbjDatas['prev_kredit_382spd'] : "0";
        //     $prev_kredit_all = $prev_kredit_582spd + $prev_kredit_7499 + $prev_kredit_382spd;
        //     $now_saldo_order_netto_all = $now_saldo_order_all - $now_saldo_reject_all - $now_saldo_closed_all;
        //     // $now_saldo_order_netto_all = $now_saldo_order_all;
        //
        //     $sumSubjek[$sbj_id]["prev_kredit_all"] = $prev_kredit_all;
        //     $sumSubjek[$sbj_id]["now_saldo_order_all"] = $now_saldo_order_all;
        //     $sumSubjek[$sbj_id]["now_saldo_kirim_all"] = $now_saldo_kirim_all;
        //     $sumSubjek[$sbj_id]["now_kredit_all"] = $now_kredit_all;
        //     $sumSubjek[$sbj_id]["now_saldo_reject_all"] = $now_saldo_reject_all;
        //     $sumSubjek[$sbj_id]["now_saldo_closed_all"] = $now_saldo_closed_all;
        //     $sumSubjek[$sbj_id]["now_saldo_order_netto_all"] = $now_saldo_order_netto_all;
        //     /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
        //     if ($now_saldo_order_582so > 0) {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
        //     }
        //     else {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
        //     }
        //     if ($now_saldo_order_all > 0) {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = $now_saldo_kirim_all;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = $now_kredit_all;
        //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = 0;
        //     }
        //     else {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = $now_saldo_kirim_all;
        //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = $now_kredit_all;
        //     }
        //     // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
        //     // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
        //     $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
        //     $last_kredit_582spd = 0;
        //     if ($now_kredit_582spd > 0) {
        //         $last_kredit_582spd = ($now_kredit_582spd * 1) . "***";
        //     }
        //     else {
        //         //     //$sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"]
        //         // cekHijau("$now_saldo_order_582so");
        //         $last_kredit_582spd = ($prev_kredit_582spd + $now_saldo_order_582so - $now_saldo_reject_582spd - $now_saldo_closed_582spd - $now_saldo_kirim_582spd);
        //     }
        //     $last_kredit_7499 = $prev_kredit_7499 + $now_kredit_7499;
        //     $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;
        //     $sumSubjek[$sbj_id]["last_kredit_allspd"] = $last_kredit_7499 + $last_kredit_582spd;
        //     $sumSubjek[$sbj_id]["last_kredit_all"] = $prev_kredit_all + $now_saldo_order_all - $now_saldo_closed_582spd - $now_saldo_reject_582spd - $now_saldo_kirim_all;
        // }
        //
        // // arrPrintWebs($sumSubjekSeller2);
        // // arrPrintWebs($sumSubjekSeller);
        // // arrPrintPink($sumSubjek);
        // // test_table($sumSubjek);
        // // arrPrintPink($sumSubjek);
        // // arrPrintHijau($arrSubjek);
        // //   matiHere(__LINE__);
        // // /* --------------------------------------------------------------------------------------------------
        // //   * #3 pengumpulan data menjadi data siap tempur
        // //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($arrSubjek as $subj_id => $itemParam) {
        //     $sumParams = $sumSubjek[$subj_id];
        //
        //
        //     // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
        //     $hasilOlahan_1[] = $itemParam + $sumParams;
        //     // $hasilOlahan[$customer_id] = $itemParam;
        // }
        //
        // $masterData = $hasilOlahan_1;
        /**/
        $masterData = $src_000;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            // "master_id"            => array(
            //     "label" => "mid",
            // ),
            "seller_id"                  => array(
                "label"   => "sid",
                "rowspan" => "2",
            ),
            "seller_nama"                => array(
                "label"   => "salesman",
                "rowspan" => "2",
                // "links"   => array(
                //     // "target"  => "laporan/Crm/cekoutstandingraw",
                //     "target"  => "laporan/Crm/cekoutstandingtransaksi",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
                // "collapsible" => array(
                //     "layout" => false,
                //     "key"    => "seller_id",
                //     "target" => "laporan/Crm/cekoutstandingtransaksi",
                // ),
                "popup"   => array(
                    "key"    => "seller_id",
                    // "target" => "laporan/Outstanding/perindekshow/cekoutstandingtransaksi",
                    "target" => "laporan/Outstanding/viewemyoutstanding",
                ),
            ),
            // "customer_nama"        => array(
            //     "label" => "customer",
            //     "links" => array(
            //         // "target"  => "laporan/Crm/cekoutstandingraw",
            //         "target"  => "laporan/Crm/cekoutstandingtransaksi",
            //         "title"   => "Transaksi per salesman",
            //         "key"     => "seller_id",
            //         "heading" => "seller_nama",
            //     ),
            //     // "collapsible" => array(
            //     //     "layout" => false,
            //     //     "key"    => "seller_id",
            //     //     "target" => "laporan/Crm/cekoutstandingtransaksi",
            //     // ),
            // ),
            /*prevous outstanding*/
            // "prev_qty_order" => array(
            //     "label"      => "previous order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_saldo_order_582so" => array(
            //     "label"      => "previous order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "prev_kredit_all"            => array(
                "label"      => "previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            // "prev_kredit_582spd"     => array(
            //     "label"      => "previous outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kredit_7499"     => array(
            //     "label"      => "previous outstanding proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*all*/
            "now_saldo_order_all"        => array(
                "label"      => "new order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*project*/
            // "now_saldo_order_588so"  => array(
            //     "label"      => "new order proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order rincian*/
            // "qty_debet_reguler_582so"      => array(
            //     "label"      => "new order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_582so"          => array(
            //     "label"      => "new order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_582so"  => array(
            //     "label"      => "new netto order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            "now_saldo_reject_582spd"    => array(
                "label"      => "new reject value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_saldo_closed_582spd"    => array(
                "label"      => "new close value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_batal_nilai_9912"       => array(
                "label"      => "cancel value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            "now_saldo_order_return_all" => array(
                "label"      => "return value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            "now_saldo_order_netto_all"  => array(
                "label"      => "new order netto value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_582spd"         => array(
            //     "label"      => "packing list local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_batal"         => array(
            //     "label"      => "qty_kirim_batal",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            // "qty_kirim_netto"      => array(
            //     "label"      => "new netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_all_new"     => array(
            //     "label"      => "new netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_all_old"     => array(
            //     "label"      => "last netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_kirim_all"        => array(
                "label"      => "packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_saldo_batal_kirim_all"  => array(
                "label"      => "cancel value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            "now_saldo_return_kirim_all" => array(
                "label"      => "return value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            "now_saldo_kirim_netto_all"  => array(
                "label"      => "netto packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            // "now_saldo_kirim_582spd" => array(
            //     "label"      => "new netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*outstanding*/
            // "qty_outstanding"      => array(
            //     "label"      => "new outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_all"          => array(
            //     "label"      => "new outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            // "now_kredit_582spd"      => array(
            //     "label"      => "new outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*kirim old*/
            // "qty_kirim_netto_old"  => array(
            //     "label"      => "netto pl qty previous",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto_old"        => array(
            //     "label"      => "netto pl value previous",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_netto_all"  => array(
            //     "label"      => "all netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto_all"        => array(
            //     "label"      => "all netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*outstanding project*/
            // "now_kredit_7499"      => array(
            //     "label"      => "new outstanding proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*last outstanding*/
            // "ytd_qty_outstanding"  => array(
            //     "label"      => "last outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "last_kredit_582spd"      => array(
            //     "label"      => "outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "last_kredit_allspd"      => array(
            //     "label"      => "all outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            "last_kredit_all"            => array(
                "label"      => "all outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            // "seller_id"      => array(
            //     "label"      => "sID",
            // ),
        );
        // arrPrintKuning($sumSubjek);
        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('Y-m');
        $month_req = formatTanggal($get_date1, 'Y-m');
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
            // "title"       => "Laporan Sales Outstanding " . $judul_lap, // TIDAK COCOK diganti tg 12/12/2022
            "title"       => "Laporan Outstanding By Salesman " . $judul_lap . " <small><br>(regular, export, project)</small>",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
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
        $this->load->view("outstanding", $data);
    }

    public function cekoutstandingsellertahun()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // // "0"   => array("qty" => "0", "nilai" => "0",),
            // // "57"  => array("qty" => "0", "nilai" => "0",),
            // // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            // // "61"  => array("qty" => "44", "nilai" => "99561369",),
            // "65"  => array("qty" => "9", "nilai" => "21598185",),
            // // "65"  => array("qty" => "9", "nilai" => "18523185",),
            // // "69"  => array("qty" => "111936", "nilai" => "10243373229",),
            // // "69"  => array("qty" => "111936", "nilai" => "10457787651",),
            // "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            // "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // // "576" => array("qty" => "132", "nilai" => "449897306.72834",),
            // "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            // // "718" => array("qty" => "15", "nilai" => "119260509",),
            // // "286" => array("qty" => "2041", "nilai" => "0",),
            // // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // // "718" => array("qty" => "0", "nilai" => "0",),
            // // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "1", "nilai" => "4009010",),
            // // "825" => array("qty" => "0", "nilai" => "0",),
            // // "826" => array("qty" => "0", "nilai" => "0",),
            // // "712" => array("qty" => "0", "nilai" => "0",),
            // // "567" => array("qty" => "189", "nilai" => "0",),
            // // // "551" => array("qty" => "0", "nilai" => "0",),
            // // "205" => array("qty" => "28", "nilai" => "11886000",),
            // // "182" => array("qty" => "-2304", "nilai" => "2252939845",),
            // // "77"  => array("qty" => "-2", "nilai" => "-2342344",),
            // // "551"  => array("qty" => "8615", "nilai" => "5637500091",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        /* ---------------------------------------------------------------------
        * filter khusus
        * ---------------------------------------------------------------------*/
        // arrPrint($_GET);
        if (isset($_GET['ky'])) {
            $get_condites = array(
                $_GET['ky'] => $_GET[$_GET['ky']]
            );
            if (method_exists($dt, "getSellerId")) {

                // $dt->setSellerId('808');
            }
        }
        // cekHere(ipadd());
        if (ipadd() == "202.65.117.72") {
            // $dt->setSellerId('65');
            // $dt->setSellerId('73');
            // ------------------
            // $dt->setSellerId('576');
            // $dt->setSellerId('61');
            // $dt->setSellerId('69');
            // $dt->setSellerId('719');
        }
        // $dt->setSellerId('844');
        // $dt->setSellerId('65');
        // $dt->setSellerId('69');
        // $dt->setMasterId('100786');

        // $src_00 = $dt->getSaldoSeller($date1, $date2);
        // $src_000 = $dt->callPerSeller($date1, $date2);
        $src_000 = $dt->callPerSellerTahun($date1, $date2);
        /**/
        // $src_mtd = $src_00['mtd'];
        // $src_yang_lalu = $src_00['ytd_previous'];
        // $arrRekenings = $src_00['rekening'];
        // $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // $src_koloms = $src_00['kolom'];
        // // cekBiru(sizeof($src_ytd));
        // // arrPrint($src_ytd);
        // // arrPrint($src_mtd);
        // arrPrint($src_000);
        // // matiHere(__LINE__ . __FILE__);
        //
        // /* ------------------------------------------------------------------------------------------
        //  * saat ini
        //  * ------------------------------------------------------------------------------------------*/
        // $sumSubjek = array();
        // $arrSubjek = array();
        // foreach ($src_mtd as $item) {
        //
        //     $seller_id = $item['seller_id'];
        //     $subjek_id = $item['seller_id'];
        //     // $subjek_id = $item['master_id'];
        //     // $qty_debet = $item['qty_debet_reguler'];
        //
        //     $debet = $item['debet'];
        //     $kredit = $item['kredit'];
        //
        //     $rekening = $item['rekening'];
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //     foreach ($src_koloms as $src_kolom) {
        //         $$src_kolom = $item[$src_kolom];
        //
        //         $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
        //     }
        //     // $sumSubjek[$subjek_id]['now_debet_' . $rekening] = $debet;
        //     // //
        //     // // // ---------------------------------------------------------
        //     // // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
        //     // //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
        //     // // }
        //     // $sumSubjek[$subjek_id]['now_kredit_' . $rekening] = $kredit;
        //
        //
        //     //---------------------------------------------------------
        //     $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
        //     $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
        //     // $arrRekenings[$rekening] = $rekening;
        //     $arrSeller[$seller_id] = $item;
        // }
        //
        // /* ------------------------------------------------------------------------------------------
        //  * yang lalu dengan prefik prev
        //  * ------------------------------------------------------------------------------------------*/
        // // arrPrintPink($src_yang_lalu);
        // // $sumSubjek = array();
        // foreach ($src_yang_lalu as $item) {
        //
        //     $seller_id = $item['seller_id'];
        //     $subjek_id = $item['seller_id'];
        //     // $subjek_id = $item['master_id'];
        //     // $qty_debet = $item['qty_debet_reguler'];
        //
        //     $debet = $item['debet'];
        //     $kredit = $item['kredit'];
        //
        //     $rekening = $item['rekening'];
        //     foreach ($src_koloms as $src_kolom) {
        //         if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
        //             $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
        //         }
        //         $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
        //     }
        //     // if (!isset($sumSubjek[$subjek_id]['prev_debet_' . $rekening])) {
        //     //     $sumSubjek[$subjek_id]['prev_debet_' . $rekening] = 0;
        //     // }
        //     // $sumSubjek[$subjek_id]['prev_debet_' . $rekening] += $debet;
        //     //
        //     // // ---------------------------------------------------------
        //     // if (!isset($sumSubjek[$subjek_id]['prev_kredit_' . $rekening])) {
        //     //     $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] = 0;
        //     // }
        //     // $sumSubjek[$subjek_id]['prev_kredit_' . $rekening] += $kredit;
        //
        //
        //     //---------------------------------------------------------
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //     $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
        //     $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
        //     // $arrRekenings[$rekening] = $rekening;
        //     $arrSeller[$seller_id] = $item;
        // }
        // // arrPrint($sumSubjek);
        // // test_table($sumSubjek);
        // // matiHere(__LINE__);
        //
        // /* ----------------------------------------------------------------------------------------------------------
        //  * Rumus san order netto order - kirim - return_kirim
        //  * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
        //  * ----------------------------------------------------------------------------------------------------------*/
        // // $sumSubjek = array();
        // $qty_kirim = 0;
        // foreach ($sumSubjek as $sbj_id => $sbjDatas) {
        //     $rekening = $sbjDatas['rekening'];
        //     $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
        //     $now_saldo_order_588so = isset($sbjDatas['now_saldo_order_588so']) ? $sbjDatas['now_saldo_order_588so'] : "0";
        //     $now_saldo_order_382so = isset($sbjDatas['now_saldo_order_382so']) ? $sbjDatas['now_saldo_order_382so'] : "0";
        //     $now_saldo_order_all = $now_saldo_order_582so + $now_saldo_order_588so + $now_saldo_order_382so;
        //
        //     $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
        //     $now_saldo_reject_7499 = isset($sbjDatas['now_saldo_reject_7499']) ? $sbjDatas['now_saldo_reject_7499'] : "0";
        //     $now_saldo_reject_382spd = isset($sbjDatas['now_saldo_reject_382spd']) ? $sbjDatas['now_saldo_reject_382spd'] : "0";
        //     $now_saldo_reject_all = $now_saldo_reject_582spd + $now_saldo_reject_7499 + $now_saldo_reject_382spd;
        //
        //     $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
        //     $now_saldo_closed_7499 = isset($sbjDatas['now_saldo_closed_7499']) ? $sbjDatas['now_saldo_closed_7499'] : "0";
        //     $now_saldo_closed_382spd = isset($sbjDatas['now_saldo_closed_382spd']) ? $sbjDatas['now_saldo_closed_382spd'] : "0";
        //     $now_saldo_closed_all = $now_saldo_closed_582spd + $now_saldo_closed_7499 + $now_saldo_closed_382spd;
        //
        //     $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
        //     $now_kredit_7499 = isset($sbjDatas['now_kredit_7499']) ? $sbjDatas['now_kredit_7499'] : "0";
        //     $now_kredit_382spd = isset($sbjDatas['now_kredit_382spd']) ? $sbjDatas['now_kredit_382spd'] : "0";
        //     $now_kredit_all = $now_kredit_582spd + $now_kredit_7499 + $now_kredit_382spd;
        //
        //     $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
        //     $now_saldo_kirim_7499 = isset($sbjDatas['now_saldo_kirim_7499']) ? $sbjDatas['now_saldo_kirim_7499'] : "0";
        //     $now_saldo_kirim_382spd = isset($sbjDatas['now_saldo_kirim_382spd']) ? $sbjDatas['now_saldo_kirim_382spd'] : "0";
        //     $now_saldo_kirim_all = $now_saldo_kirim_582spd + $now_saldo_kirim_7499 + $now_saldo_kirim_382spd;
        //
        //     $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
        //     $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
        //
        //     $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";
        //
        //     $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";
        //     $prev_kredit_7499 = isset($sbjDatas['prev_kredit_7499']) ? $sbjDatas['prev_kredit_7499'] : "0";
        //     $prev_kredit_382spd = isset($sbjDatas['prev_kredit_382spd']) ? $sbjDatas['prev_kredit_382spd'] : "0";
        //     $prev_kredit_all = $prev_kredit_582spd + $prev_kredit_7499 + $prev_kredit_382spd;
        //     $now_saldo_order_netto_all = $now_saldo_order_all - $now_saldo_reject_all - $now_saldo_closed_all;
        //     // $now_saldo_order_netto_all = $now_saldo_order_all;
        //
        //     $sumSubjek[$sbj_id]["prev_kredit_all"] = $prev_kredit_all;
        //     $sumSubjek[$sbj_id]["now_saldo_order_all"] = $now_saldo_order_all;
        //     $sumSubjek[$sbj_id]["now_saldo_kirim_all"] = $now_saldo_kirim_all;
        //     $sumSubjek[$sbj_id]["now_kredit_all"] = $now_kredit_all;
        //     $sumSubjek[$sbj_id]["now_saldo_reject_all"] = $now_saldo_reject_all;
        //     $sumSubjek[$sbj_id]["now_saldo_closed_all"] = $now_saldo_closed_all;
        //     $sumSubjek[$sbj_id]["now_saldo_order_netto_all"] = $now_saldo_order_netto_all;
        //     /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
        //     if ($now_saldo_order_582so > 0) {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
        //     }
        //     else {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
        //     }
        //     if ($now_saldo_order_all > 0) {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = $now_saldo_kirim_all;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = $now_kredit_all;
        //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = 0;
        //     }
        //     else {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = $now_saldo_kirim_all;
        //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = $now_kredit_all;
        //     }
        //     // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
        //     // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
        //     $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
        //     $last_kredit_582spd = 0;
        //     if ($now_kredit_582spd > 0) {
        //         $last_kredit_582spd = ($now_kredit_582spd * 1) . "***";
        //     }
        //     else {
        //         //     //$sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"]
        //         // cekHijau("$now_saldo_order_582so");
        //         $last_kredit_582spd = ($prev_kredit_582spd + $now_saldo_order_582so - $now_saldo_reject_582spd - $now_saldo_closed_582spd - $now_saldo_kirim_582spd);
        //     }
        //     $last_kredit_7499 = $prev_kredit_7499 + $now_kredit_7499;
        //     $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;
        //     $sumSubjek[$sbj_id]["last_kredit_allspd"] = $last_kredit_7499 + $last_kredit_582spd;
        //     $sumSubjek[$sbj_id]["last_kredit_all"] = $prev_kredit_all + $now_saldo_order_all - $now_saldo_closed_582spd - $now_saldo_reject_582spd - $now_saldo_kirim_all;
        // }
        //
        // // arrPrintWebs($sumSubjekSeller2);
        // // arrPrintWebs($sumSubjekSeller);
        // // arrPrintPink($sumSubjek);
        // // test_table($sumSubjek);
        // // arrPrintPink($sumSubjek);
        // // arrPrintHijau($arrSubjek);
        // //   matiHere(__LINE__);
        // // /* --------------------------------------------------------------------------------------------------
        // //   * #3 pengumpulan data menjadi data siap tempur
        // //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($arrSubjek as $subj_id => $itemParam) {
        //     $sumParams = $sumSubjek[$subj_id];
        //
        //
        //     // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
        //     $hasilOlahan_1[] = $itemParam + $sumParams;
        //     // $hasilOlahan[$customer_id] = $itemParam;
        // }
        //
        // $masterData = $hasilOlahan_1;
        /**/
        $masterData = $src_000;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            // "master_id"            => array(
            //     "label" => "mid",
            // ),
            "seller_id"                  => array(
                "label"   => "sid",
                "rowspan" => "2",
            ),
            "seller_nama"                => array(
                "label"   => "salesman",
                "rowspan" => "2",
                // "links"   => array(
                //     // "target"  => "laporan/Crm/cekoutstandingraw",
                //     "target"  => "laporan/Crm/cekoutstandingtransaksi",
                //     "title"   => "Transaksi per salesman",
                //     "key"     => "seller_id",
                //     "heading" => "seller_nama",
                // ),
                // "collapsible" => array(
                //     "layout" => false,
                //     "key"    => "seller_id",
                //     "target" => "laporan/Crm/cekoutstandingtransaksi",
                // ),
                "popup"   => array(
                    "key"    => "seller_id",
                    // "target" => "laporan/Outstanding/perindekshow/cekoutstandingtransaksi",
                    "target" => "laporan/Outstanding/viewmyoutstandingthn",
                ),
            ),
            // "customer_nama"        => array(
            //     "label" => "customer",
            //     "links" => array(
            //         // "target"  => "laporan/Crm/cekoutstandingraw",
            //         "target"  => "laporan/Crm/cekoutstandingtransaksi",
            //         "title"   => "Transaksi per salesman",
            //         "key"     => "seller_id",
            //         "heading" => "seller_nama",
            //     ),
            //     // "collapsible" => array(
            //     //     "layout" => false,
            //     //     "key"    => "seller_id",
            //     //     "target" => "laporan/Crm/cekoutstandingtransaksi",
            //     // ),
            // ),
            /*prevous outstanding*/
            // "prev_qty_order" => array(
            //     "label"      => "previous order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_saldo_order_582so" => array(
            //     "label"      => "previous order",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "prev_kredit_all"            => array(
                "label"      => "previous outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            // "prev_kredit_582spd"     => array(
            //     "label"      => "previous outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kredit_7499"     => array(
            //     "label"      => "previous outstanding proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*all*/
            "now_saldo_order_all"        => array(
                "label"      => "new order value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            /*project*/
            // "now_saldo_order_588so"  => array(
            //     "label"      => "new order proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order rincian*/
            // "qty_debet_reguler_582so"      => array(
            //     "label"      => "new order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_582so"          => array(
            //     "label"      => "new order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_582so"  => array(
            //     "label"      => "new netto order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            "now_saldo_reject_582spd"    => array(
                "label"      => "new reject value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_saldo_closed_582spd"    => array(
                "label"      => "new close value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_batal_nilai_9912"       => array(
                "label"      => "cancel value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            "now_saldo_order_return_all" => array(
                "label"      => "return value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            "now_saldo_order_netto_all"  => array(
                "label"      => "new order netto value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),

            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_582spd"         => array(
            //     "label"      => "packing list local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_batal"         => array(
            //     "label"      => "qty_kirim_batal",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            // "qty_kirim_netto"      => array(
            //     "label"      => "new netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_all_new"     => array(
            //     "label"      => "new netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_all_old"     => array(
            //     "label"      => "last netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_kirim_all"        => array(
                // "label"      => "netto packing list value",
                "label"      => "packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),

            "now_batal_nilai_9912" => array(
                "label"      => "batal value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),
            "now_saldo_kirim_982"  => array(
                "label"      => "return value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),

            "now_saldo_kirim_all_netto" => array(
                "label"      => "netto packing list value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
            ),

            // "now_saldo_kirim_582spd" => array(
            //     "label"      => "new netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*outstanding*/
            // "qty_outstanding"      => array(
            //     "label"      => "new outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_all"          => array(
            //     "label"      => "new outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            // "now_kredit_582spd"      => array(
            //     "label"      => "new outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*kirim old*/
            // "qty_kirim_netto_old"  => array(
            //     "label"      => "netto pl qty previous",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto_old"        => array(
            //     "label"      => "netto pl value previous",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_netto_all"  => array(
            //     "label"      => "all netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto_all"        => array(
            //     "label"      => "all netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*outstanding project*/
            // "now_kredit_7499"      => array(
            //     "label"      => "new outstanding proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*last outstanding*/
            // "ytd_qty_outstanding"  => array(
            //     "label"      => "last outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "last_kredit_582spd"      => array(
            //     "label"      => "outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "last_kredit_allspd"      => array(
            //     "label"      => "all outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            "last_kredit_all"           => array(
                "label"      => "all outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
            ),
            // "seller_id"      => array(
            //     "label"      => "sID",
            // ),
        );
        // arrPrintKuning($sumSubjek);
        $gr = isset($_GET['gr']) ? "&gr=" . $_GET['gr'] : "";
        $strget = $_GET;
        // arrPrintHijau($strget);
        $strGet = "?1=1";
        foreach ($strget as $kget => $vget) {
            $strGet .= "&$kget=$vget";
        }
        $month_now = dtimeNow('Y');
        $month_req = formatTanggal($get_date1, 'Y');
        // cekHere("$month_req $month_now");
        $periode_str = "";
        if ($month_now == $month_req) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i:s');
        }
        elseif ($date1 == dtimeNow('Y-01-01') && $date2 == dtimeNow('Y-m-d')) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
        }
        else {
            // $judul_lap = " " . formatTanggal($get_date1, 'd F Y') . " - " . formatTanggal($get_date2, 'd F Y');
            $judul_lap = "tahun " . formatTanggal($get_date1, 'Y');
            $periode_str = "Periode 01 Januari s/d 31 Desember " . formatTanggal($get_date1, 'Y');
        }

        // cekMerah(base_url(uri_string()) . "$strGet");
        // $strGr = isset($_GET['date1']) ? ""
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : "";
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : "";
        $data = array(
            "mode"        => "langsung_indek",
            // "title"       => "Laporan Sales Outstanding " . $judul_lap, // TIDAK COCOK diganti tg 12/12/2022
            "title"       => "Laporan Outstanding By Salesman " . $judul_lap . " <small><br>(regular, export, project) $periode_str</small>",
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
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
        $this->load->view("outstanding", $data);
    }

    /*dengan project */
    public function cekoutstandingtransaksi_oldd()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // "113414" => array("qty" => "2", "nilai" => "0",),
            // "0"   => array("qty" => "0", "nilai" => "0",),
            // "57"  => array("qty" => "0", "nilai" => "0",),
            // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            // "65"  => array("qty" => "9", "nilai" => "21622194",),
            // "69"  => array("qty" => "111125", "nilai" => "9940808792",),
            // "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            // "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            // "718" => array("qty" => "15", "nilai" => "119260509",),
            // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // "718" => array("qty" => "0", "nilai" => "0",),
            // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "0", "nilai" => "0",),
            // "825" => array("qty" => "0", "nilai" => "0",),
            // "826" => array("qty" => "0", "nilai" => "0",),
            // "712" => array("qty" => "0", "nilai" => "0",),
            // "567" => array("qty" => "189", "nilai" => "0",),
            // // "551" => array("qty" => "0", "nilai" => "0",),
            // "205" => array("qty" => "28", "nilai" => "11886000",),
            // "182" => array("qty" => "-2304", "nilai" => "-2252939845",),
            // "127003" => array("qty" => "165", "nilai" => "400000000",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        /* -----------------------------------------------------------------
         * filter khusus buat seller, berlaku diluar pusat
         * -----------------------------------------------------------------*/
        if (my_cabang_id() > 0) {
            // $date1 = "2021-01-01";
            $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "2021-01-01";
            $dt->setSellerId(my_id());
            // $dt->setMasterId("153293");
            // $dt->setMasterId("153291");
            // $dt->setMasterId("113488");
        }
        else {
            if (isset($_GET['ky'])) {
                switch ($_GET['ky']) {
                    case "seller_id":
                        $dt->setSellerId($_GET[$_GET['ky']]);
                        break;
                    case "master_id":
                        $dt->setMasterId($_GET[$_GET['ky']]);
                        break;
                }
            }
        }

        // $dt->setSellerId('65');
        // $dt->setSellerId('77');
        // $dt->setMasterId("113488");
        // $dt->setMasterId("127359");
        // $dt->setMasterId("126305");
        // $dt->setMasterId("123515");
        // $dt->setMasterId("91060 ");
        // $dt->setMasterId("100786 ");

        // $src_00 = $dt->getSaldoSellerTransaksi($date1, $date2);
        // //        arrPrintKuning($src_00);
        // //        showLast_query("kuning");
        // $src_mtd = $src_00['mtd'];
        // $src_yang_lalu = $src_00['ytd_previous'];
        // $src_ytd = $src_00['ytd'];
        // $src_ytd_pluss = $src_00['ytd_pluss'];
        // $arrRekenings = $src_00['rekening'];
        // $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // $src_tr = $src_00['transaksi'];
        // $src_koloms = $src_00['kolom'];
        // // cekBiru(sizeof($src_ytd));
        // // arrPrint($src_ytd);
        // // arrPrint($src_mtd);
        // // arrPrint($src_yang_lalu);
        // // arrPrintPink($src_ytd);
        // // arrPrint($src_ytd_pluss);
        // foreach ($src_ytd_pluss as $src_ytd_pluss) {
        //     $subjek_id = $src_ytd_pluss['master_id'];
        //     $tr_datas[$subjek_id] = $src_ytd_pluss;
        // }
        // // matiDisini(__LINE__);
        // /* ------------------------------------------------------------------------------------------
        //  * saat ini
        //  * ------------------------------------------------------------------------------------------*/
        // $arrSubjek = array();
        // $sumSubjek = array();
        // foreach ($src_mtd as $item) {
        //
        //     $subjek_id = $item['master_id'];
        //     $transaksi_id = $item['transaksi_id'];
        //
        //     $seller_id = $item['seller_id'];
        //     $seller_nama = $item['seller_nama'];
        //     $rekening = $item['rekening'];
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //     foreach ($src_koloms as $src_kolom) {
        //         $$src_kolom = $item[$src_kolom];
        //
        //         $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
        //     }
        //     // cekBiru("$rekening");
        //
        //     //---------------------------------------------------------
        //     // $arrSubjek[$subjek_id]['seller_id'] = $seller_id;
        //     // $arrSubjek[$subjek_id]['seller_nama'] = $seller_nama;
        //     $arrSubjek[$subjek_id] = $item;
        //     // $arrRekenings[$rekening] = $rekening;
        //     $arrDatas[$subjek_id] = $item;
        // }
        //
        // // arrPrint($sumSubjek);
        // // matiHere(__LINE__);
        //
        // /* ------------------------------------------------------------------------------------------
        //  * yang lalu dengan prefik prev
        //  * ------------------------------------------------------------------------------------------*/
        // // arrPrintPink($src_yang_lalu);
        // // $sumSubjek = array();
        // $rekening_subjek_2 = array(
        //     "582spd", "7499"
        // );
        // $sumSubjek2 = array();
        // foreach ($src_yang_lalu as $item) {
        //
        //     $seller_id = $item['seller_id'];
        //     $subjek_id = $item['master_id'];
        //     // $subjek_id = $item['master_id'];
        //     // $qty_debet = $item['qty_debet_reguler'];
        //
        //     $debet = $item['debet'];
        //     $kredit = $item['kredit'];
        //
        //     $rekening = $item['rekening'];
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //     foreach ($src_koloms as $src_kolom) {
        //         $nilai = $item[$src_kolom] * 1;
        //
        //         // if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
        //         //     $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
        //         // }
        //         $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $nilai;
        //
        //         $sumSubjek2[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $nilai;
        //     }
        //
        //
        //     //---------------------------------------------------------
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //
        //     // $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
        //     // $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
        //
        //     // $arrSubjek[$subjek_id] = $item;
        //     // if($rekening == "582spd"){
        //     if (in_array($rekening, $rekening_subjek_2)) {
        //         $arrSubjek_2[$subjek_id][] = $item;
        //     }
        //
        //     // $arrRekenings[$rekening] = $rekening;
        //     $arrSeller[$seller_id] = $item;
        // }
        //
        // // cekHijau(sizeof($sumSubjek));
        // // arrPrintHijau($sumSubjek);
        // // arrPrint($arrSubjek_2);
        // // test_table($sumSubjek);
        // // matiHere(__LINE__);
        //
        // /* ----------------------------------------------------------------
        //  * filter untuk membuang prevous outstandinf yg dibawah nilai 1
        //  * ----------------------------------------------------------------*/
        // // arrPrintKuning($sumSubjek2);
        // foreach ($arrSubjek_2 as $mst_id => $item) {
        //     $arrSubjek_3[$mst_id] = end($item);
        // }
        // // arrPrint($arrSubjek_3);
        // foreach ($arrSubjek_3 as $mast_id => $item) {
        //     if ($item['rekening'] == "582spd" && $item["kredit"] >= 1) {
        //         $arrSubjek[$mast_id] = $item;
        //     }
        //     if ($item['rekening'] == "7499" && $item["kredit"] >= 1) {
        //         $arrSubjek[$mast_id] = $item;
        //     }
        // }
        // // $arrSubjek = $arrSubjek_2;
        //
        // /* ----------------------------------------------------------------------------------------------------------
        //  * Rumus san order netto order - kirim - return_kirim
        //  * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
        //  * ----------------------------------------------------------------------------------------------------------*/
        // // $sumSubjek = array();
        // $qty_kirim = 0;
        // foreach ($sumSubjek as $sbj_id => $sbjDatas) {
        //     $rekening = $sbjDatas['rekening'];
        //     $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
        //     $now_saldo_order_588so = isset($sbjDatas['now_saldo_order_588so']) ? $sbjDatas['now_saldo_order_588so'] : "0";
        //     $now_saldo_order_382so = isset($sbjDatas['now_saldo_order_382so']) ? $sbjDatas['now_saldo_order_382so'] : "0";
        //     $now_saldo_order_all = $now_saldo_order_582so + $now_saldo_order_588so + $now_saldo_order_382so;
        //
        //     $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
        //     $now_kredit_7499 = isset($sbjDatas['now_kredit_7499']) ? $sbjDatas['now_kredit_7499'] : "0";
        //     $now_kredit_382spd = isset($sbjDatas['now_kredit_382spd']) ? $sbjDatas['now_kredit_382spd'] : "0";
        //     $now_kredit_all = $now_kredit_582spd + $now_kredit_7499 + $now_kredit_382spd;
        //
        //     $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
        //     $now_saldo_kirim_7499 = isset($sbjDatas['now_saldo_kirim_7499']) ? $sbjDatas['now_saldo_kirim_7499'] : "0";
        //     $now_saldo_kirim_382spd = isset($sbjDatas['now_saldo_kirim_382spd']) ? $sbjDatas['now_saldo_kirim_382spd'] : "0";
        //     $now_saldo_kirim_all = $now_saldo_kirim_582spd + $now_saldo_kirim_7499 + $now_saldo_kirim_382spd;
        //
        //     $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
        //     $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
        //
        //     $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";
        //
        //     $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";
        //     $prev_kredit_7499 = isset($sbjDatas['prev_kredit_7499']) ? $sbjDatas['prev_kredit_7499'] : "0";
        //     $prev_kredit_382spd = isset($sbjDatas['prev_kredit_382spd']) ? $sbjDatas['prev_kredit_382spd'] : "0";
        //     $prev_kredit_all = $prev_kredit_582spd + $prev_kredit_7499 + $prev_kredit_382spd;
        //
        //     $sumSubjek[$sbj_id]["prev_kredit_all"] = $prev_kredit_all;
        //     $sumSubjek[$sbj_id]["now_saldo_order_all"] = $now_saldo_order_all;
        //     $sumSubjek[$sbj_id]["now_saldo_kirim_all"] = $now_saldo_kirim_all;
        //     $sumSubjek[$sbj_id]["now_kredit_all"] = $now_kredit_all;
        //     /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
        //     if ($now_saldo_order_582so > 0) {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
        //     }
        //     else {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
        //     }
        //     if ($now_saldo_order_all > 0) {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = $now_saldo_kirim_all;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = $now_kredit_all;
        //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = 0;
        //     }
        //     else {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = $now_saldo_kirim_all;
        //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = $prev_kredit_all - $now_saldo_kirim_all;
        //     }
        //     // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
        //     // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
        //     $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
        //     $last_kredit_582spd = 0;
        //     // if ($now_kredit_582spd > 0) {
        //     //     $last_kredit_582spd = ($now_kredit_582spd * 1);
        //     // }
        //     // else {
        //     // $last_kredit_582spd = $prev_kredit_582spd - $now_saldo_kirim_582spd_old - 0 + $now_kredit_582spd;
        //     $last_kredit_582spd = (($prev_kredit_582spd + $now_saldo_order_582so) - $now_saldo_reject_582spd - $now_saldo_kirim_582spd - $now_saldo_closed_582spd);
        //     // }
        //
        //     // if($last_kredit_582spd > 0){
        //
        //     // cekMerah("$sbj_id || $last_kredit_582spd = (($prev_kredit_582spd + $now_saldo_order_582so) - $now_saldo_reject_582spd - $now_saldo_kirim_582spd - $now_saldo_closed_582spd);");
        //     // }
        //     $last_kredit_7499 = $prev_kredit_all + $now_saldo_order_all - $now_saldo_reject_582spd - $now_saldo_closed_582spd - $now_saldo_kirim_all;
        //     $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;
        //     $sumSubjek[$sbj_id]["last_kredit_all"] = $last_kredit_7499;
        //
        // }
        //
        // // arrPrintWebs($sumSubjekSeller2);
        // // arrPrintWebs($sumSubjekSeller);
        // // arrPrintPink($sumSubjek);
        // // test_table($sumSubjek);
        // // arrPrintPink($sumSubjek);
        // // arrPrintHijau($arrSubjek);
        // //   matiHere(__LINE__);
        //
        // // /* --------------------------------------------------------------------------------------------------
        // //   * #3 pengumpulan data menjadi data siap tempur
        // //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($arrSubjek as $subj_id => $itemParam) {
        //     $sumParams = $sumSubjek[$subj_id];
        //     $transParams = $tr_datas[$subj_id];
        //
        //     // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
        //     $hasilOlahan_1[] = $itemParam + $sumParams + $transParams;
        //     // $hasilOlahan_1[] = $itemParam;
        // }
        // // arrPrintKuning($hasilOlahan_1);
        // $masterData = $hasilOlahan_1;
        $src_000 = $dt->callPerTransaksi($date1, $date2);
        $masterData = $src_000;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $seller_nama = $masterData[0]['now_seller_nama'];

        $arrHeaders = array(
            "master_id"               => array(
                "label"   => "mid",
                "rowspan" => "2",
                "topth"   => true,
            ),
            // "seller_id"            => array(
            //     "label"   => "sid",
            //     "rowspan" => "2",
            // ),
            // "seller_nama"          => array(
            //     "label"   => "salesman",
            //     "rowspan" => "2",
            //     "links"   => array(
            //         // "target"  => "laporan/Crm/cekoutstandingraw",
            //         "target"  => "laporan/Crm/cekoutstandingtransaksi",
            //         "title"   => "Transaksi per salesman",
            //         "key"     => "seller_id",
            //         "heading" => "seller_nama",
            //     ),
            //     // "collapsible" => array(
            //     //     "layout" => false,
            //     //     "key"    => "seller_id",
            //     //     "target" => "laporan/Crm/cekoutstandingtransaksi",
            //     // ),
            // ),
            "customer_nama"           => array(
                "label"   => "customer",
                "rowspan" => "2",
                "topth"   => true,
            ),
            "now_dtime"               => array(
                "label"      => "tanggal",
                "rowspan"    => "2",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
                "topth"      => true,
            ),
            "extern_nama"             => array(
                "label"      => "nomer so",
                "rowspan"    => "2",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
                "topth"      => true,
            ),
            /*---yg urut pakai ini ya gais--*/
            // "_company_cabangID_modul_subModul_jenisTr_stepCode_olehID" => array(
            //     "label"   => "counter oleh",
            //     "rowspan" => "2",
            //     "attr"    => "class='text-right'",
            // ),
            // "_company_cabangID_modul_subModul_jenisTr_stepCode_sellerID" => array(
            //     "label"   => "counter",
            //     "rowspan" => "2",
            //     "attr"    => "class='text-right'",
            // ),

            /*previous rinci*/
            // "prev_qty_order"       => array(
            //     "label"      => "previous order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_saldo_debet_582so"                                     => array(
            //     "label"      => "order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_qty_kirim"       => array(
            //     "label"      => "previous kirim qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kirim"           => array(
            //     "label"      => "previous kirim value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*prevous outstanding*/
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "summary"    => true,
            // ),
            "prev_kredit_all"         => array(
                "label"      => "outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "previous",
            ),
            // "prev_kredit_582spd"         => array(
            //     "label"      => "outstanding loc. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kredit_7499"           => array(
            //     "label"      => "outstanding proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kredit_382spd"         => array(
            //     "label"      => "outstanding exp. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_order_all"     => array(
                "label"      => "value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            // "now_saldo_order_582so"      => array(
            //     "label"      => "local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_588so"      => array(
            //     "label"      => "project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_382so"      => array(
            //     "label"      => "export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*reject-closed*/
            "now_saldo_reject_582spd" => array(
                "label"      => "reject value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            "now_saldo_closed_582spd" => array(
                "label"      => "closed value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            /*RETURNAN*/
            // "qty_debet_982"        => array(
            //     "label"      => "new return qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_582spd_new" => array(
            //     "label"      => "new local",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_582spd_old" => array(
            //     "label"      => "last local",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_7499"       => array(
            //     "label"      => "project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_382so"      => array(
            //     "label"      => "export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_kirim_all_new" => array(
                "label"      => "new value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            "now_saldo_kirim_all_old" => array(
                "label"      => "last value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),

            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            // "now_saldo_kirim_582spd"      => array(
            //     "label"      => "total kirim",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_total"          => array(
            //     "label"      => "new packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_batal"      => array(
            //     "label"      => "new canceled packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_batal"          => array(
            //     "label"      => "new canceled packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_netto"      => array(
            //     "label"      => "new netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto"          => array(
            //     "label"      => "new netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*outstanding*/
            // "qty_outstanding"      => array(
            //     "label"      => "new outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "summary"    => true,
            // ),
            // "now_kredit_582spd_new"      => array(
            //     "label"      => "new value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "netto",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         // "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*kiriman sebelumnya*/
            // "qty_kirim_netto_old"  => array(
            //     "label"      => "netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_582spd_old"      => array(
            //     "label"      => "last value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_7499_new"        => array(
            //     "label"      => "new project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_7499_old"        => array(
            //     "label"      => "last project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_382spd"          => array(
            //     "label"      => "last value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_kredit_all_new"      => array(
                "label"      => "new value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "outstanding"
            ),
            "now_kredit_all_old"      => array(
                "label"      => "last value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "outstanding"
            ),
            /*ytd rinci*/
            // "ytd_qty_order"        => array(
            //     "label"      => "order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_order"            => array(
            //     "label"      => "order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_qty_kirim"        => array(
            //     "label"      => "kirim qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_kirim"            => array(
            //     "label"      => "kirim value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            /*last outstanding*/
            // "ytd_qty_outstanding"  => array(
            //     "label"      => "outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "last_kredit_582spd"         => array(
            //     "label"      => "total value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            "last_kredit_all"         => array(
                "label"      => "total value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
                "top_parent" => "outstanding",
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
            $judul_lap = "MTD " . dtimeNow('d F Y H:i');
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
            "mode"        => "langsung_2",
            "title"       => "Laporan Sales Order ($seller_nama)" . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            // "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // "arrTopHeaders"  => $arrTopHeaders,
            "modal_size"  => true,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "loader_div"  => "sum_dua",
        );
        $this->load->view("outstanding", $data);
    }

    public function cekoutstandingtransaksi()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // "113414" => array("qty" => "2", "nilai" => "0",),
            // "0"   => array("qty" => "0", "nilai" => "0",),
            // "57"  => array("qty" => "0", "nilai" => "0",),
            // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            // "65"  => array("qty" => "9", "nilai" => "21622194",),
            // "69"  => array("qty" => "111125", "nilai" => "9940808792",),
            // "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            // "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            // "718" => array("qty" => "15", "nilai" => "119260509",),
            // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // "718" => array("qty" => "0", "nilai" => "0",),
            // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "0", "nilai" => "0",),
            // "825" => array("qty" => "0", "nilai" => "0",),
            // "826" => array("qty" => "0", "nilai" => "0",),
            // "712" => array("qty" => "0", "nilai" => "0",),
            // "567" => array("qty" => "189", "nilai" => "0",),
            // // "551" => array("qty" => "0", "nilai" => "0",),
            // "205" => array("qty" => "28", "nilai" => "11886000",),
            // "182" => array("qty" => "-2304", "nilai" => "-2252939845",),
            // "127003" => array("qty" => "165", "nilai" => "400000000",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        /* -----------------------------------------------------------------
         * filter khusus buat seller, berlaku diluar pusat
         * -----------------------------------------------------------------*/
        if (my_cabang_id() > 0) {
            // $date1 = "2021-01-01";
            $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "2021-01-01";
            $dt->setSellerId(my_id());
            // $dt->setMasterId("153293");
            // $dt->setMasterId("153291");
            // $dt->setMasterId("113488");
        }
        else {
            if (isset($_GET['ky'])) {
                switch ($_GET['ky']) {
                    case "seller_id":
                        $dt->setSellerId($_GET[$_GET['ky']]);
                        break;
                    case "master_id":
                        $dt->setMasterId($_GET[$_GET['ky']]);
                        break;
                }
            }
        }

        // $dt->setSellerId('65');
        // $dt->setSellerId('77');
        // $dt->setMasterId("113488");
        // $dt->setMasterId("127359");
        // $dt->setMasterId("126305");
        // $dt->setMasterId("123515");
        // $dt->setMasterId("91060 ");
        // $dt->setMasterId("100786 ");

        // $src_00 = $dt->getSaldoSellerTransaksi($date1, $date2);
        // //        arrPrintKuning($src_00);
        // //        showLast_query("kuning");
        // $src_mtd = $src_00['mtd'];
        // $src_yang_lalu = $src_00['ytd_previous'];
        // $src_ytd = $src_00['ytd'];
        // $src_ytd_pluss = $src_00['ytd_pluss'];
        // $arrRekenings = $src_00['rekening'];
        // $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // $src_tr = $src_00['transaksi'];
        // $src_koloms = $src_00['kolom'];
        // // cekBiru(sizeof($src_ytd));
        // // arrPrint($src_ytd);
        // // arrPrint($src_mtd);
        // // arrPrint($src_yang_lalu);
        // // arrPrintPink($src_ytd);
        // // arrPrint($src_ytd_pluss);
        // foreach ($src_ytd_pluss as $src_ytd_pluss) {
        //     $subjek_id = $src_ytd_pluss['master_id'];
        //     $tr_datas[$subjek_id] = $src_ytd_pluss;
        // }
        // // matiDisini(__LINE__);
        // /* ------------------------------------------------------------------------------------------
        //  * saat ini
        //  * ------------------------------------------------------------------------------------------*/
        // $arrSubjek = array();
        // $sumSubjek = array();
        // foreach ($src_mtd as $item) {
        //
        //     $subjek_id = $item['master_id'];
        //     $transaksi_id = $item['transaksi_id'];
        //
        //     $seller_id = $item['seller_id'];
        //     $seller_nama = $item['seller_nama'];
        //     $rekening = $item['rekening'];
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //     foreach ($src_koloms as $src_kolom) {
        //         $$src_kolom = $item[$src_kolom];
        //
        //         $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
        //     }
        //     // cekBiru("$rekening");
        //
        //     //---------------------------------------------------------
        //     // $arrSubjek[$subjek_id]['seller_id'] = $seller_id;
        //     // $arrSubjek[$subjek_id]['seller_nama'] = $seller_nama;
        //     $arrSubjek[$subjek_id] = $item;
        //     // $arrRekenings[$rekening] = $rekening;
        //     $arrDatas[$subjek_id] = $item;
        // }
        //
        // // arrPrint($sumSubjek);
        // // matiHere(__LINE__);
        //
        // /* ------------------------------------------------------------------------------------------
        //  * yang lalu dengan prefik prev
        //  * ------------------------------------------------------------------------------------------*/
        // // arrPrintPink($src_yang_lalu);
        // // $sumSubjek = array();
        // $rekening_subjek_2 = array(
        //     "582spd", "7499"
        // );
        // $sumSubjek2 = array();
        // foreach ($src_yang_lalu as $item) {
        //
        //     $seller_id = $item['seller_id'];
        //     $subjek_id = $item['master_id'];
        //     // $subjek_id = $item['master_id'];
        //     // $qty_debet = $item['qty_debet_reguler'];
        //
        //     $debet = $item['debet'];
        //     $kredit = $item['kredit'];
        //
        //     $rekening = $item['rekening'];
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //     foreach ($src_koloms as $src_kolom) {
        //         $nilai = $item[$src_kolom] * 1;
        //
        //         // if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
        //         //     $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
        //         // }
        //         $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $nilai;
        //
        //         $sumSubjek2[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $nilai;
        //     }
        //
        //
        //     //---------------------------------------------------------
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //
        //     // $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
        //     // $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
        //
        //     // $arrSubjek[$subjek_id] = $item;
        //     // if($rekening == "582spd"){
        //     if (in_array($rekening, $rekening_subjek_2)) {
        //         $arrSubjek_2[$subjek_id][] = $item;
        //     }
        //
        //     // $arrRekenings[$rekening] = $rekening;
        //     $arrSeller[$seller_id] = $item;
        // }
        //
        // // cekHijau(sizeof($sumSubjek));
        // // arrPrintHijau($sumSubjek);
        // // arrPrint($arrSubjek_2);
        // // test_table($sumSubjek);
        // // matiHere(__LINE__);
        //
        // /* ----------------------------------------------------------------
        //  * filter untuk membuang prevous outstandinf yg dibawah nilai 1
        //  * ----------------------------------------------------------------*/
        // // arrPrintKuning($sumSubjek2);
        // foreach ($arrSubjek_2 as $mst_id => $item) {
        //     $arrSubjek_3[$mst_id] = end($item);
        // }
        // // arrPrint($arrSubjek_3);
        // foreach ($arrSubjek_3 as $mast_id => $item) {
        //     if ($item['rekening'] == "582spd" && $item["kredit"] >= 1) {
        //         $arrSubjek[$mast_id] = $item;
        //     }
        //     if ($item['rekening'] == "7499" && $item["kredit"] >= 1) {
        //         $arrSubjek[$mast_id] = $item;
        //     }
        // }
        // // $arrSubjek = $arrSubjek_2;
        //
        // /* ----------------------------------------------------------------------------------------------------------
        //  * Rumus san order netto order - kirim - return_kirim
        //  * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
        //  * ----------------------------------------------------------------------------------------------------------*/
        // // $sumSubjek = array();
        // $qty_kirim = 0;
        // foreach ($sumSubjek as $sbj_id => $sbjDatas) {
        //     $rekening = $sbjDatas['rekening'];
        //     $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
        //     $now_saldo_order_588so = isset($sbjDatas['now_saldo_order_588so']) ? $sbjDatas['now_saldo_order_588so'] : "0";
        //     $now_saldo_order_382so = isset($sbjDatas['now_saldo_order_382so']) ? $sbjDatas['now_saldo_order_382so'] : "0";
        //     $now_saldo_order_all = $now_saldo_order_582so + $now_saldo_order_588so + $now_saldo_order_382so;
        //
        //     $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
        //     $now_kredit_7499 = isset($sbjDatas['now_kredit_7499']) ? $sbjDatas['now_kredit_7499'] : "0";
        //     $now_kredit_382spd = isset($sbjDatas['now_kredit_382spd']) ? $sbjDatas['now_kredit_382spd'] : "0";
        //     $now_kredit_all = $now_kredit_582spd + $now_kredit_7499 + $now_kredit_382spd;
        //
        //     $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
        //     $now_saldo_kirim_7499 = isset($sbjDatas['now_saldo_kirim_7499']) ? $sbjDatas['now_saldo_kirim_7499'] : "0";
        //     $now_saldo_kirim_382spd = isset($sbjDatas['now_saldo_kirim_382spd']) ? $sbjDatas['now_saldo_kirim_382spd'] : "0";
        //     $now_saldo_kirim_all = $now_saldo_kirim_582spd + $now_saldo_kirim_7499 + $now_saldo_kirim_382spd;
        //
        //     $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
        //     $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
        //
        //     $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";
        //
        //     $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";
        //     $prev_kredit_7499 = isset($sbjDatas['prev_kredit_7499']) ? $sbjDatas['prev_kredit_7499'] : "0";
        //     $prev_kredit_382spd = isset($sbjDatas['prev_kredit_382spd']) ? $sbjDatas['prev_kredit_382spd'] : "0";
        //     $prev_kredit_all = $prev_kredit_582spd + $prev_kredit_7499 + $prev_kredit_382spd;
        //
        //     $sumSubjek[$sbj_id]["prev_kredit_all"] = $prev_kredit_all;
        //     $sumSubjek[$sbj_id]["now_saldo_order_all"] = $now_saldo_order_all;
        //     $sumSubjek[$sbj_id]["now_saldo_kirim_all"] = $now_saldo_kirim_all;
        //     $sumSubjek[$sbj_id]["now_kredit_all"] = $now_kredit_all;
        //     /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
        //     if ($now_saldo_order_582so > 0) {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
        //     }
        //     else {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
        //     }
        //     if ($now_saldo_order_all > 0) {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = $now_saldo_kirim_all;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = $now_kredit_all;
        //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = 0;
        //     }
        //     else {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = $now_saldo_kirim_all;
        //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = $prev_kredit_all - $now_saldo_kirim_all;
        //     }
        //     // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
        //     // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
        //     $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
        //     $last_kredit_582spd = 0;
        //     // if ($now_kredit_582spd > 0) {
        //     //     $last_kredit_582spd = ($now_kredit_582spd * 1);
        //     // }
        //     // else {
        //     // $last_kredit_582spd = $prev_kredit_582spd - $now_saldo_kirim_582spd_old - 0 + $now_kredit_582spd;
        //     $last_kredit_582spd = (($prev_kredit_582spd + $now_saldo_order_582so) - $now_saldo_reject_582spd - $now_saldo_kirim_582spd - $now_saldo_closed_582spd);
        //     // }
        //
        //     // if($last_kredit_582spd > 0){
        //
        //     // cekMerah("$sbj_id || $last_kredit_582spd = (($prev_kredit_582spd + $now_saldo_order_582so) - $now_saldo_reject_582spd - $now_saldo_kirim_582spd - $now_saldo_closed_582spd);");
        //     // }
        //     $last_kredit_7499 = $prev_kredit_all + $now_saldo_order_all - $now_saldo_reject_582spd - $now_saldo_closed_582spd - $now_saldo_kirim_all;
        //     $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;
        //     $sumSubjek[$sbj_id]["last_kredit_all"] = $last_kredit_7499;
        //
        // }
        //
        // // arrPrintWebs($sumSubjekSeller2);
        // // arrPrintWebs($sumSubjekSeller);
        // // arrPrintPink($sumSubjek);
        // // test_table($sumSubjek);
        // // arrPrintPink($sumSubjek);
        // // arrPrintHijau($arrSubjek);
        // //   matiHere(__LINE__);
        //
        // // /* --------------------------------------------------------------------------------------------------
        // //   * #3 pengumpulan data menjadi data siap tempur
        // //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($arrSubjek as $subj_id => $itemParam) {
        //     $sumParams = $sumSubjek[$subj_id];
        //     $transParams = $tr_datas[$subj_id];
        //
        //     // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
        //     $hasilOlahan_1[] = $itemParam + $sumParams + $transParams;
        //     // $hasilOlahan_1[] = $itemParam;
        // }
        // // arrPrintKuning($hasilOlahan_1);
        // $masterData = $hasilOlahan_1;
        $src_000 = $dt->callPerTransaksi($date1, $date2);
        $masterData = $src_000;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        //        arrPrintHijau($masterData);
        $seller_nama = $masterData[0]['now_seller_nama'];

        $arrHeaders = array(
            "master_id"                  => array(
                "label"   => "mid",
                "rowspan" => "2",
                "topth"   => true,
            ),
            // "seller_id"            => array(
            //     "label"   => "sid",
            //     "rowspan" => "2",
            // ),
            // "seller_nama"          => array(
            //     "label"   => "salesman",
            //     "rowspan" => "2",
            //     "links"   => array(
            //         // "target"  => "laporan/Crm/cekoutstandingraw",
            //         "target"  => "laporan/Crm/cekoutstandingtransaksi",
            //         "title"   => "Transaksi per salesman",
            //         "key"     => "seller_id",
            //         "heading" => "seller_nama",
            //     ),
            //     // "collapsible" => array(
            //     //     "layout" => false,
            //     //     "key"    => "seller_id",
            //     //     "target" => "laporan/Crm/cekoutstandingtransaksi",
            //     // ),
            // ),
            "customer_nama"              => array(
                "label"   => "customer",
                "rowspan" => "2",
                "topth"   => true,
            ),
            "now_dtime"                  => array(
                "label"      => "tanggal",
                "rowspan"    => "2",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
                "topth"      => true,
            ),
            "extern_nama"                => array(
                "label"      => "nomer so",
                "rowspan"    => "2",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
                "topth"      => true,
            ),
            /*---yg urut pakai ini ya gais--*/
            // "_company_cabangID_modul_subModul_jenisTr_stepCode_olehID" => array(
            //     "label"   => "counter oleh",
            //     "rowspan" => "2",
            //     "attr"    => "class='text-right'",
            // ),
            // "_company_cabangID_modul_subModul_jenisTr_stepCode_sellerID" => array(
            //     "label"   => "counter",
            //     "rowspan" => "2",
            //     "attr"    => "class='text-right'",
            // ),

            /*previous rinci*/
            // "prev_qty_order"       => array(
            //     "label"      => "previous order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_saldo_debet_582so"                                     => array(
            //     "label"      => "order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_qty_kirim"       => array(
            //     "label"      => "previous kirim qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kirim"           => array(
            //     "label"      => "previous kirim value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*prevous outstanding*/
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "summary"    => true,
            // ),
            "prev_kredit_all"            => array(
                "label"      => "outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "previous",
            ),
            // "prev_kredit_582spd"         => array(
            //     "label"      => "outstanding loc. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kredit_7499"           => array(
            //     "label"      => "outstanding proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kredit_382spd"         => array(
            //     "label"      => "outstanding exp. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_order_all"        => array(
                "label"      => "value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            // "now_saldo_order_582so"      => array(
            //     "label"      => "local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_588so"      => array(
            //     "label"      => "project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_382so"      => array(
            //     "label"      => "export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*reject-closed*/
            "now_saldo_reject_582spd"    => array(
                "label"      => "reject value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            "now_saldo_closed_582spd"    => array(
                "label"      => "closed value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            "now_batal_nilai_9912"       => array(
                "label"      => "cancel value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            "now_saldo_order_return_all" => array(
                "label"      => "return value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            "now_saldo_order_netto_all"  => array(
                "label"      => "netto value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),


            /*RETURNAN*/
            // "qty_debet_982"        => array(
            //     "label"      => "new return qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_582spd_new" => array(
            //     "label"      => "new local",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_582spd_old" => array(
            //     "label"      => "last local",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_7499"       => array(
            //     "label"      => "project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_382so"      => array(
            //     "label"      => "export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_kirim_all_new"    => array(
                "label"      => "new value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            "now_saldo_kirim_all_old"    => array(
                "label"      => "last value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            "now_saldo_batal_kirim_all"  => array(
                "label"      => "cancel value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            "now_saldo_return_kirim_all" => array(
                "label"      => "return value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            "now_saldo_kirim_netto_all"  => array(
                "label"      => "netto value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),

            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            // "now_saldo_kirim_582spd"      => array(
            //     "label"      => "total kirim",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_total"          => array(
            //     "label"      => "new packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_batal"      => array(
            //     "label"      => "new canceled packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_batal"          => array(
            //     "label"      => "new canceled packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_netto"      => array(
            //     "label"      => "new netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto"          => array(
            //     "label"      => "new netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*outstanding*/
            // "qty_outstanding"      => array(
            //     "label"      => "new outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "summary"    => true,
            // ),
            // "now_kredit_582spd_new"      => array(
            //     "label"      => "new value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "netto",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         // "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*kiriman sebelumnya*/
            // "qty_kirim_netto_old"  => array(
            //     "label"      => "netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_582spd_old"      => array(
            //     "label"      => "last value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_7499_new"        => array(
            //     "label"      => "new project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_7499_old"        => array(
            //     "label"      => "last project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_382spd"          => array(
            //     "label"      => "last value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_kredit_all_new"         => array(
                "label"      => "new value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "outstanding"
            ),
            "now_kredit_all_old"         => array(
                "label"      => "last value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "outstanding"
            ),
            /*ytd rinci*/
            // "ytd_qty_order"        => array(
            //     "label"      => "order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_order"            => array(
            //     "label"      => "order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_qty_kirim"        => array(
            //     "label"      => "kirim qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_kirim"            => array(
            //     "label"      => "kirim value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            /*last outstanding*/
            // "ytd_qty_outstanding"  => array(
            //     "label"      => "outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "last_kredit_582spd"         => array(
            //     "label"      => "total value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            "last_kredit_all"            => array(
                "label"      => "total value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
                "top_parent" => "outstanding",
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
            $judul_lap = "MTD " . dtimeNow('d F Y H:i');
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
            "mode"        => "langsung_2",
            "title"       => "Laporan Sales Order ($seller_nama)" . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            // "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // "arrTopHeaders"  => $arrTopHeaders,
            "modal_size"  => true,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "loader_div"  => "sum_dua",
        );
        $this->load->view("outstanding", $data);
    }

    public function cekoutstandingtransaksitahun()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // "113414" => array("qty" => "2", "nilai" => "0",),
            // "0"   => array("qty" => "0", "nilai" => "0",),
            // "57"  => array("qty" => "0", "nilai" => "0",),
            // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            // "65"  => array("qty" => "9", "nilai" => "21622194",),
            // "69"  => array("qty" => "111125", "nilai" => "9940808792",),
            // "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            // "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            // "718" => array("qty" => "15", "nilai" => "119260509",),
            // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // "718" => array("qty" => "0", "nilai" => "0",),
            // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "0", "nilai" => "0",),
            // "825" => array("qty" => "0", "nilai" => "0",),
            // "826" => array("qty" => "0", "nilai" => "0",),
            // "712" => array("qty" => "0", "nilai" => "0",),
            // "567" => array("qty" => "189", "nilai" => "0",),
            // // "551" => array("qty" => "0", "nilai" => "0",),
            // "205" => array("qty" => "28", "nilai" => "11886000",),
            // "182" => array("qty" => "-2304", "nilai" => "-2252939845",),
            // "127003" => array("qty" => "165", "nilai" => "400000000",),

        );
        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        /* -----------------------------------------------------------------
         * filter khusus buat seller, berlaku diluar pusat
         * -----------------------------------------------------------------*/
        if (my_cabang_id() > 0) {
            // $date1 = "2021-01-01";
            $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "2021-01-01";
            $dt->setSellerId(my_id());
            // $dt->setMasterId("153293");
            // $dt->setMasterId("153291");
            // $dt->setMasterId("113488");
        }
        else {
            if (isset($_GET['ky'])) {
                switch ($_GET['ky']) {
                    case "seller_id":
                        $dt->setSellerId($_GET[$_GET['ky']]);
                        break;
                    case "master_id":
                        $dt->setMasterId($_GET[$_GET['ky']]);
                        break;
                }
            }
        }

        // $dt->setSellerId('65');
        // $dt->setSellerId('77');
        // $dt->setMasterId("113488");
        // $dt->setMasterId("127359");
        // $dt->setMasterId("126305");
        // $dt->setMasterId("123515");
        // $dt->setMasterId("91060 ");
        // $dt->setMasterId("100786 ");

        // $src_00 = $dt->getSaldoSellerTransaksi($date1, $date2);
        // //        arrPrintKuning($src_00);
        // //        showLast_query("kuning");
        // $src_mtd = $src_00['mtd'];
        // $src_yang_lalu = $src_00['ytd_previous'];
        // $src_ytd = $src_00['ytd'];
        // $src_ytd_pluss = $src_00['ytd_pluss'];
        // $arrRekenings = $src_00['rekening'];
        // $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // $src_tr = $src_00['transaksi'];
        // $src_koloms = $src_00['kolom'];
        // // cekBiru(sizeof($src_ytd));
        // // arrPrint($src_ytd);
        // // arrPrint($src_mtd);
        // // arrPrint($src_yang_lalu);
        // // arrPrintPink($src_ytd);
        // // arrPrint($src_ytd_pluss);
        // foreach ($src_ytd_pluss as $src_ytd_pluss) {
        //     $subjek_id = $src_ytd_pluss['master_id'];
        //     $tr_datas[$subjek_id] = $src_ytd_pluss;
        // }
        // // matiDisini(__LINE__);
        // /* ------------------------------------------------------------------------------------------
        //  * saat ini
        //  * ------------------------------------------------------------------------------------------*/
        // $arrSubjek = array();
        // $sumSubjek = array();
        // foreach ($src_mtd as $item) {
        //
        //     $subjek_id = $item['master_id'];
        //     $transaksi_id = $item['transaksi_id'];
        //
        //     $seller_id = $item['seller_id'];
        //     $seller_nama = $item['seller_nama'];
        //     $rekening = $item['rekening'];
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //     foreach ($src_koloms as $src_kolom) {
        //         $$src_kolom = $item[$src_kolom];
        //
        //         $sumSubjek[$subjek_id]["now_" . $src_kolom . "_$rekening"] = $item[$src_kolom] * 1;
        //     }
        //     // cekBiru("$rekening");
        //
        //     //---------------------------------------------------------
        //     // $arrSubjek[$subjek_id]['seller_id'] = $seller_id;
        //     // $arrSubjek[$subjek_id]['seller_nama'] = $seller_nama;
        //     $arrSubjek[$subjek_id] = $item;
        //     // $arrRekenings[$rekening] = $rekening;
        //     $arrDatas[$subjek_id] = $item;
        // }
        //
        // // arrPrint($sumSubjek);
        // // matiHere(__LINE__);
        //
        // /* ------------------------------------------------------------------------------------------
        //  * yang lalu dengan prefik prev
        //  * ------------------------------------------------------------------------------------------*/
        // // arrPrintPink($src_yang_lalu);
        // // $sumSubjek = array();
        // $rekening_subjek_2 = array(
        //     "582spd", "7499"
        // );
        // $sumSubjek2 = array();
        // foreach ($src_yang_lalu as $item) {
        //
        //     $seller_id = $item['seller_id'];
        //     $subjek_id = $item['master_id'];
        //     // $subjek_id = $item['master_id'];
        //     // $qty_debet = $item['qty_debet_reguler'];
        //
        //     $debet = $item['debet'];
        //     $kredit = $item['kredit'];
        //
        //     $rekening = $item['rekening'];
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //     foreach ($src_koloms as $src_kolom) {
        //         $nilai = $item[$src_kolom] * 1;
        //
        //         // if (!isset($sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"])) {
        //         //     $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = 0;
        //         // }
        //         $sumSubjek[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $nilai;
        //
        //         $sumSubjek2[$subjek_id]["prev_" . $src_kolom . "_$rekening"] = $nilai;
        //     }
        //
        //
        //     //---------------------------------------------------------
        //     $sumSubjek[$subjek_id]['rekening'] = $rekening;
        //
        //     // $arrSubjek[$subjek_id]['seller_id'] = $item['seller_id'];
        //     // $arrSubjek[$subjek_id]['seller_nama'] = $item['seller_nama'];
        //
        //     // $arrSubjek[$subjek_id] = $item;
        //     // if($rekening == "582spd"){
        //     if (in_array($rekening, $rekening_subjek_2)) {
        //         $arrSubjek_2[$subjek_id][] = $item;
        //     }
        //
        //     // $arrRekenings[$rekening] = $rekening;
        //     $arrSeller[$seller_id] = $item;
        // }
        //
        // // cekHijau(sizeof($sumSubjek));
        // // arrPrintHijau($sumSubjek);
        // // arrPrint($arrSubjek_2);
        // // test_table($sumSubjek);
        // // matiHere(__LINE__);
        //
        // /* ----------------------------------------------------------------
        //  * filter untuk membuang prevous outstandinf yg dibawah nilai 1
        //  * ----------------------------------------------------------------*/
        // // arrPrintKuning($sumSubjek2);
        // foreach ($arrSubjek_2 as $mst_id => $item) {
        //     $arrSubjek_3[$mst_id] = end($item);
        // }
        // // arrPrint($arrSubjek_3);
        // foreach ($arrSubjek_3 as $mast_id => $item) {
        //     if ($item['rekening'] == "582spd" && $item["kredit"] >= 1) {
        //         $arrSubjek[$mast_id] = $item;
        //     }
        //     if ($item['rekening'] == "7499" && $item["kredit"] >= 1) {
        //         $arrSubjek[$mast_id] = $item;
        //     }
        // }
        // // $arrSubjek = $arrSubjek_2;
        //
        // /* ----------------------------------------------------------------------------------------------------------
        //  * Rumus san order netto order - kirim - return_kirim
        //  * dikarekan untuk penilaian performa selesman, dan return tidak menghidupkan so
        //  * ----------------------------------------------------------------------------------------------------------*/
        // // $sumSubjek = array();
        // $qty_kirim = 0;
        // foreach ($sumSubjek as $sbj_id => $sbjDatas) {
        //     $rekening = $sbjDatas['rekening'];
        //     $now_saldo_order_582so = isset($sbjDatas['now_saldo_order_582so']) ? $sbjDatas['now_saldo_order_582so'] : "0";
        //     $now_saldo_order_588so = isset($sbjDatas['now_saldo_order_588so']) ? $sbjDatas['now_saldo_order_588so'] : "0";
        //     $now_saldo_order_382so = isset($sbjDatas['now_saldo_order_382so']) ? $sbjDatas['now_saldo_order_382so'] : "0";
        //     $now_saldo_order_all = $now_saldo_order_582so + $now_saldo_order_588so + $now_saldo_order_382so;
        //
        //     $now_kredit_582spd = isset($sbjDatas['now_kredit_582spd']) ? $sbjDatas['now_kredit_582spd'] : "0";
        //     $now_kredit_7499 = isset($sbjDatas['now_kredit_7499']) ? $sbjDatas['now_kredit_7499'] : "0";
        //     $now_kredit_382spd = isset($sbjDatas['now_kredit_382spd']) ? $sbjDatas['now_kredit_382spd'] : "0";
        //     $now_kredit_all = $now_kredit_582spd + $now_kredit_7499 + $now_kredit_382spd;
        //
        //     $now_saldo_kirim_582spd = isset($sbjDatas['now_saldo_kirim_582spd']) ? $sbjDatas['now_saldo_kirim_582spd'] : "0";
        //     $now_saldo_kirim_7499 = isset($sbjDatas['now_saldo_kirim_7499']) ? $sbjDatas['now_saldo_kirim_7499'] : "0";
        //     $now_saldo_kirim_382spd = isset($sbjDatas['now_saldo_kirim_382spd']) ? $sbjDatas['now_saldo_kirim_382spd'] : "0";
        //     $now_saldo_kirim_all = $now_saldo_kirim_582spd + $now_saldo_kirim_7499 + $now_saldo_kirim_382spd;
        //
        //     $now_saldo_reject_582spd = isset($sbjDatas['now_saldo_reject_582spd']) ? $sbjDatas['now_saldo_reject_582spd'] : "0";
        //     $now_saldo_closed_582spd = isset($sbjDatas['now_saldo_closed_582spd']) ? $sbjDatas['now_saldo_closed_582spd'] : "0";
        //
        //     $prev_saldo_order_582so = isset($sbjDatas['prev_saldo_order_582so']) ? $sbjDatas['prev_saldo_order_582so'] : "0";
        //
        //     $prev_kredit_582spd = isset($sbjDatas['prev_kredit_582spd']) ? $sbjDatas['prev_kredit_582spd'] : "0";
        //     $prev_kredit_7499 = isset($sbjDatas['prev_kredit_7499']) ? $sbjDatas['prev_kredit_7499'] : "0";
        //     $prev_kredit_382spd = isset($sbjDatas['prev_kredit_382spd']) ? $sbjDatas['prev_kredit_382spd'] : "0";
        //     $prev_kredit_all = $prev_kredit_582spd + $prev_kredit_7499 + $prev_kredit_382spd;
        //
        //     $sumSubjek[$sbj_id]["prev_kredit_all"] = $prev_kredit_all;
        //     $sumSubjek[$sbj_id]["now_saldo_order_all"] = $now_saldo_order_all;
        //     $sumSubjek[$sbj_id]["now_saldo_kirim_all"] = $now_saldo_kirim_all;
        //     $sumSubjek[$sbj_id]["now_kredit_all"] = $now_kredit_all;
        //     /*--untuk membedakan pengiriman u/ order baru atau order yg lampau*/
        //     if ($now_saldo_order_582so > 0) {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = $now_saldo_kirim_582spd;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = $now_kredit_582spd;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = 0;
        //     }
        //     else {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_582spd_old"] = $now_saldo_kirim_582spd;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_582spd_old"] = $now_kredit_582spd;
        //     }
        //     if ($now_saldo_order_all > 0) {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = $now_saldo_kirim_all;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = $now_kredit_all;
        //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = 0;
        //     }
        //     else {
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_saldo_kirim_all_old"] = $now_saldo_kirim_all;
        //         $sumSubjek[$sbj_id]["now_kredit_all_new"] = 0;
        //         $sumSubjek[$sbj_id]["now_kredit_all_old"] = $prev_kredit_all - $now_saldo_kirim_all;
        //     }
        //     // $sumSubjek[$sbj_id]['last_debet'] = $prev_debet + $now_debet;
        //     // $sumSubjek[$sbj_id]['last_kredit'] = $prev_kredit + $now_kredit;
        //     $sumSubjek[$sbj_id]["last_saldo_order_582so"] = $now_saldo_order_582so > 0 ? $now_saldo_order_582so : $prev_saldo_order_582so;
        //     $last_kredit_582spd = 0;
        //     // if ($now_kredit_582spd > 0) {
        //     //     $last_kredit_582spd = ($now_kredit_582spd * 1);
        //     // }
        //     // else {
        //     // $last_kredit_582spd = $prev_kredit_582spd - $now_saldo_kirim_582spd_old - 0 + $now_kredit_582spd;
        //     $last_kredit_582spd = (($prev_kredit_582spd + $now_saldo_order_582so) - $now_saldo_reject_582spd - $now_saldo_kirim_582spd - $now_saldo_closed_582spd);
        //     // }
        //
        //     // if($last_kredit_582spd > 0){
        //
        //     // cekMerah("$sbj_id || $last_kredit_582spd = (($prev_kredit_582spd + $now_saldo_order_582so) - $now_saldo_reject_582spd - $now_saldo_kirim_582spd - $now_saldo_closed_582spd);");
        //     // }
        //     $last_kredit_7499 = $prev_kredit_all + $now_saldo_order_all - $now_saldo_reject_582spd - $now_saldo_closed_582spd - $now_saldo_kirim_all;
        //     $sumSubjek[$sbj_id]["last_kredit_582spd"] = $last_kredit_582spd;
        //     $sumSubjek[$sbj_id]["last_kredit_all"] = $last_kredit_7499;
        //
        // }
        //
        // // arrPrintWebs($sumSubjekSeller2);
        // // arrPrintWebs($sumSubjekSeller);
        // // arrPrintPink($sumSubjek);
        // // test_table($sumSubjek);
        // // arrPrintPink($sumSubjek);
        // // arrPrintHijau($arrSubjek);
        // //   matiHere(__LINE__);
        //
        // // /* --------------------------------------------------------------------------------------------------
        // //   * #3 pengumpulan data menjadi data siap tempur
        // //   * --------------------------------------------------------------------------------------------------*/
        // $hasilOlahan_1 = array();
        // foreach ($arrSubjek as $subj_id => $itemParam) {
        //     $sumParams = $sumSubjek[$subj_id];
        //     $transParams = $tr_datas[$subj_id];
        //
        //     // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
        //     $hasilOlahan_1[] = $itemParam + $sumParams + $transParams;
        //     // $hasilOlahan_1[] = $itemParam;
        // }
        // // arrPrintKuning($hasilOlahan_1);
        // $masterData = $hasilOlahan_1;
        // $src_000 = $dt->callPerTransaksi($date1, $date2);
        $src_000 = $dt->callPerTransaksiTahun($date1, $date2);
        $masterData = $src_000;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);
        $seller_nama = $masterData[0]['now_seller_nama'];

        $arrHeaders = array(
            "master_id"               => array(
                "label"   => "mid",
                "rowspan" => "2",
                "topth"   => true,
            ),
            // "seller_id"            => array(
            //     "label"   => "sid",
            //     "rowspan" => "2",
            // ),
            // "seller_nama"          => array(
            //     "label"   => "salesman",
            //     "rowspan" => "2",
            //     "links"   => array(
            //         // "target"  => "laporan/Crm/cekoutstandingraw",
            //         "target"  => "laporan/Crm/cekoutstandingtransaksi",
            //         "title"   => "Transaksi per salesman",
            //         "key"     => "seller_id",
            //         "heading" => "seller_nama",
            //     ),
            //     // "collapsible" => array(
            //     //     "layout" => false,
            //     //     "key"    => "seller_id",
            //     //     "target" => "laporan/Crm/cekoutstandingtransaksi",
            //     // ),
            // ),
            "customer_nama"           => array(
                "label"   => "customer",
                "rowspan" => "2",
                "topth"   => true,
            ),
            "now_dtime"               => array(
                "label"      => "tanggal",
                "rowspan"    => "2",
                "format"     => "formatField_he_format",
                "format_key" => "fulldate",
                "topth"      => true,
            ),
            "extern_nama"             => array(
                "label"      => "nomer so",
                "rowspan"    => "2",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
                "topth"      => true,
            ),
            /*---yg urut pakai ini ya gais--*/
            // "_company_cabangID_modul_subModul_jenisTr_stepCode_olehID" => array(
            //     "label"   => "counter oleh",
            //     "rowspan" => "2",
            //     "attr"    => "class='text-right'",
            // ),
            // "_company_cabangID_modul_subModul_jenisTr_stepCode_sellerID" => array(
            //     "label"   => "counter",
            //     "rowspan" => "2",
            //     "attr"    => "class='text-right'",
            // ),

            /*previous rinci*/
            // "prev_qty_order"       => array(
            //     "label"      => "previous order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_saldo_debet_582so"                                     => array(
            //     "label"      => "order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_qty_kirim"       => array(
            //     "label"      => "previous kirim qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kirim"           => array(
            //     "label"      => "previous kirim value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*prevous outstanding*/
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "summary"    => true,
            // ),
            "prev_kredit_all"         => array(
                "label"      => "outstanding value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-warning'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "previous",
            ),
            // "prev_kredit_582spd"         => array(
            //     "label"      => "outstanding loc. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kredit_7499"           => array(
            //     "label"      => "outstanding proj. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_kredit_382spd"         => array(
            //     "label"      => "outstanding exp. value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_order_all"     => array(
                "label"      => "value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            // "now_saldo_order_582so"      => array(
            //     "label"      => "local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_588so"      => array(
            //     "label"      => "project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_order_382so"      => array(
            //     "label"      => "export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*reject-closed*/
            "now_saldo_reject_582spd" => array(
                "label"      => "reject value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            "now_saldo_closed_582spd" => array(
                "label"      => "closed value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-info'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "new order",
            ),
            /*RETURNAN*/
            // "qty_debet_982"        => array(
            //     "label"      => "new return qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_582spd_new" => array(
            //     "label"      => "new local",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_582spd_old" => array(
            //     "label"      => "last local",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_7499"       => array(
            //     "label"      => "project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_saldo_kirim_382so"      => array(
            //     "label"      => "export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_saldo_kirim_all_new" => array(
                "label"      => "new value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            "now_saldo_kirim_all_old" => array(
                "label"      => "last value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "sales/packing list",
            ),
            /*kirim rinci*/
            // "qty_debet_reguler_582spd"     => array(
            //     "label"      => "packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),

            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            // "now_saldo_kirim_582spd"      => array(
            //     "label"      => "total kirim",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_total"          => array(
            //     "label"      => "new packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_batal"      => array(
            //     "label"      => "new canceled packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_batal"          => array(
            //     "label"      => "new canceled packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_kirim_netto"      => array(
            //     "label"      => "new netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto"          => array(
            //     "label"      => "new netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*outstanding*/
            // "qty_outstanding"      => array(
            //     "label"      => "new outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "summary"    => true,
            // ),
            // "now_kredit_582spd_new"      => array(
            //     "label"      => "new value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "netto",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         // "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*kiriman sebelumnya*/
            // "qty_kirim_netto_old"  => array(
            //     "label"      => "netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_582spd_old"      => array(
            //     "label"      => "last value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_7499_new"        => array(
            //     "label"      => "new project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_7499_old"        => array(
            //     "label"      => "last project value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "now_kredit_382spd"          => array(
            //     "label"      => "last value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            "now_kredit_all_new"      => array(
                "label"      => "new value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "outstanding"
            ),
            "now_kredit_all_old"      => array(
                "label"      => "last value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-success'",
                "attr_head"  => "class='text-right'",
                "summary"    => true,
                "top_parent" => "outstanding"
            ),
            /*ytd rinci*/
            // "ytd_qty_order"        => array(
            //     "label"      => "order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_order"            => array(
            //     "label"      => "order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_qty_kirim"        => array(
            //     "label"      => "kirim qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_kirim"            => array(
            //     "label"      => "kirim value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            /*last outstanding*/
            // "ytd_qty_outstanding"  => array(
            //     "label"      => "outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "last_kredit_582spd"         => array(
            //     "label"      => "total value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            "last_kredit_all"         => array(
                "label"      => "total value",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right bg-danger'",
                "summary"    => true,
                "top_parent" => "outstanding",
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
        $month_now = dtimeNow('Y');
        $month_req = formatTanggal($get_date1, 'Y');
        // cekHere("$month_req $month_now");
        if ($month_now == $month_req) {
            $judul_lap = "YTD " . dtimeNow('d F Y H:i');
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
            "mode"        => "langsung_2",
            "title"       => "Laporan Sales Order ($seller_nama)" . $judul_lap,
            "subTitle"    => "",
            // "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
            "data_id"     => "rawdata_" . dtimeToSecond(dtimeNow()) . randomNumber(3),
            "layout"      => isset($_GET['layout']) ? $_GET['layout'] : true,
            "master_data" => $masterData,
            "arrHeaders"  => $arrHeaders,
            // "arrTopHeaders"  => $arrTopHeaders,
            "modal_size"  => true,
            // navigasi
            "url"         => base_url(uri_string()) . "$strGet",
            "strGet"      => $strGet,
            "date1"       => $date1,
            "date2"       => $date2,
            "date_min"    => 1,
            "date_max"    => dtimeNow('Y-m-d'),
            "loader_div"  => "sum_dua",
        );
        $this->load->view("outstanding", $data);
    }

    public function cekoutstandingraw()
    {
        /* ---------------------------------------------------------------------
         * data outstanding statik per seller akhir tahun 2021
         * ---------------------------------------------------------------------*/
        $arrOutstanding2021 = array(
            // "0"   => array("qty" => "0", "nilai" => "0",),
            // "57"  => array("qty" => "0", "nilai" => "0",),
            // "61"  => array("qty" => "69", "nilai" => "108594099.1",),
            "61"  => array("qty" => "44", "nilai" => "99561369",),
            "65"  => array("qty" => "9", "nilai" => "21622194",),
            "69"  => array("qty" => "111936", "nilai" => "10243373229",),
            "73"  => array("qty" => "14", "nilai" => "31213718.1828",),
            "576" => array("qty" => "189", "nilai" => "449897306.72834",),
            // "576" => array("qty" => "171", "nilai" => "449897306.72834",),
            "664" => array("qty" => "2339", "nilai" => "1361999916.33",),
            "718" => array("qty" => "15", "nilai" => "119260509",),
            // "663" => array("qty" => "-2", "nilai" => "-7178000",),
            // "718" => array("qty" => "0", "nilai" => "0",),
            // "719" => array("qty" => "0", "nilai" => "0",),
            // "808" => array("qty" => "0", "nilai" => "0",),
            // "825" => array("qty" => "0", "nilai" => "0",),
            // "826" => array("qty" => "0", "nilai" => "0",),
            // "712" => array("qty" => "0", "nilai" => "0",),
            // "567" => array("qty" => "189", "nilai" => "0",),
            // // "551" => array("qty" => "0", "nilai" => "0",),
            // "205" => array("qty" => "28", "nilai" => "11886000",),
            // "182" => array("qty" => "-2304", "nilai" => "2252939845",),
            // "77"  => array("qty" => "-2", "nilai" => "-2342344",),

        );

        // -----------------------------------------------------------------
        $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow('Y-m-01');
        $date2 = $get_date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow('Y-m-d');

        $this->load->library("laporan/DataCrm");
        $dt = new DataCrm();
        if (isset($_GET['ky'])) {

            arrPrint($_GET);
            $dt->setSellerId($_GET['seller_id']);
        }

        $src_00 = $dt->callOrderan($date1, $date2);
        $src_mtd = $src_00['mtd'];
        $src_ytd = $src_00['ytd'];
        $src_ytd_pluss = $src_00['ytd_pluss'];
        $src_yang_lalu = $src_00['ytd_previous'];
        $arrRekenings = $src_00['rekening'];
        $arrTransaksiTipes = $src_00['transaksi_tipe'];
        // cekBiru(sizeof($src_ytd));
        // arrPrint($src_ytd);
        // arrPrint($src_mtd);
        // arrPrint($src_ytd_pluss);
        // matiHere();
        /* ------------------------------------------------------------------------------------------
         * saat ini
         * ------------------------------------------------------------------------------------------*/
        foreach ($src_mtd as $item) {

            $subjek_id = $item['seller_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
            $qty_debet_closed = $item['qty_debet_closed'];
            $debet_closed = $item['debet_closed'];
            $qty_debet_batal = $item['qty_debet_batal'];
            $debet_batal = $item['debet_batal'];
            $rekening = $item['rekening'];
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

            $subjek_id = $item['seller_id'];
            $qty_debet = $item['qty_debet_reguler'];
            $debet = $item['debet_reguler'];
            $qty_debet_rejected = $item['qty_debet_rejected'];
            $debet_rejected = $item['debet_rejected'];
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
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {

                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";
                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller = "debet_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sbjDatas[$caller_prev_qty]) ? $sbjDatas[$caller_prev_qty] : 0;
                    $$caller_prev = isset($sbjDatas[$caller_prev]) ? $sbjDatas[$caller_prev] : 0;
                    $$caller_qty = isset($sbjDatas[$caller_qty]) ? $sbjDatas[$caller_qty] : 0;
                    $$caller = isset($sbjDatas[$caller]) ? $sbjDatas[$caller] : 0;
                }
            }

            $awalOts = isset($arrOutstanding2021[$sbj_id]) ? $arrOutstanding2021[$sbj_id] : 0;
            $alhir2021qty = $awalOts["qty"];
            $alhir2021nilai = $awalOts["nilai"];

            // mati_disini($qty_debet_reguler_582so);
            // -------------------------NEW OUTSTANDING-------------------------------------------------
            $qty_return_order = ($qty_debet_rejected_582so + $qty_debet_closed_582so);
            $qty_return_kirim = ($qty_debet_rejected_582spd + $qty_debet_closed_582spd);
            $qty_pembatalan_kirim = ($qty_debet_batal_9912);
            $qty_order = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order - $qty_return_kirim - $qty_pembatalan_kirim;
            // $qty_kirim = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_return_kirim;
            $qty_kirim = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_pembatalan_kirim;

            $qty_outstanding = $qty_order - $qty_kirim;
            // cekBiru("$qty_outstanding = $qty_order - $qty_kirim");
            $qty_outstanding_f = $qty_outstanding < 0 ? 0 : $qty_outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['qty_outstanding'] += $qty_outstanding;
            $sumSubjek[$sbj_id]['qty_order_netto'] = $qty_order;
            $sumSubjek[$sbj_id]['qty_kirim_netto'] = $qty_kirim;

            $return_order = ($debet_rejected_582so + $debet_closed_582so);
            $return_kirim = ($debet_rejected_582spd + $debet_closed_582spd);
            $pembatalan_kirim = ($debet_batal_9912);
            $order = ($debet_reguler_582so + $debet_reguler_382so) - $return_order - $return_kirim - $pembatalan_kirim;
            // $kirim = ($debet_reguler_582spd + $debet_reguler_382spd) - $return_kirim;
            $kirim = ($debet_reguler_582spd + $debet_reguler_382spd) - $pembatalan_kirim;

            $outstanding = $order - $kirim;
            $outstanding_f = $qty_outstanding_f == 0 ? 0 : $outstanding;
            if (!isset($outstandingSubjek[$sbj_id]['outstanding'])) {
                $outstandingSubjek[$sbj_id]['outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['outstanding'] += $outstanding;
            $sumSubjek[$sbj_id]['order_netto'] = $order;
            $sumSubjek[$sbj_id]['kirim_netto'] = $kirim;

            // -------------------------PREVIOUS OUTSTANDING-------------------------------------------------
            $prev_qty_return_order = ($prev_qty_debet_rejected_582so + $prev_qty_debet_closed_582so);
            $prev_qty_return_kirim = ($prev_qty_debet_rejected_582spd + $prev_qty_debet_closed_582spd);
            $prev_qty_pembatalan_kirim = ($prev_qty_debet_batal_9912);
            $prev_qty_order = ($prev_qty_debet_reguler_582so + $prev_qty_debet_reguler_382so) - $prev_qty_return_order - $prev_qty_return_kirim - $prev_qty_pembatalan_kirim;
            // $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_return_kirim;
            $prev_qty_kirim = ($prev_qty_debet_reguler_582spd + $prev_qty_debet_reguler_382spd) - $prev_qty_pembatalan_kirim;
            $prev_qty_outstanding = $alhir2021qty + ($prev_qty_order - $prev_qty_kirim);
            // cekHijau("$prev_qty_outstanding = $prev_qty_order - $prev_qty_kirim;");
            // $prev_qty_outstanding = ($prev_qty_order - $prev_qty_kirim);
            if (!isset($outstandingSubjek[$sbj_id]['prev_qty_outstanding'])) {
                $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] = 0;
            }
            $outstandingSubjek[$sbj_id]['prev_qty_outstanding'] += $prev_qty_outstanding;
            // ------------------------------------------------------------------------------------------------
            $prev_return_order = ($prev_debet_rejected_582so + $prev_debet_closed_582so);
            $prev_return_kirim = ($prev_debet_rejected_582spd + $prev_debet_closed_582spd);
            $prev_pembatalan_kirim = ($prev_debet_batal_9912);
            $prev_order = ($prev_debet_reguler_582so + $prev_debet_reguler_382so) - $prev_return_order - $prev_return_kirim - $prev_pembatalan_kirim;
            $prev_kirim = ($prev_debet_reguler_582spd + $prev_debet_reguler_382spd) - $prev_pembatalan_kirim;
            $prev_outstanding = $alhir2021nilai + ($prev_order - $prev_kirim);
            // $prev_outstanding = $alhir2021nilai + (($prev_debet_582so + $prev_debet_382so) - $prev_debet_982) - ($prev_debet_582spd + $prev_debet_382spd);
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
            // $customer_id = $itemParam['m_customerID'];
            // arrPrintKuning($itemParam);
            //region penyiapan kolom data YTD
            foreach ($arrRekenings as $strekening) {
                foreach ($arrTransaksiTipes as $trtipe) {
                    $caller_prev_qty = "prev_qty_debet_$trtipe" . "_$strekening";
                    $caller_qty = "qty_debet_$trtipe" . "_$strekening";
                    $caller_prev = "prev_debet_$trtipe" . "_$strekening";
                    $caller = "debet_$trtipe" . "_$strekening";

                    $$caller_prev_qty = isset($sumSubjek[$subj_id][$caller_prev_qty]) ? $sumSubjek[$subj_id][$caller_prev_qty] : 0;
                    $$caller_prev = isset($sumSubjek[$subj_id][$caller_prev]) ? $sumSubjek[$subj_id][$caller_prev] : 0;
                    $$caller_qty = isset($sumSubjek[$subj_id][$caller_qty]) ? $sumSubjek[$subj_id][$caller_qty] : 0;
                    $$caller = isset($sumSubjek[$subj_id][$caller]) ? $sumSubjek[$subj_id][$caller] : 0;
                }
            }

            // cekMerah("$qty_debet_batal_9912");
            $qty_return_order_mtd = ($qty_debet_rejected_582so + $qty_debet_closed_582so);
            $qty_return_kirim_mtd = ($qty_debet_rejected_582spd + $qty_debet_closed_582spd);
            $qty_batal_kirim_mtd = $qty_debet_batal_9912;
            $qty_order_mtd = ($qty_debet_reguler_582so + $qty_debet_reguler_382so) - $qty_return_order_mtd - $qty_return_kirim_mtd - $qty_batal_kirim_mtd;
            // $qty_kirim_mtd = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_return_kirim_mtd;
            $qty_kirim_mtd = ($qty_debet_reguler_582spd + $qty_debet_reguler_382spd) - $qty_batal_kirim_mtd;

            $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;
            // cekHijau("[$subj_id] ".$outstandingSubjek[$subj_id]["prev_qty_outstanding"]." $qty_mtd = $qty_order_mtd - $qty_kirim_mtd;");
            $ytd_qty_outstanding = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $qty_mtd;
            // $sub_outstanding["ytd_qty_outstanding"] = $outstandingSubjek[$subj_id]["prev_qty_outstanding"] + $outstandingSubjek[$subj_id]["qty_outstanding"];
            $sub_outstanding["ytd_qty_outstanding"] = $ytd_qty_outstanding;
            // -----------------------------------------------------------------------------------
            $return_order_mtd = ($debet_rejected_582so + $debet_closed_582so);
            $return_kirim_mtd = ($debet_rejected_582spd + $debet_closed_582spd);
            $batal_kirim_mtd = $debet_batal_9912;
            $order_mtd = ($debet_reguler_582so + $debet_reguler_382so) - $return_order_mtd - $return_kirim_mtd - $batal_kirim_mtd;
            // $kirim_mtd = ($debet_reguler_582spd + $debet_reguler_382spd) - $return_kirim_mtd;
            $kirim_mtd = ($debet_reguler_582spd + $debet_reguler_382spd) - $batal_kirim_mtd;

            $mtd = $order_mtd - $kirim_mtd;
            // $mtd = ($mtd_582so + $mtd_382so - $mtd_982) - ($mtd_582spd + $mtd_382spd);
            $ytd_outstanding = $outstandingSubjek[$subj_id]["prev_outstanding"] + $mtd;
            // $sub_outstanding["ytd_outstanding"] = $outstandingSubjek[$subj_id]["prev_outstanding"] + $outstandingSubjek[$subj_id]["outstanding"];
            $sub_outstanding["ytd_outstanding"] = $ytd_qty_outstanding > 0 ? $ytd_outstanding : 0;
            //endregion

            // $hasilOlahan_1[] = $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            $hasilOlahan_1[] = $itemParam + $sumSubjek[$subj_id] + $outstandingSubjek[$subj_id] + $sub_outstanding;
            // $hasilOlahan[$customer_id] = $itemParam;
        }
        $masterData = $src_ytd_pluss;
        /* ------------------------------------------------------------------------------
         * data yg tampil ditentukan dari sini
         * ------------------------------------------------------------------------------*/
        // arrPrintHijau($masterData);

        $arrHeaders = array(
            "master_id"                                         => array(
                "label" => "sid",
            ),
            "fulldate"                                          => array(
                "label"  => "tanggal",
                "format" => "formatField_he_format",
            ),
            "_step_1_nomer"                                     => array(
                "label"      => "tanggal",
                "format"     => "formatField_he_format",
                "format_key" => "nomer",
            ),
            "seller_nama"                                       => array(
                "label" => "salesman",
            ),
            "_company_cabangID_modul_subModul_jenisTr_sellerID" => array(
                "label" => "urut salesman",
            ),
            "cabang_nama"                                       => array(
                "label" => "cabang",
            ),
            "rekening"                                          => array(
                "label" => "cabang",
            ),
            /*prevous outstanding*/
            // "prev_qty_outstanding" => array(
            //     "label"      => "previous outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "prev_outstanding"     => array(
            //     "label"      => "previous outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-warning'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*order rincian*/
            "qty_debet_reguler"                                 => array(
                "label"      => "qty_debet_reguler",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_debet_582so_reguler"                           => array(
                "label"      => "qty_debet_so_reg",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_kredit_582so_rejected"                         => array(
                "label"      => "qty_kredit_so_rej",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_debet_582so_rejected"                          => array(
                "label"      => "qty_debet_so_rej",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_debet_582spd_reguler"                          => array(
                "label"      => "qty_debet_spd reg",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_debet_rejected"                                => array(
                "label"      => "qty_debet_reject",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_debet_closed"                                  => array(
                "label"      => "qty_debet_closed",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "debet_batal"                                       => array(
                "label"      => "debet_batal",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
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
            // "qty_order_netto"      => array(
            //     "label"      => "new netto order qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "order_netto"          => array(
            //     "label"      => "new netto order value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-info'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
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
            "qty_kredit_reguler"                                => array(
                "label"      => "qty_kredit_reguler",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_kredit_rejected"                               => array(
                "label"      => "qty_kredit_reject",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "qty_kredit_closed"                                 => array(
                "label"      => "qty_kredit_closed",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            "kredit_batal"                                      => array(
                "label"      => "kredit_batal",
                "format"     => "formatField_he_format",
                "format_key" => "harga",
                "attr"       => "class='text-right'",
                "summary"    => true,
            ),
            // "debet_reguler_582spd"         => array(
            //     "label"      => "packing list local value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_reguler_382spd"         => array(
            //     "label"      => "packing list export value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_rejected_582spd"     => array(
            //     "label"      => "packing list balik qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "qty_debet_reguler_382spd"     => array(
            //     "label"      => "packing list export qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "debet_batal_9912"         => array(
            //     "label"      => "packing list cancel",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*kirim*/
            // "qty_kirim_netto"      => array(
            //     "label"      => "new netto packing list qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "kirim_netto"          => array(
            //     "label"      => "new netto packing list value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-success'",
            //     "attr_head"  => "class='text-right'",
            //     "summary"    => true,
            // ),
            /*outstanding*/
            // "qty_outstanding"      => array(
            //     "label"      => "new outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right'",
            //     "summary"    => true,
            // ),
            // "outstanding"          => array(
            //     "label"      => "new outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "netto",
            //     "attr"       => "class='text-right'",
            //     "logics"     => array(
            //         "nilai" => "<0",
            //         "text"  => "termasuk pengiriman dari outstanding sebelumnya",
            //     ),
            //     "summary"    => true,
            // ),
            /*last outstanding*/
            // "ytd_qty_outstanding"  => array(
            //     "label"      => "total last outstanding qty",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
            // "ytd_outstanding"      => array(
            //     "label"      => "total outstanding value",
            //     "format"     => "formatField_he_format",
            //     "format_key" => "harga",
            //     "attr"       => "class='text-right bg-danger'",
            //     "summary"    => true,
            // ),
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
            $judul_lap = "MTD " . dtimeNow('d F Y H:i');
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
            "title"       => "Laporan Salesman Outstanding " . $judul_lap,
            "subTitle"    => "Raw data pembelian",
            "modul_path"  => $this->modul_path,
            // "color_bar"   => "box-info",
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"     => "582",
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

    public function maintenance()
    {

        echo underMaintenance();
    }
    // -------------------------------------------
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
        // $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
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
            // "title"       => "Outstanding Sales Order Penjualan", // TIDAK COCOK diganti 12/12/2022
            "title"       => "Outstanding Sales Order Report",
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
            "sum_satu"    => base_url() . "laporan/Outstanding/cekoutstandingseller" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            // "sum_null"    => base_url() . "laporan/Crm/cekoutstanding" . "$strGet",
            // "sum_null"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/cekoutstandingtransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Crm/cekoutstandingtransaksi" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Crm/produkoutstandingblncabang" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
            "loader"      => $loaders,
        );
        $this->load->view("outstanding", $data);
    }

    /*rendering untuk yg khusus seller*/
    public function viewemyoutstanding()
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
        // $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "2021-01-01";
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
        // $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
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
            "title"       => "Outstanding Sales Order Report",
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
            // "sum_satu"    => base_url() . "laporan/Crm/cekoutstandingseller" . "$strGet",
            "sum_dua"     => base_url() . "laporan/Outstanding/cekoutstandingtransaksi" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/maintenance" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/cekoutstandingtransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Crm/cekoutstandingtransaksi" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Crm/produkoutstandingblncabang" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
            "loader"      => $loaders,
        );
        $this->load->view("outstanding", $data);
    }

    public function vieweoutstandingthn()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningTransaksiPembantu");
        // $ps = new ComRekeningTransaksiPembantu();

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
        // -------------------------------------------------------------------------------------------------------------
        for ($i = 1; $i <= 3; $i++) {
            // echo $i;
            // echo "$thini  ";
            $thnya = $year_now--;
            $arrTahun[$thnya] = $thnya . "-01-01";
        }
        $strTahun = formatTanggal($date1, 'Y');
        // arrPrintPink($strTahun);
        // arrPrint($arrTahun);
        $action = base_url() . "laporan/" . get_class($this) . "/vieweoutstandingthn";
        $key = "?date1";
        $var = "";
        $var .= "<select id='tahun' class='btn btn-danger' onchange=\"location.href='$action" . $key . "='+this.value+'&date2='+this.value\">";
        $var .= "<option value=''>--pilih tahun--</option>";
        foreach ($arrTahun as $th => $val) {
            $selected = $th == $strTahun ? "" : "";
            $var .= "<option value='$val' $selected>$th</option>";
        }
        $var .= "</select>";
        // -------------------------------------------------------------------------------------------------------------

        // cekMerah("$date_stop_setelahnya / $date_start_setelahnya > " . dtimeNow('Y-m-d'));
        /* -----------------------------------------------------------------------------------------------
         * button navigasi bulanan
         * -----------------------------------------------------------------------------------------------*/
        // cekMerah("$date_start_sebelumnya ****** $date_stop_sebelumnya ******* " . date('Y-m-t', strtotime('2022-02-01')));
        // cekHijau($ulr_bulan_sebelum . " **** $str_get **** $str_geters");
        $btn_td = "";
        // $btn_td .= "<button type='button' class='btn btn-danger' disabled onclick=\"location.href='$ulr_bulan_sebelum'\"><i class='fa fa-chevron-circle-left'></i> $nama_bulan_sebelum </button>";
        // $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled>$nama_bulan_ygtampil <i class='fa fa-chevron-circle-down'></i></button>";
        // $btn_td .= " <button type='button' class='btn $btn_active btn-danger' $btn_disabled_mtd onclick=\"location . href = '$ulr_bulan_ini'\">MTD</button>";
        // $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
        // $btn_td .= "&nbsp;<button type='button' class='btn btn-danger' disabled $btn_disabled_aft  onclick=\"location . href = '$ulr_bulan_setelah'\">$nama_bulan_setelah <i class='fa fa-chevron-circle-right '></i></button>";
        $btn_td .= "&nbsp;" . $var;
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
        $scriptBottom = "<script>$('#top_navigasi').fadeOut();</script>";
        $scriptBottom = "";
        $data = array(
            "mode"        => "indek",
            // "title"       => "Outstanding Sales Order Penjualan", // TIDAK COCOK diganti 12/12/2022
            "title"       => "Outstanding Sales Order Report",
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

            "sum_satu"     => base_url() . "laporan/Outstanding/cekoutstandingsellertahun" . "$strGet",

            // "sum_satu"    => base_url() . "laporan/Crm/maintenance" . "$strGet",
            // "sum_null"    => base_url() . "laporan/Crm/cekoutstanding" . "$strGet",
            // "sum_null"    => base_url() . "laporan/Crm/produkoutstandingblnseller" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/cekoutstandingtransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Crm/cekoutstandingtransaksi" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Crm/produkoutstandingblncabang" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
            "loader"       => $loaders,
            "scriptBottom" => $scriptBottom,
        );
        $this->load->view("outstanding", $data);
    }

    public function viewmyoutstandingthn()
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
        // $_GET['date1'] = $date1 = $get_date1 = isset($_GET['date1']) ? $_GET['date1'] : "2021-01-01";
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
        // $btn_td .= " <button type='button' class='btn btn-danger' $btn_disabled_ytd onclick=\"location . href = '$ulr_tahun_ini'\">YTD</button>";
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
        $scriptBottom = "<script>$('#top_navigasi').fadeOut();</script>";
        $data = array(
            "mode"         => "indek",
            "title"        => "Outstanding Sales Order Report",
            "subTitle"     => "Raw Data Sales Order",
            "modul_path"   => $this->modul_path,
            // "jenisTr"     => $this->jenisTr,
            "jenisTr"      => "582",
            "master_data"  => $masterData,
            "arrHeaders"   => $arrHeaders,
            // navigasi
            "url"          => base_url(uri_string()) . "$strGet",
            "data_id"      => "permaster",
            "add_td"       => $add_td,
            "date1"        => $date1,
            "date2"        => $date2,
            "date_min"     => "2022-01-01",
            "date_max"     => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Crm/perindekshow/produkoutstandingbln" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Crm/cekoutstandingseller" . "$strGet",
            "sum_dua"      => base_url() . "laporan/Outstanding/cekoutstandingtransaksitahun" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/maintenance" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Crm/cekoutstandingtransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Crm/cekoutstandingtransaksi" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Crm/produkoutstandingblncabang" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
            "loader"       => $loaders,
            "scriptBottom" => $scriptBottom,
        );
        $this->load->view("outstanding", $data);
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
            "date_min"    => "2021-01-01",
            "date_max"    => dtimeNow('Y-m-d'),
            // "sum_null"    => base_url() . "laporan/Penjualan/produksopersales" . "$strGet",
            "sum_null"    => base_url() . "laporan/Outstanding/$ygditampilkan" . "$strGet",
            // "sum_satu"    => base_url() . "laporan/Penjualan/produksosales" . "$strGet",
            // "sum_dua"     => base_url() . "laporan/Penjualan/produksotransaksi" . "$strGet",
            // "sum_tiga"    => base_url() . "laporan/Penjualan/produksoproduk" . "$strGet",
            // "sum_empat"   => base_url() . "laporan/Penjualan/produksocustomer" . "$strGet",
            // "sum_lima"    => base_url() . "laporan/Crm/produkoutstandingcabang" . "$strGet",
        );
        $this->load->view("laporan", $data);
    }
    // -----------------------------------------------------------------------

    /*mongo*/
    /* -------------------------------------------------------------------------
     * untuk mengon off kan koneksi ke mongo dari confic heWeb
     * -------------------------------------------------------------------------*/
    private function MdbConnection()
    {
        $this->load->config("heWebs");
        $con_webs = $this->config->item("mongo");
        // arrPrintKuning($con_webs);
        $ping = $this->ping_monngo();
        $mdb_connection = false;
        if ($ping == 0) {
            $mdb_connection = isset($con_webs["connection"]) ? $con_webs["connection"] : false;
        }

        return $mdb_connection;
    }

    /* -------------------------------------------------------------------------
     * mendapatkan transaksi id yg terakhir dari table transaksi
     * -------------------------------------------------------------------------*/
    private function cekLastTransaksiId()
    {
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $condites = array(
            "link_id"      => 0,
            "jenis_master" => "582",
        );
        $this->db->limit(1);
        $this->db->order_by("id", "desc");
        $this->db->select("id");
        $src_tr = $tr->lookupByCondition($condites)->row();

        return $src_tr->id;
    }

    /* -------------------------------------------------------------------------
     * PING server mongo
     * -------------------------------------------------------------------------*/
    private function ping_monngo()
    {
        $host = "192.168.5.14";
        $status = 0;
        // $pingresult = exec("/bin/ping -c 4 $host", $outcome, $status);

        return $status;
    }
}