<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 03/04/2019
 * Time: 13.50
 */

class Bi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("SmtpMailer");
        $this->load->helper("he_angka");

        $this->reportJenis = array(
            "pre_penjualan" => array(
                "582spo",
                "382spo",
                // "582so",
            ),
            "pre_penjualan_canceled" => array(
                "582spo",
                "382spo",
                // "582so",
            ),
            "penjualan" => array(
                "582spd",
                "982",
                "382spd",
                // "982",
            ),
            "pembelian_supplies" => array(
                "461",
                "961",
            ),
            "pembelian_produk" => array(
                "467",
                "961",
            ),
        );
    }

    public function viewProdukBi()
    {
        $this->load->model("Mdls/MdlBi");
        $this->load->model("Mdls/MdlProduk");
        $class = get_class();
        $pr = new MdlProduk();
        $bi = new MdlBi();
        $cabang_id = "1";
        // arrPrint($_SESSION);
        // arrPrint($_SESSION['webs']['cart']);

        $periode = isset($_SESSION[$class]['periode']) ? $_SESSION[$class]['periode'] : 1;
        $indeks = isset($_SESSION[$class]['indeks']) ? $_SESSION[$class]['indeks'] : 100;
        $buffer = isset($_SESSION[$class]['buffer']) ? $_SESSION[$class]['buffer'] : 1;
        $leadTime = isset($_SESSION[$class]['leadTime']) ? $_SESSION[$class]['leadTime'] : 100;
        $jml_hari_penjualan = $periode * 30;
        // $_SESSION[$class] = array();
        $arrBi = array();
        if (!isset($_SESSION[$class])) {
            $arrBi["indeks"] = $indeks;
            $arrBi["buffer"] = $buffer;
            $arrBi["periode"] = $periode;
            $arrBi["leadTime"] = $leadTime;
            $_SESSION[$class] = $arrBi;
            // cekHere("masukin array");
        } else {
            $arrBi = $_SESSION[$class];
        }
        $arrBiAttr["indeks"] = array(
            "label" => "index",
            "minimal" => "100",
        );
        $arrBiAttr["buffer"] = array(
            "label" => "buffer",
            "minimal" => "1",
        );
        $arrBiAttr["periode"] = array(
            "label" => "month periode",
            "minimal" => "1",
        );
        $arrBiAttr["leadTime"] = array(
            "label" => "index lead time",
            "minimal" => "100",
        );
        // }

        //region jml hari pembelian
        $dtime = $kemarin = date('Y-m-d', strtotime("-$jml_hari_penjualan day", strtotime(date("Y-m-d"))));
        // $arrPembelian_terakhir_1[$barang_id] = $dtime;
        // $arrJml_hari_penjualan_1[$barang_id] = $jml_hari_penjualan;
        $str_metode = "dari rata-rata penjualan selama $jml_hari_penjualan hari terakhir === $dtime";
        // cekHere("$str_metode");
        //endregion

        $tmpPr = $pr->lookupAll();
        $produks = $tmpPr->result();
        // showLast_query("kuning");
        // arrPrint($produks);

        $tmpStok = $bi->getStokNowAll();
        //         showLast_query("here");
        //         arrPrintWebs($tmpStok);
        // matiHere();
        $condites = array(
            "dtime >" => $dtime,
        );
        $bi->setCondites($condites);
        // $tmp = $bi->lookupPenjualanProduk();
        // $tmpPenjualan = $bi->lookupPenjualanProdukHr();
        $tmpPenjualan = $bi->lookupPenjualanProdukHrAll();
        $tmpReturnPenjualan = $bi->lookupReturnPenjualanProdukHrAll();
        // showLast_query("lime");
        // arrPrint($tmpPenjualan);
        // arrPrint($arrOpen);
        // arrPrint($tmpReturnPenjualan);
        // cekMerah("$jml_item / $qty_item");
        // matiHere($leadTime);
        $data = array(
            "mode" => "view",
            "title" => "BI",
            "subTitle" => "Penjualan vs stok",
            "periode" => $periode,
            "indeks" => $indeks,
            "buffer" => $buffer,
            "leadTime" => $leadTime,
            "navigasi" => $arrBi,
            "navigasiAttr" => $arrBiAttr,
            "produks" => $produks,
            "stokNow" => $tmpStok["sums"],
            "penjualan" => $tmpPenjualan["sums"],
            "returnPenjualan" => $tmpReturnPenjualan["sums"],
        );
        $this->load->view("bi", $data);

    }

    public function viewSession()
    {
        arrPrint($_SESSION);
    }

    public function createSession()
    {
        // cekBiru(__LINE__);
        $class = get_class();
        // cekHijau($class);
        // arrPrint($_REQUEST);
        $name = $_GET['n'];
        $value = $_GET['v'];
        // cekOrange("$class $name/createSession $value");

        $srr2 = array();
        $srr2[$name] = $value;
        if (!isset($_SESSION[$class][$name])) {

            $_SESSION[$class][$name] = $value;
        }

        $_SESSION[$class][$name] = $value;
        // arrPrint($_SESSION[$class]);

        $link_bi_items = MODUL_PATH . "Bi/viewProdukSales_2/466/pembelian";
        echo "<script>
            $('#biItems').load('$link_bi_items');
        </script>";

    }

    //region update table produk per kolom
    public function updateProdukMoqTime()
    {
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $produk_id = $this->uri->segment(3);
        $newLimit = $_GET['v'];

        $wheres = array(
            "id" => $produk_id
        );
        $newLimits = array(
            "moq_time" => $newLimit,
        );
        $this->db->trans_begin();
        $upd = $pr->updateData($wheres, $newLimits);
        // showLast_query("lime");

        // matiHere("cek boss");
        $this->db->trans_complete();
    }

    public function updateProdukMoq()
    {
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $produk_id = $this->uri->segment(3);
        $newLimit = $_GET['v'];

        $wheres = array(
            "id" => $produk_id
        );
        $newLimits = array(
            "moq" => $newLimit,
        );
        $this->db->trans_begin();
        $upd = $pr->updateData($wheres, $newLimits);
        // showLast_query("lime");

        // matiHere("cek boss");
        $this->db->trans_complete();
    }

    public function updateProdukLimitTime()
    {
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $produk_id = $this->uri->segment(3);
        $newLimit = $_GET['v'];

        $wheres = array(
            "id" => $produk_id
        );
        $newLimits = array(
            "limit_time" => $newLimit,
        );
        $this->db->trans_begin();
        $upd = $pr->updateData($wheres, $newLimits);
        // showLast_query("lime");

        // matiHere("cek boss");
        $this->db->trans_complete();
    }

    public function updateProdukLimit()
    {
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $produk_id = $this->uri->segment(3);
        $newLimit = $_GET['v'];

        $this->db->trans_begin();
        $upd = $pr->updateLimit($produk_id, $newLimit);
        // showLast_query("lime");

        // matiHere("cek boss");
        $this->db->trans_complete();
    }

    public function updateProdukLeadTime()
    {
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $produk_id = $this->uri->segment(3);
        $newLimit = $_GET['v'];

        $this->db->trans_begin();
        $upd = $pr->updateLeadTime($produk_id, $newLimit);
        // showLast_query("lime");

        // matiHere("cek boss");
        $this->db->trans_complete();
    }

    public function updateProdukIndeks()
    {
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $produk_id = $this->uri->segment(4);
        $newLimit = $_GET['v'];

        $this->db->trans_begin();
        $upd = $pr->updateIndeks($produk_id, $newLimit);
        showLast_query("lime");

        // matiHere("cek boss");
        $this->db->trans_complete();
    }
    //endregion
    //region update master seting
    public function updateSetingLimit()
    {
        $this->load->model("Mdls/MdlCalcStokLimit");
        $pr = new MdlCalcStokLimit();
        $produk_id = $this->uri->segment(3);
        $newLimit = $_GET['v'];

        $wheres = array(
            "id" => $produk_id
        );
        $newLimits = array(
            "nilai" => $newLimit,
        );
        $this->db->trans_begin();
        $upd = $pr->updateData($wheres, $newLimits);
        // showLast_query("lime");
        //
        // matiHere("cek boss");
        $this->db->trans_complete();
    }

    //endregion

    /* =====================================
     * viewProdukSales
     * penghitung order stok BI-BI an berdasarkan penjualan bulanan
     * --------------------------------------*/
    public function viewProdukSales_1()
    {
        $this->load->model("Mdls/MdlReport");
        $this->load->model("Mdls/MdlBi");
        $this->load->model("Mdls/MdlProduk");
        $rp = new MdlReport();
        $class = get_class();
        $pr = new MdlProduk();
        $bi = new MdlBi();
        $cabang_id = "1";
        // arrPrint($_SESSION);
        // arrPrint($_SESSION['webs']['cart']);

        $refSegmen2 = explode("/", url_referer())[4];
        // cekHere(url_referer() . " methode" . __FUNCTION__ . " segmen::" . $this->uri->segment(2) . " s2::" . $refSegmen2);
        if ($this->uri->segment(2) != $refSegmen2) {
            if (isset($_SESSION[$class])) {
                unset($_SESSION[$class]);
            }
        }
        $periode = isset($_SESSION[$class]['periode']) ? $_SESSION[$class]['periode'] : 3;
        $indeks = isset($_SESSION[$class]['indeks']) ? $_SESSION[$class]['indeks'] : 100;
        $buffer = isset($_SESSION[$class]['buffer']) ? $_SESSION[$class]['buffer'] : 1;
        $buferTime = isset($_SESSION[$class]['bufferTime']) ? $_SESSION[$class]['bufferTime'] : 1;
        $leadTime = isset($_SESSION[$class]['leadTime']) ? $_SESSION[$class]['leadTime'] : 1;
        $llimitTime = isset($_SESSION[$class]['limitTime']) ? $_SESSION[$class]['limitTime'] : 1;
        $moqTime = isset($_SESSION[$class]['moqTime']) ? $_SESSION[$class]['moqTime'] : 1;
        $jml_hari_penjualan = $periode * 30;
        // $_SESSION[$class] = array();
        $arrBi = array();
        // if (!isset($_SESSION[$class])) {
        $arrBi["indeks"] = $indeks;
        $arrBi["periode"] = $periode;
        $arrBi["moqTime"] = $moqTime;
        // $arrBi["buffer"] = $buffer;
        $arrBi["limitTime"] = $llimitTime;
        $arrBi["leadTime"] = $leadTime;
        $_SESSION[$class] = $arrBi;
        // cekHere("masukin array");
        // }
        // else {
        //     $arrBi = $_SESSION[$class];
        // }
        // $arrBiAttr["indeks"] = array(
        //     "label"   => "index",
        //     "minimal" => "100",
        // );
        // $arrBiAttr["buffer"] = array(
        //     "label"   => "buffer",
        //     "minimal" => "1",
        // );
        // $arrBiAttr["periode"] = array(
        //     "label"   => "month periode",
        //     "minimal" => "1",
        // );
        // $arrBiAttr["leadTime"] = array(
        //     "label"   => "index lead time",
        //     "minimal" => "100",
        // );
        // }

        //region jml hari pembelian
        $dtime = $kemarin = date('Y-m-d', strtotime("-$jml_hari_penjualan day", strtotime(date("Y-m-d"))));
        // $arrPembelian_terakhir_1[$barang_id] = $dtime;
        // $arrJml_hari_penjualan_1[$barang_id] = $jml_hari_penjualan;
        $str_metode = "dari rata-rata penjualan selama $jml_hari_penjualan hari terakhir === $dtime";
        // cekHere("$str_metode");
        //endregion

        $this->db->limit(10);
        $tmpPr = $pr->lookupAll();
        $produks = $tmpPr->result();
        // showLast_query("kuning");
        // arrPrint($produks);

        $tmpStok = $bi->getStokNowAll();
        //         showLast_query("here");
        //         arrPrintWebs($tmpStok);
        // matiHere();
        $dtimeNow = dtimeNow('Y-m') . "-01";
        // $dtimeNow = dtimeNow('Y-m-d');
        $periode_X = ($periode) > 0 ? ($periode) * -1 : 0;
        $stop_date = date('Y-m-d', strtotime($dtimeNow . ' -1 month'));
        $prev_date = date('Y-m-d', strtotime($dtimeNow . " " . $periode_X . ' month'));
        $condites = array(
            // "th" => "2020",
            "tanggal >=" => $prev_date,
            "tanggal <" => $dtimeNow,
            // "bl >=" => "12",
        );
        // $rp->setDebug(true);
        $rp->setJenis("penjualan");
        $rp->setPeriode("bulanan");
        $rp->setCondites($condites);
        $rp->setOrder("tanggal asc");
        $tmpPenjualan = $rp->lookupPenjualanProdukAll();

        foreach ($tmpPenjualan->result() as $pnjSpecs) {
            $th = $pnjSpecs->th;
            $bl = $pnjSpecs->bl;
            $subject_id = $pnjSpecs->subject_id;
            $datas['bl'] = $bl;
            $datas['unit_ot'] = $pnjSpecs->unit_ot;
            $datas['unit_in'] = $pnjSpecs->unit_in;
            $datas['unit_af'] = $pnjSpecs->unit_af;

            $pnjualans[$th][$bl][$subject_id] = $datas;
        }
        // arrPrint($tmpPenjualan->result());
        // arrPrint($pnjualans);


        // matiHere();
        $data = array(
            "mode" => "viewMonthly",
            "title" => "monthly sales",
            "subTitle" => "",
            "periode" => $periode,
            "indeks" => $indeks,
            "buffer" => $buffer,
            "leadTime" => $leadTime,
            "navigasi" => $arrBi,
            // "navigasiAttr"     => $arrBiAttr,
            "produks" => $produks,
            "stokNow" => $tmpStok["sums"],
            "penjualanBulanan" => $pnjualans,
            // "returnPenjualan" => $tmpReturnPenjualan["sums"],
        );
        $this->load->view("bi", $data);
    }

    public function viewProdukSales()
    {
        // arrPrintWebs($this->uri->segment_array());
        $source_data = "report"; // report //// mutasi
        $setPeriode = "bulanan"; // bulanan//// harian
        $modes = array(
            "bulanan" => "viewMonthly",
            "harian" => "viewDaily",
        );
        $this->load->config("heBi");
        $this->load->model("Mdls/MdlReportSql");
        $this->load->model("Mdls/MdlBi");
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $rp = new MdlReportSql();
        $class = get_class();
        $pr = new MdlProduk();
        $bi = new MdlBi();
        $cabang_id = "1";

        /* ------------------------------------------------------------------------------------
         * defauld nilai navigasi diatur dr confiq ini yuaaaa
         * ------------------------------------------------------------------------------------*/
        $heBis = $this->config->item("heBi");


        $arrBiAttr = $arrBies = $heBis['pembelian']['produk']['setting'];
        // $arrBies = array(
        //     "periode_4"  => array(
        //         "label"   => "stock sett (m)",
        //         "minimal" => "1",
        //         "default" => "6",
        //         // "formula" => "9",
        //     ),
        // );
        // arrPrintKuning($arrBies);
        foreach ($arrBies as $biKey => $arrBY) {
            $biValues = isset($_SESSION[$class][$biKey]) ? $_SESSION[$class][$biKey] : (isset($arrBY['default']) ? $arrBY['default'] : 0);
            $arrBi[$biKey] = $biValues;
            $$biKey = $biValues;

            // cekHijau("$biKey = $biValues");
        }
        // $periode = 1;
        // cekMerah("$periode");
        $cheaders = $heBis['pembelian']['produk']['headerField'];
        // $content_note = "";
        $notes = array();
        $heads_2 = array();
        // arrPrint($cheaders);
        foreach ($cheaders as $cpKey => $cpValues) {
            $legenda = $cpValues['label'];
            if (isset($cpValues['formula'])) {
                $lNote = isset($cpValues['formula']) ? $cpValues['formula'] : "-";

                $notes[$legenda] = $lNote;
            }

            // if (isset($cpValues['label'])) {
            $heads_2[] = $legenda;
            // }
        }
        // arrPrintWebs($arrBi);
        // arrPrintWebs($heads_2);
        // matiHere();
        // $refSegmen2 = strlen(url_referer()) > 3 ? explode("/", url_referer())[4] : "";

        // $jml_hari_penjualan = $periode * 30; // bulan
        $jml_hari_penjualan = $periode; // hari
        $_SESSION[$class] = $arrBi;

        //region jml hari pembelian
        $dtime = $kemarin = date('Y-m-d', strtotime("-1 day", strtotime(date("Y-m-d"))));
        //endregion

        $dtimeNow = dtimeNow('Y-m-d');
        $periode_X = ($periode) > 0 ? ($periode) * -1 : 0;

        // cekMerah("periode::$periode || jml hari penjualan:$jml_hari_penjualan || periodeX:$periode_X || kemarin:$kemarin");

        $stop_date = date('Y-m-d', strtotime($dtimeNow . ' -1 day'));
        $prev_date = date('Y-m-d', strtotime($dtimeNow . " " . $periode_X . ' day'));
        $condites = array(
            // "th" => "2020",
            "tanggal >=" => $prev_date,
            "tanggal <" => $dtimeNow,
            // "bl >=" => "12",
        );
        $live_condites = array(
            // "th" => "2020",
            "date(dtime) >=" => $prev_date,
            "date(dtime) <" => $dtimeNow,
            // "sum(qty_kredit) as 'sum_qty_k',extern_id"
        );

        //---- cek kiriman jenisTR dari pembelian, uri segment 3
        // arrPrint($this->uri->segment_array());
        // matiHEre();
        // cekMerah($this->uri->segment(3));
        if (NULL != ($this->uri->segment(4))) {
            $mode = "viewBiPurchasing";
            $jenisTr = $this->uri->segment(4);
            $cCode = "_TR_" . $jenisTr;
            $vendorID = isset($_GET['selID']) ? $_GET['selID'] : (isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : 0);
            // $vendorID = 18; // indomarco
            // $vendorID = 3; // wings
            if ($vendorID > 0) {

                $pps = New MdlProdukPerSupplier();
                $pps->addFilter("suppliers_id='$vendorID'");
                $pps->setSortBy(array("mode" => "ASC", "kolom" => "produk_id"));
                $produksVendor = $pps->lookupAll()->result();

                //            showLast_query("biru");
                //            cekHitam(sizeof($produksVendor));
                $pIDs = array();
                $produks = array();
                if (sizeof($produksVendor) > 0) {
                    foreach ($produksVendor as $spec) {
                        $pIDs[$spec->produk_id] = $spec->produk_id;
                    }

                    $pr->addFilter("id in ('" . implode("','", $pIDs) . "')");
                    $tmpPr = $pr->lookupAll();
                    // showLast_query("kuning");

                    $produks = $tmpPr->result();
                }
            } else {
                $produks = array();
                $pIDs = array();
            }

            //----bi->getStokNowAll()
            if (sizeof($pIDs) > 0) {
                $this->db->where_in("produk_id", $pIDs);
            }


        } else {
            $mode = $modes[$setPeriode];

            if (isset($_GET['limit'])) {
                $this->db->limit($_GET['limit']);
            } else {
                $this->db->limit(20);
            }


            $tmpPr = $pr->lookupAll();

            $produks = $tmpPr->result();

        }


        // arrPrint($produks);
        // matiHEre();
        // nyari omset dalam periode tertentu
        if (sizeof($produks) > 0) {
            /* -------------------------------------------
             * stok now
             * -------------------------------------------*/
            $bi->setTokoID(my_toko_id());
            $tmpStok = $bi->getStokNowAll();
            // showLast_query("hijau");

            // matiHEre(__LINE__);
            // $rp->setDebug(true);
            /* -------------------------------------------
             * omset penjualan dalam x
             * -------------------------------------------*/
            // $rp->setJenis("penjualan");
            // $rp->setPeriode("bulanan");
            // $rp->setCondites($condites);
            // $rp->setOrder("tanggal asc");

            $koloms = array(
                "produk_id",
                "extern_id",
                // "sum(qty_kredit) as 'sum_qty_kredit'",
                "sum(qty_kredit) as 'unit_af'",
                "sum(kredit) as 'sum_kredit'",
                "cabang_id",
                "date(dtime) as 'tgl'",
                "month(dtime) as 'bln'",
                "year(dtime) as 'thn'",
            );
            $this->db->group_by("produk_id");
            $this->db->select($koloms);
            $this->db->where($live_condites);
            $tmpPenjualan = $rp->callPenjualan()->result();
            // showLast_query("hitam");
            // cekKuning(count($tmpPenjualan));
            // arrPrint($tmpPenjualan);
            //        mati_disini();

            $pnjualans = array();
            foreach ($tmpPenjualan as $pnjSpecs) {
                $th = $pnjSpecs->thn;
                $bl = $pnjSpecs->bln;
                // $subject_id = $pnjSpecs->subject_id;
                $subject_id = $pnjSpecs->produk_id;
                $datas['bl'] = $bl;
                $datas['unit_ot'] = 0;
                $datas['unit_in'] = 1;
                // $datas['unit_ot'] = $pnjSpecs->unit_ot;
                // $datas['unit_in'] = $pnjSpecs->unit_in;
                $datas['unit_af'] = $pnjSpecs->unit_af;

                $pnjualans[$th][$bl][$subject_id] = $datas;
            }

        }


        // arrPrintWebs($_SESSION[$class]);
        // arrPrint($this->uri->segment_array());
        // arrPrint($pnjualans);
        // arrPrint($_GET);

        /* -------------------------------------------------------------------------------------------------------------
         * logic button dari modul atau reguler
         * supaya shopingcart bisa ngerload ketika button diklik, harus bersih dari mode debuger index maupun he_misc
         * -------------------------------------------------------------------------------------------------------------
         */
        if ($this->uri->segment(4) != null) {
            $btnShopCrt = base_url() . $this->uri->segment(5) . "/_processSelectProduct/multiSelectBi/$jenisTr";
        } else {
            $btnShopCrt = "Selectors/_processSelectProduct/multiSelectBi/$jenisTr/";
        }

        // cekLime($btnShopCrt);
        //         cekHere("$mode || $jenisTr");
        $data = array(

            "mode" => $mode,
            "title" => "calc stok",
            "subTitle" => $setPeriode,
            "periode" => isset($periode) ? $periode : 0,
            "periode_1" => isset($periode_1) ? $periode_1 : 0,
            "periode_2" => isset($periode_2) ? $periode_2 : 0,
            "periode_3" => isset($periode_3) ? $periode_3 : 0,
            // "dateDiffDay"      => $date_diff->days,
            "dateDiffDay" => "",
            "indeks" => isset($indeks) ? $indeks : 0,
            "leadTime" => isset($leadTime) ? $leadTime : 0,
            "limitTime" => isset($limitTime) ? $limitTime : 0, // buffer
            "bufferTime" => isset($limitTime) ? $limitTime : 0, // buffer
            "limit" => isset($limit) ? $limit : 0, // buffer
            "buffer" => isset($limit) ? $limit : 0, // buffer
            "moqTime" => isset($moqTime) ? $moqTime : 0,
            "navigasi" => $arrBi,
            "navigasiAttr" => $arrBiAttr,
            "notes" => $notes,
            "heads_2" => $heads_2,
            "produks" => $produks,
            "stokNow" => isset($tmpStok["sums"]) ? $tmpStok["sums"] : array(),
            "penjualanBulanan" => isset($pnjualans) ? $pnjualans : array(),
            // "returnPenjualan" => $tmpReturnPenjualan["sums"],
            // "btnToShoppingCart" => isset($jenisTr) ? "Selectors/_processSelectProduct/multiSelectBi/$jenisTr" : NULL,
            "btnToShoppingCart" => isset($jenisTr) ? $btnShopCrt : NULL,
            "jenisTr" => isset($jenisTr) ? $jenisTr : NULL,
            "vendorNama" => isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] : NULL,
            "vendorId" => isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : NULL,
        );
        $this->load->view("bi", $data);
    }

    /* -------------------------------------------------------------------
     * dipasang pada pembelian dibawaH shopingcart
     * -------------------------------------------------------------------*/
    public function viewProdukSales_2()
    {
        // arrPrintWebs($this->uri->segment_array());
        $source_data = "report"; // report //// mutasi
        $setPeriode = "bulanan"; // bulanan//// harian
        $modes = array(
            "bulanan" => "viewMonthly",
            "harian" => "viewDaily",
        );
        $this->load->config("heBi");
        $this->load->model("Mdls/MdlReportSql");
        $this->load->model("Mdls/MdlBi");
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $rp = new MdlReportSql();
        $class = get_class();
        $pr = new MdlProduk();
        $bi = new MdlBi();
        $cabang_id = "1";

        /* ------------------------------------------------------------------------------------
         * defauld nilai navigasi diatur dr confiq ini yuaaaa
         * ------------------------------------------------------------------------------------*/
        $heBis = $this->config->item("heBi");

        // arrPrintHijau($_SESSION[$class]);
        $arrBiAttr = $arrBies = $heBis['pembelian']['produk']['setting'];
        $arrBies_2 = array(
            "periode_1" => array(
                "label" => "stock sett (m)",
                "minimal" => "1",
                "default" => "7",
                // "formula" => "9",
            ),
            "periode_2" => array(
                "label" => "stock sett (m)",
                "minimal" => "1",
                "default" => "14",
                // "formula" => "9",
            ),
            "periode_3" => array(
                "label" => "stock sett (m)",
                "minimal" => "1",
                "default" => "30",
                // "formula" => "9",
            ),
            "periode_4" => array(
                "label" => "stock sett (m)",
                "minimal" => "1",
                "default" => "100",
                // "formula" => "9",
            ),
        );
        // arrPrintKuning($arrBies+$arrBies_2);
        foreach (($arrBies + $arrBies_2) as $biKey => $arrBY) {
            $biValues = isset($_SESSION[$class][$biKey]) ? $_SESSION[$class][$biKey] : (isset($arrBY['default']) ? $arrBY['default'] : 0);
            $arrBi[$biKey] = $biValues;
            $$biKey = $biValues;

            // cekHijau("$biKey = $biValues");
        }
        // $periode = 1;
        // cekMerah("$periode");
        $cheaders = $heBis['pembelian']['produk']['headerField'];
        // $content_note = "";
        $notes = array();
        $heads_2 = array();
        // arrPrint($cheaders);
        foreach ($cheaders as $cpKey => $cpValues) {
            $legenda = $cpValues['label'];
            if (isset($cpValues['formula'])) {
                $lNote = isset($cpValues['formula']) ? $cpValues['formula'] : "-";

                $notes[$legenda] = $lNote;
            }

            // if (isset($cpValues['label'])) {
            $heads_2[] = $legenda;
            // }
        }
        // arrPrintWebs($arrBi);
        // arrPrintWebs($heads_2);
        // matiHere();
        // $refSegmen2 = strlen(url_referer()) > 3 ? explode("/", url_referer())[4] : "";

        // $jml_hari_penjualan = $periode * 30; // bulan
        $jml_hari_penjualan = $periode; // hari
        $_SESSION[$class] = $arrBi;

        //region jml hari pembelian
        $dtime = $kemarin = date('Y-m-d', strtotime("-1 day", strtotime(date("Y-m-d"))));
        //endregion

        $dtimeNow = dtimeNow('Y-m-d');
        $periode_X = ($periode) > 0 ? ($periode) * -1 : 0;

        // cekMerah("periode::$periode || jml hari penjualan:$jml_hari_penjualan || periodeX:$periode_X || kemarin:$kemarin");

        $stop_date = date('Y-m-d', strtotime($dtimeNow . ' -1 day'));
        $prev_date = date('Y-m-d', strtotime($dtimeNow . " " . $periode_X . ' day'));
        $condites = array(
            // "th" => "2020",
            "tanggal >=" => $prev_date,
            "tanggal <" => $dtimeNow,
            // "bl >=" => "12",
        );
        $live_condites = array(
            // "th" => "2020",
            "date(dtime) >=" => $prev_date,
            "date(dtime) <" => $dtimeNow,
            // "sum(qty_kredit) as 'sum_qty_k',extern_id"
        );

        //---- cek kiriman jenisTR dari pembelian, uri segment 4
        // arrPrint($this->uri->segment_array());
        // matiHEre();
        // cekMerah($this->uri->segment(3));
        if (NULL != ($this->uri->segment(4))) {
            $mode = "viewBiPurchasing_2";
            $jenisTr = $this->uri->segment(4);
            $cCode = "_TR_" . $jenisTr;
            $vendorID = isset($_GET['selID']) ? $_GET['selID'] : (isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : 0);
            // $vendorID = 18; // indomarco
            // $vendorID = 3; // wings
            if ($vendorID > 0) {

                $pps = New MdlProdukPerSupplier();
                $pps->addFilter("suppliers_id='$vendorID'");
                $pps->setSortBy(array("mode" => "ASC", "kolom" => "produk_id"));
                $produksVendor = $pps->lookupAll()->result();

                //            showLast_query("biru");
                //            cekHitam(sizeof($produksVendor));
                $pIDs = array();
                $produks = array();
                if (sizeof($produksVendor) > 0) {
                    foreach ($produksVendor as $spec) {
                        $pIDs[$spec->produk_id] = $spec->produk_id;
                    }

                    $pr->addFilter("id in ('" . implode("','", $pIDs) . "')");
                    $tmpPr = $pr->lookupAll();
                    // showLast_query("kuning");

                    $produks = $tmpPr->result();
                }
            } else {
                $produks = array();
                $pIDs = array();
            }

            //----bi->getStokNowAll()
            if (sizeof($pIDs) > 0) {
                $this->db->where_in("produk_id", $pIDs);
            }


        } else {
            $mode = $modes[$setPeriode];

            if (isset($_GET['limit'])) {
                $this->db->limit($_GET['limit']);
            } else {
                $this->db->limit(20);
            }


            $tmpPr = $pr->lookupAll();

            $produks = $tmpPr->result();

        }


        // arrPrint($produks);
        // matiHEre();
        // nyari omset dalam periode tertentu
        if (sizeof($produks) > 0) {
            /* -------------------------------------------
             * stok now
             * -------------------------------------------*/
            $bi->setTokoID(my_toko_id());
            $tmpStok = $bi->getStokNowAll();
            // showLast_query("hijau");

            // matiHEre(__LINE__);
            // $rp->setDebug(true);

            /* -------------------------------------------
             * omset penjualan dalam x
             * -------------------------------------------*/
            // $rp->setJenis("penjualan");
            // $rp->setPeriode("bulanan");
            // $rp->setCondites($condites);
            // $rp->setOrder("tanggal asc");

            $koloms = array(
                "produk_id",
                "extern_id",
                // "sum(qty_kredit) as 'sum_qty_kredit'",
                "sum(qty_kredit) as 'unit_af'",
                "sum(kredit) as 'sum_kredit'",
                "cabang_id",
                "date(dtime) as 'tgl'",
                "month(dtime) as 'bln'",
                "year(dtime) as 'thn'",
            );
            $this->db->group_by("produk_id");
            $this->db->select($koloms);
            // $live_condites['produk_id'] = "54958";
            $this->db->where($live_condites);
            $tmpPenjualan = $rp->callPenjualan()->result();
            // showLast_query("hitam");
            // cekKuning(count($t2mpPenjualan));
            // arrPrint($tmpPenjualan);
            // mati_disini();
            $omset_xd = $this->callOmsetHari($tmpPenjualan);
            // arrPrintKuning($omset_xd);

            /* -------------------------------------------
             * omset penjualan dalam 7
             * -------------------------------------------*/
            $dtime_7 = date('Y-m-d', strtotime($dtimeNow . " " . $periode_1 * -1 . ' day'));

            $this->db->group_by("produk_id");
            $this->db->select($koloms);
            // $live_condites['produk_id'] = "54958";
            $live_condites['date(dtime) >='] = $dtime_7;
            $this->db->where($live_condites);
            $tmpPenjualan = $rp->callPenjualan()->result();
            // showLast_query("merah");
            // arrPrintPink($tmpPenjualan);
            $omset_7d = $this->callOmsetHari($tmpPenjualan, $periode_1);
            // arrPrintPink($omset_7d);
            /* -------------------------------------------
             * omset penjualan dalam 14
             * -------------------------------------------*/
            $dtime_14 = date('Y-m-d', strtotime($dtimeNow . " " . $periode_2 * -1 . ' day'));

            $this->db->group_by("produk_id");
            $this->db->select($koloms);
            // $live_condites['produk_id'] = "54958";
            $live_condites['date(dtime) >='] = $dtime_14;
            $this->db->where($live_condites);
            $tmpPenjualan = $rp->callPenjualan()->result();
            // showLast_query("hijau");
            // arrPrintHijau($tmpPenjualan);
            $omset_14d = $this->callOmsetHari($tmpPenjualan, $periode_2);
            /* -------------------------------------------
             * omset penjualan dalam 30
             * -------------------------------------------*/
            $dtime_30 = date('Y-m-d', strtotime($dtimeNow . " " . $periode_3 * -1 . ' day'));

            $this->db->group_by("produk_id");
            $this->db->select($koloms);
            // $live_condites['produk_id'] = "54958";
            $live_condites['date(dtime) >='] = $dtime_30;
            $this->db->where($live_condites);
            $tmpPenjualan = $rp->callPenjualan()->result();
            // showLast_query("merah");
            // arrPrintPink($tmpPenjualan);
            $omset_30d = $this->callOmsetHari($tmpPenjualan, $periode_3);
            /* -------------------------------------------
             * omset penjualan dalam 100
             * -------------------------------------------*/
            $dtime_100 = date('Y-m-d', strtotime($dtimeNow . " " . $periode_4 * -1 . ' day'));

            $this->db->group_by("produk_id");
            $this->db->select($koloms);
            // $live_condites['produk_id'] = "54958";
            $live_condites['date(dtime) >='] = $dtime_100;
            $this->db->where($live_condites);
            $tmpPenjualan = $rp->callPenjualan()->result();
            // showLast_query("hijau");
            // arrPrintHijau($tmpPenjualan);
            $omset_100d = $this->callOmsetHari($tmpPenjualan, $periode_4);
        }

        // matiHere(__LINE__);
        // arrPrintWebs($_SESSION[$class]);
        // arrPrint($this->uri->segment_array());
        // arrPrint($pnjualans);
        // arrPrint($_GET);

        /* -------------------------------------------------------------------------------------------------------------
         * logic button dari modul atau reguler
         * supaya shopingcart bisa ngerload ketika button diklik, harus bersih dari mode debuger index maupun he_misc
         * -------------------------------------------------------------------------------------------------------------
         */
        if ($this->uri->segment(4) != null) {
            $btnShopCrt = base_url() . $this->uri->segment(5) . "/_processSelectProduct/multiSelectBi/$jenisTr";
        } else {
            $btnShopCrt = "Selectors/_processSelectProduct/multiSelectBi/$jenisTr/";
        }

        // cekLime($btnShopCrt);
        // cekLime($mode);
        // cekBiru($_GET);
        //         cekHere("$mode || $jenisTr");
        $data = array(

            "mode" => $mode, // viewBiPurchasing_2
            "title" => "calc stok",
            "subTitle" => $setPeriode,
            "periode" => isset($periode) ? $periode : 0,
            "periode_1" => isset($periode_1) ? $periode_1 : 0,
            "periode_2" => isset($periode_2) ? $periode_2 : 0,
            "periode_3" => isset($periode_3) ? $periode_3 : 0,
            // "dateDiffDay"      => $date_diff->days,
            "dateDiffDay" => "",
            "indeks" => isset($indeks) ? $indeks : 0,
            "leadTime" => isset($leadTime) ? $leadTime : 0,
            "limitTime" => isset($limitTime) ? $limitTime : 0, // buffer
            "bufferTime" => isset($limitTime) ? $limitTime : 0, // buffer
            "limit" => isset($limit) ? $limit : 0, // buffer
            "buffer" => isset($limit) ? $limit : 0, // buffer
            "moqTime" => isset($moqTime) ? $moqTime : 0,
            "navigasi" => $arrBi,
            "navigasiAttr" => $arrBiAttr,
            "notes" => $notes,
            "heads_2" => $heads_2,
            "produks" => $produks,
            "stokNow" => isset($tmpStok["sums"]) ? $tmpStok["sums"] : array(),
            "penjualanBulanan" => isset($pnjualans) ? $pnjualans : array(),
            "omset_xd" => isset($omset_xd) ? $omset_xd : array(),
            "omset_1" => isset($omset_7d) ? $omset_7d : array(),
            "omset_2" => isset($omset_14d) ? $omset_14d : array(),
            "omset_3" => isset($omset_30d) ? $omset_30d : array(),
            "omset_4" => isset($omset_100d) ? $omset_100d : array(),
            // "returnPenjualan" => $tmpReturnPenjualan["sums"],
            // "btnToShoppingCart" => isset($jenisTr) ? "Selectors/_processSelectProduct/multiSelectBi/$jenisTr" : NULL,
            "btnToShoppingCart" => isset($jenisTr) ? $btnShopCrt : NULL,
            "jenisTr" => isset($jenisTr) ? $jenisTr : NULL,
            "vendorNama" => isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] : NULL,
            "vendorId" => isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : NULL,
        );
        $this->load->view("bi", $data);
    }

    private function callOmsetHari($tmpPenjualan, $periode = "")
    {
        $pnjualans = array();
        $omset_xd = array();
        $average_xd = array();
        foreach ($tmpPenjualan as $pnjSpecs) {
            $th = $pnjSpecs->thn;
            $bl = $pnjSpecs->bln;
            // $subject_id = $pnjSpecs->subject_id;
            $subject_id = $pnjSpecs->produk_id;
            $datas['bl'] = $bl;
            $datas['unit_ot'] = 0;
            $datas['unit_in'] = 1;
            // $datas['unit_ot'] = $pnjSpecs->unit_ot;
            // $datas['unit_in'] = $pnjSpecs->unit_in;
            $datas['unit_af'] = $pnjSpecs->unit_af;

            $pnjualans[$th][$bl][$subject_id] = $datas;

            $omset_xd[$subject_id] = $pnjSpecs->unit_af;
            $average_xd[$subject_id] = $periode > 0 ? ($pnjSpecs->unit_af / $periode) : 0;
        }

        if ($periode == "") {
            return $omset_xd;
        } else {
            return $average_xd;

        }
    }

    private function callOmsetHariCabang($tmpPenjualan, $periode = "")
    {
        $pnjualans = array();
        $omset_xd = array();
        $average_xd = array();
        foreach ($tmpPenjualan as $pnjSpecs) {
            $th = $pnjSpecs->thn;
            $bl = $pnjSpecs->bln;
            $cb_id = $pnjSpecs->cabang_id;
            // $subject_id = $pnjSpecs->subject_id;
            $subject_id = $pnjSpecs->produk_id;
            $datas['bl'] = $bl;
            $datas['unit_ot'] = 0;
            $datas['unit_in'] = 1;
            // $datas['unit_ot'] = $pnjSpecs->unit_ot;
            // $datas['unit_in'] = $pnjSpecs->unit_in;
            $datas['unit_af'] = $pnjSpecs->unit_af;

            $pnjualans[$th][$bl][$subject_id] = $datas;

            $omset_xd[$cb_id][$subject_id] = $pnjSpecs->unit_af;
//            $omset_xd[$cb_id][$subject_id] = 50;
            $average_xd[$subject_id] = $periode > 0 ? ($pnjSpecs->unit_af / $periode) : 0;
        }

        if ($periode == "") {
            return $omset_xd;
        } else {
            return $average_xd;

        }
    }

    // ===================================== //

    public function viewGraphSales()
    {
        $this->load->model("Mdls/MdlReportSql");
        $this->load->model("Mdls/MdlProduk");
        $rp = new MdlReportSql();
        $pr = new MdlProduk();
        $class = get_class();

        //region navigasi
        // arrPrint($_SERVER);
        // cekPink(url_referer());
        if (strlen(url_referer()) > 0) {

            $refSegmen2 = explode("/", url_referer())[4];
        }
        // cekHere(url_referer() . " methode" . __FUNCTION__ . " segmen::" . $this->uri->segment(2) . " s2::" . $refSegmen2);
        if ($this->uri->segment(2) != $refSegmen2) {
            if (isset($_SESSION[$class])) {
                unset($_SESSION[$class]);
            }
        }
        $periode = isset($_SESSION[$class]['periode']) ? $_SESSION[$class]['periode'] : 6;
        $indeks = isset($_SESSION[$class]['indeks']) ? $_SESSION[$class]['indeks'] : 100;
        $buffer = isset($_SESSION[$class]['buffer']) ? $_SESSION[$class]['buffer'] : 1;
        $leadTime = isset($_SESSION[$class]['leadTime']) ? $_SESSION[$class]['leadTime'] : 100;
        // $jml_hari_penjualan = $periode * 30;
        // $_SESSION[$class] = array();
        $arrBi = array();
        // if (!isset($_SESSION[$class])) {
        // $arrBi["indeks"] = $indeks;
        // $arrBi["buffer"] = $buffer;
        $arrBi["periode"] = $periode;
        // $arrBi["leadTime"] = $leadTime;
        $_SESSION[$class] = $arrBi;
        // cekHere("masukin array");
        // }
        // else {
        //     $arrBi = $_SESSION[$class];
        // }
        $arrBiAttr["indeks"] = array(
            "label" => "index",
            "minimal" => "100",
        );
        $arrBiAttr["buffer"] = array(
            "label" => "buffer",
            "minimal" => "1",
        );
        $arrBiAttr["periode"] = array(
            "label" => "show month",
            "minimal" => "1",
        );
        $arrBiAttr["leadTime"] = array(
            "label" => "index lead time",
            "minimal" => "100",
        );
        //endregion

        // $tmpPr = $pr->lookupAll();
        // $tmpPr = $pr->callProdukFire();
        // $produks = $tmpPr->result();
        // $fireProduks = $tmpPr['fire'];
        // $nonFireProduks = $tmpPr['nonFire'];
        // $produks = $tmpPr['all'];
        // $nonFireProduks = array();
        // $fireProduks = array();
        // foreach ($fireProduks as $produkSrc) {
        //     $fireProdukIds[] = $produkSrc->id;
        // }
        // foreach ($nonFireProduks as $produkSrc) {
        //     $nonFireProduksIds[] = $produkSrc->id;
        // }

        // arrPrint($fireProdukIds);
        // arrPrint($fireProduks);
        // arrPrint(sizeof($fireProduks));
        // arrPrint(sizeof($nonFireProduks));
        // arrPrint(sizeof($produks));
        // matiHere();

        // $periode = 1;
        // $kolomProdukDatas = array(
        //   "id"
        // );
        $produkFields = $pr->getFields();
        $kolomProdukDatas = array();
        foreach ($produkFields as $produkField) {
            $kolomProdukDatas[] = $produkField["kolom"];
        }

        $this->db->select($kolomProdukDatas);
        $produks_0 = $pr->lookupAll()->result();
        $produks = array();
        foreach ($produks_0 as $item) {
            $produks[$item->id] = $item;
        }
        // showLast_query("hijau");
        // arrPrintPink($produks);

        $firstDtimeNow = dtimeNow('Y-m') . "-01";
        $dtimeNow = dtimeNow('Y-m-d');
        // $dtimeNow = dtimeNow('Y-m-d');
        $periode_X = ($periode) > 0 ? ($periode) * -1 : 0;
        $stop_date = date('Y-m-d', strtotime($firstDtimeNow . ' -1 month'));
        $prev_date = date('Y-m-d', strtotime($firstDtimeNow . " " . $periode_X . ' month'));
        $condites = array(
            // "th" => "2020",
            "date(dtime) >=" => $prev_date,
            "date(dtime) <=" => $dtimeNow,
            // "bl >=" => "12",
        );

        $condite_cabang = array(
            "cabang_id" => my_cabang_id(),
        );
        // cekOrange("$periode $periode_X");
        // arrPrintWebs($condites);
        //         $rp->setDebug(true);
        $rp->setJenis("penjualan");
        $rp->setPeriode("bulanan");
        $rp->setCondites($condites);
        $rp->setOrder("tanggal asc");

        $this->db->where($condites + $condite_cabang);
        $tmpPembelian = $rp->callPembelianAllM();
        // showLast_query("pink");
        // arrPrint($tmpPembelian);
        /* -----------------------------------
         * manipulasi data karena bulan 11 2020 belum aa penjualan
         * --------------------------------*/
        $tambahan = 0;
        foreach ($tmpPembelian as $item) {
            if ($item->th == "2020" && $item->bl == "11") {
                $tambahan = 1;
                // cekHere();
            }
        }
        // matiHere(__LINE__ . " stop");

        // $condites = array(
        //     // "date(dtime) >=" => "2021-03-01",
        //     "date(dtime) >=" => "2021-04-01",
        //     "date(dtime) <=" => $dtimeNow,
        // );
        $this->db->where($condites);
        $tmpPenjualan = $rp->callPenjualanAllM();
        if ($tambahan == 1) {
            $tmpPenjualan[sizeof($tmpPenjualan)] = (object)array(
                "unit_af" => 0,
                "nilai_af" => 0,
                "bl" => 11,
                "th" => 2020,
            );
        }
        // cekHijau($tambahan);
        // showLast_query("lime");
        // arrPrintPink($tmpPenjualan);
        // showLast_query("lime");

        $this->db->where($condites);
        $tmpPenjualanDaily = $rp->callPenjualanAllD();
        // cekHijau($tambahan);
        // showLast_query("lime");
        // arrPrintPink($tmpPenjualanDaily);
        // arrPrintPink($kolomProdukDatas);
        // showLast_query("lime");
        $this->db->where($condites);
        $tmpPenjualanProduk = $rp->callPenjualanProdukAll();
        // showLast_query("lime");
        // arrPrintPink($tmpPenjualanProduk);

        $produkYgDiJual = array();
        foreach ($tmpPenjualanProduk as $item) {
            $items = (array)$item;
            $produk_id = $item->subject_id;

            $produk_speks = array();
            foreach ($kolomProdukDatas as $nama_kolom) {
                // cekBiru($produks[$produk_id]);
                // cekBiru($produk_id);
                $produk_speks[$nama_kolom] = isset($produks[$produk_id]) ? $produks[$produk_id]->$nama_kolom : "-";
            }

            $produkYgDiJual[$produk_id] = (object)($items + $produk_speks);
            // $produkYgDiJual[$produk_id] = $produk_nama;
        }
        $kolomPenjualanProduk = array(
            "no" => array(
                "label" => "no",
                "attr" => "class='text-right'",
                "attr_head" => "class='text-center'",
            ),
            "kode" => array(
                "label" => "kode",
                "attr" => "class='text-center'",
                "attr_head" => "class='text-center'",
            ),
            "nama" => array(
                "label" => "produk",
                // "attr"      => "class='text-red'",
                "attr_head" => "class='text-center'",
            ),
            "no_part" => array(
                "label" => "no part",
                // "attr"      => "class='text-red'",
                "attr_head" => "class='text-center'",
            ),
            "unit_af" => array(
                "label" => "qty",
                "attr" => "class='text-right'",
                "attr_head" => "class='text-center'",
                "format" => "formatField_he_format",
                "summary" => true,
            ),
            "nilai_af" => array(
                "label" => "nilai penjualan",
                "attr" => "class='text-right'",
                "format" => "formatField_he_format",
                "attr_head" => "class='text-center'",
                "summary" => true,
            ),

        );
        // showLast_query("kuning");
        // cekHijau($produk_speks);
        // cekBiru($produkYgDiJual);
        $kolomDatas = array(
            "bl",
            "nilai_ot",
            "nilai_in",
            "nilai_af",
            // "quarter",
            // "counter",
        );
        $kolomShows = array(
            "nilai_af" => "penjualan"
        );
        // arrPrint($tmpPenjualan);
        // arrPrintPink($tmpPenjualanProduk);
        // matiDisini(__LINE__);
        $pnjualans = array();
        if ($tambahan == 1) {
            $pnjualans["2020"]["11"] = array();
        }
        foreach ($tmpPenjualan as $pnjSpecs) {
            $th = $pnjSpecs->th;
            $bl = $pnjSpecs->bl;
            // $qrt = $pnjSpecs->quarter;

            foreach ($kolomDatas as $kolomData) {
                $datas[$kolomData] = isset($pnjSpecs->$kolomData) ? $pnjSpecs->$kolomData : 0;
            }

            $pnjualans[$th][$bl] = $datas;

            // $qrtPenjualans[$th]['bulan'] = $bl;
            // $qrtDatas[$th][$qrt]['bl'] = $bl;
            // if (!isset($qrtPenjualans[$th][$qrt])) {
            //     $qrtPenjualans[$th][$qrt] = 0;
            // }
            // $qrtPenjualans[$th][$qrt] += ($pnjSpecs->nilai_af / 3);
        }
        // arrPrint($qrtPenjualans);
        // arrPrint($pnjualans);
        // arrPrint($qrtDatas);
        // matiDisini(__LINE__);
        foreach ($tmpPenjualanProduk as $item) {
            $th = $item->th;
            $bl = $item->bl;
            // if (in_array($item->subject_id, $fireProdukIds)) {
            //     if (!isset($nilaiPenjualanFireNet[$th][$bl])) {
            //         $nilaiPenjualanFireNet[$th][$bl] = 0;
            //     }
            //     $nilaiPenjualanFireNet[$th][$bl] += $item->nilai_af;
            // }
            // else {
            if (!isset($nilaiPenjualanNonFireNet[$th][$bl])) {
                $nilaiPenjualanNonFireNet[$th][$bl] = 0;
            }
            $nilaiPenjualanNonFireNet[$th][$bl] += $item->nilai_af;
            // }

            if (!isset($nilaiPenjualan[$th][$bl])) {
                $nilaiPenjualan[$th][$bl] = 0;
            }
            $nilaiPenjualan[$th][$bl] += $item->nilai_af;
        }
        // arrPrint($nilaiPenjualanFireNet);
        // arrPrint($nilaiPenjualanNonFireNet);
        // arrPrintWebs($nilaiPenjualan);
        // arrPrint($tmpPenjualanProduk->result());
        $pembelianNet = array();
        foreach ($tmpPembelian as $item) {
            $pembelianNet[$item->th][$item->bl] = $item->nilai_af;
        }
        $nilaiPenjualanFireNet = "";

        $pnjualanDailies = array();
        foreach ($tmpPenjualanDaily as $pnjSpecs) {
            $tg = $pnjSpecs->tg;
            // $qrt = $pnjSpecs->quarter;

            foreach ($kolomDatas as $kolomData) {
                $datas[$kolomData] = isset($pnjSpecs->$kolomData) ? $pnjSpecs->$kolomData : 0;
            }

            $pnjualanDailies[$tg] = $datas;

            // $qrtPenjualans[$th]['bulan'] = $bl;
            // $qrtDatas[$th][$qrt]['bl'] = $bl;
            // if (!isset($qrtPenjualans[$th][$qrt])) {
            //     $qrtPenjualans[$th][$qrt] = 0;
            // }
            // $qrtPenjualans[$th][$qrt] += ($pnjSpecs->nilai_af / 3);
        }
        // arrPrint($pnjualanDailies);
        // arrPrint($pembelianNet);
        // matiDisini(__LINE__);

        $data = array(
            "mode" => "viewGraph",
            "title" => "Graph Reports",
            "subTitle" => "",
            "periode" => $periode,
            "indeks" => $indeks,
            "buffer" => $buffer,
            "leadTime" => $leadTime,
            "navigasi" => $arrBi,
            "navigasiAttr" => $arrBiAttr,
            // "produks"          => $produks,
            // "stokNow"          => $tmpStok["sums"],
            "dateStart" => $prev_date,
            "dateStop" => $dtimeNow,
            "penjualanHarian" => $pnjualanDailies,
            "penjualanBulanan" => $pnjualans,
            "penjualanBulananProduk" => $produkYgDiJual,
            // "penjualanFireBulanan"    => $nilaiPenjualanFireNet,
            // "penjualanNonFireBulanan" => $nilaiPenjualanNonFireNet,
            "kolomPenjualanProduk" => $kolomPenjualanProduk,
            // "penjualanQuarter"        => $qrtPenjualans,
            // "returnPenjualan" => $tmpReturnPenjualan["sums"],
            "pembelianBulanan" => $pembelianNet,
        );
        $this->load->view("bi", $data);
    }

    public function formSetting()
    {
        $class = get_class();
        $this->load->config("heBi");
        $heBis = $this->config->item("heBi");
        $arrBiAttr = $arrBies = $heBis['pembelian']['produk']['setting'];
        $this->load->model("Mdls/MdlBi");
        $st = new MdlBi();
        // arrPrint($_REQUEST);
        $cUmums = $heBis['umum'];
        foreach ($cUmums as $cuKey => $cuValues) {
            $cukeys[] = $cuKey;
            $cuLabels[] = $cuValues['label'];
        }
        $cProduks = $heBis['pembelian']['produk']['setting'];
        $cheaders = $heBis['pembelian']['produk']['headerField'];
        $content_note = "";

        foreach ($cheaders as $cpKey => $cpValues) {
            if (isset($cpValues['formula'])) {
                $legenda = $cpValues['label'];
                $lNote = isset($cpValues['formula']) ? $cpValues['formula'] : "-";
                // $legendaNotes[$cpValues['label']] = isset($cpValues['formula']) ? $cpValues['formula'] : "-";
                $content_note .= "<p class='meta no-margin'>";
                $content_note .= "<span class='text-primary text-uppercase'>$legenda</span> : ";
                $content_note .= "$lNote";
                $content_note .= "</p> ";
            }
        }
        foreach ($cProduks as $cpKey => $cpValues) {
            $cukeys[] = $cpKey;
            // arrPrint($cpValues);
        }
        $strNotes = "<div class='alert bg-yellow-light'>";
        $strNotes .= $content_note;
        $strNotes .= "</div>";
        $setBis = $st->lookupBiPenjualanProduk()->result();
        // showLast_query("orange");
        // arrPrint($setBis);
        // arrPrint($cukeys);
        // arrPrint($cuPkeys);
        foreach ($setBis as $biDatas) {
            // foreach ($cukeys as $cukey) {
            //
            //     $biDb[$cukey] = $biDatas->$cukey;
            // }
            $biKeyDb[$biDatas->nama] = $biDatas->nilai;


        }
        // arrPrint($biKeyDb);
        $jam_sekarang = dtimeNow("h:i");
        $nim_date = date('Y-m-d', strtotime(dtimeNow('Y-m-d') . " " . 1 . ' day'));
        $forms = array();
        foreach ($cUmums as $cuKey => $cuAttr) {
            $type = isset($cuAttr['type']) ? $cuAttr['type'] : "text";
            $setValue = isset($biKeyDb[$cuKey]) ? $biKeyDb[$cuKey] : 0;

            $forms[$cuAttr['label']] = "<input type='$type' name='$cuKey' value='$setValue' class='form-control' min='$nim_date'>";
        }
        // $forms["schedule tanggal"] = "<input type='date' name='schedule' class='form-control' min='$nim_date'>";
        // $forms["waktu"] = "<input type='time' name='jam' class='form-control' value='$jam_sekarang'>";
        // $forms["email"] = "<input type='email' name='email' class='form-control' value=''>";
        foreach ($arrBiAttr as $biKey => $arrBY) {
            $setValue = isset($biKeyDb[$biKey]) ? $biKeyDb[$biKey] : 0;
            $biValues = isset($biKeyDb[$biKey]) ? $biKeyDb[$biKey] : (isset($_SESSION[$class][$biKey]) ? $_SESSION[$class][$biKey] : (isset($arrBY['default']) ? $arrBY['default'] : 0));
            $forms[$arrBY['label']] = form_input("$biKey", "$biValues", "class='form-control' placeholder='$biKey'");
        }


        // arrPrint($formsx);
        // arrPrintWebs($forms);
        // arrPrintWebs($legendaNotes);
        $arrKolom_alias = array();

        $data = array(
            "mode" => "modal",
            "field" => "",
            // "template"       => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "template" => "application/template/profile.html",
            "heading" => "setting BI pembelian",
            "forms" => $forms,
            "footer" => form_submit("submit", "Save", "class='btn btn-primary pull-right'"),
            "target" => "result",
            "actions" => "/Bi/saveSetting",
            "notes" => $strNotes,
            "headTpl" => headTpl(),
            "footTpl" => footTpl(),
        );
        $this->load->view("data", $data);
    }

    public function saveSetting()
    {
        $this->load->model("Mdls/MdlBi");
        $st = new MdlBi();

        arrPrint($_POST);
        $this->db->trans_begin();

        $condites = array(
            "jenis" => "bi_pembelian_produk",
            "trash" => "0",
        );
        $newUpd = array(
            "trash" => 1
        );
        $st->updateData($condites, $newUpd);
        showLast_query("kuning");

        foreach ($_POST as $nama => $nilai) {

            $newDatas["jenis"] = "bi_pembelian_produk";
            $newDatas["nama"] = $nama;
            $newDatas["nilai"] = $nilai;
            $newDatas["author_id"] = my_id();
            $newDatas["author_nama"] = my_name();
            $newDatas["dtime"] = dtimeNow();

            $insert = $st->addData($newDatas);
            showLast_query("lime");

        }

        // matiHere("stopss boss");
        $this->db->trans_complete();

        $arrSwals = array(
            "type" => "success",

        );
        echo swalAlert($arrSwals);
        die(topReload(700));
    }

    //------------------
    public function checklistBi()
    {
        //        arrPrint($this->uri->segment_array());
        $vendorID = $this->uri->segment(4);
        $produkID = $_GET['pid'];
        $mode = $_GET['mode'];

        $this->load->model("Mdls/MdlProdukPerSupplier");

        $pps = New MdlProdukPerSupplier();
        if ($mode == "item") {
            $pps->addFilter("suppliers_id='$vendorID'");
            $pps->addFilter("produk_id='$produkID'");

            if (!isset($_SESSION['Bi'][$vendorID]['item'])) {
                $_SESSION['Bi'][$vendorID]['item'] = array();
            }
        } else {
            $pps->addFilter("suppliers_id='$vendorID'");

            if (!isset($_SESSION['Bi'][$vendorID]['all'])) {
                $_SESSION['Bi'][$vendorID]['all'] = array();
            }
        }

        $ppsTmp = $pps->lookupAll()->result();
        //        showLast_query("kuning");
        if (sizeof($ppsTmp) > 0) {
            foreach ($ppsTmp as $spec) {

            }
        }

    }

    public function checklist_toitem()
    {
        // arrPrint($this->uri->segment_array());
        $vendorID = $this->uri->segment(3);
        $produkID = $_GET['pid'];
        $order = $_GET['order'];
        $mode = $_GET['mode'];
        $val = $_GET["val"];
        // arrprint($_GET);
        //         matiHEre();
        // $this->load->model("Mdls/MdlProdukPerSupplier");

        // $pps = New MdlProdukPerSupplier();
        if ($val == "true") {
            // matiHEre("hee true");
            if (!isset($_SESSION['Bi'][$vendorID][$produkID])) {
                $_SESSION['Bi'][$vendorID][$produkID]["new_order"] = $order;
            }
        } else {
            // matiHEre("hee false");
            unset($_SESSION['Bi'][$vendorID][$produkID]);
        }


        // $ppsTmp = $pps->lookupAll()->result();
        // //        showLast_query("kuning");
        // if (sizeof($ppsTmp) > 0) {
        //     foreach ($ppsTmp as $spec) {
        //
        //     }
        // }

    }

    public function viewSetupBi()
    {
        // cekMerah("***" . __LINE__);
        $tres = microtime(true);

        // arrPrintWebs($this->uri->segment_array());
        $source_data = "report"; // report //// mutasi
        $setPeriode = "harian"; // bulanan//// harian
        $modes = array(
            "bulanan" => "viewMonthly",
            "harian" => "viewDaily",
        );
        $this->load->config("heBi");
        $this->load->model("Mdls/MdlReportSql");
        $this->load->model("Mdls/MdlBi");
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $this->load->model("Mdls/MdlCalcStokLimit");
        $rp = new MdlReportSql();
        $class = get_class();
        $pr = new MdlProduk();
        $bi = new MdlBi();
        $mc = new MdlCalcStokLimit();
        $cabang_id = "1";
        $prevSeting = $mc->lookUpRelation();
        // arrprint($prevSeting);

        /* ------------------------------------------------------------------------------------
         * defauld nilai navigasi diatur dr confiq ini yuaaaa
         * ------------------------------------------------------------------------------------*/
        $heBis = $this->config->item("heBi");
        // arrPrint($heBis);

        $arrBiAttr = $arrBies = $heBis['pembelian']['produk']['setting'];
        // arrPrint($arrBiAttr);
        foreach ($prevSeting as $biKey => $arrBY) {
            $biValues = $arrBY["nilai"];
            $arrBi[$biKey] = $biValues;
            $$biKey = $biValues;
        }
        $periode = $prevSeting["periode"]["nilai"];
        // cekMerah("$periode");
        $cheaders = $heBis['pembelian']['produk']['headerField'];
        // $content_note = "";
        $notes = array();
        $heads_2 = array();
        // arrPrint($cheaders);
        foreach ($cheaders as $cpKey => $cpValues) {
            $legenda = $cpValues['label'];
            if (isset($cpValues['formula'])) {
                $lNote = isset($cpValues['formula']) ? $cpValues['formula'] : "-";

                $notes[$legenda] = $lNote;
            }

            // if (isset($cpValues['label'])) {
            $heads_2[] = $legenda;
            // }
        }

        // arrPrintWebs($arrBi);
        // arrPrintWebs($heads_2);
        // matiHere();
        // $refSegmen2 = strlen(url_referer()) > 3 ? explode("/", url_referer())[4] : "";

        $jml_hari_penjualan = $periode * 30;
        $_SESSION[$class] = $arrBi;

        //region jml hari pembelian
        // $dtime = $kemarin = date('Y-m-d', strtotime("-$jml_hari_penjualan day", strtotime(date("Y-m-d"))));
        // $arrPembelian_terakhir_1[$barang_id] = $dtime;
        // $arrJml_hari_penjualan_1[$barang_id] = $jml_hari_penjualan;
        // $str_metode = "dari rata-rata penjualan selama $jml_hari_penjualan hari terakhir === $dtime";
        // cekHere("$str_metode");
        //endregion

        //cekMerah("menyimpan ke session $class");

        // $dtimeNow = dtimeNow('Y-m') . "-01";
        // $periode_X = ($periode) > 0 ? ($periode) * -1 : 0;
        // $stop_date = date('Y-m-d', strtotime($dtimeNow . ' -1 month'));
        // $prev_date = date('Y-m-d', strtotime($dtimeNow . " " . $periode_X . ' month'));
        // $condites = array(
        //     // "th" => "2020",
        //     "tanggal >=" => $prev_date,
        //     "tanggal <"  => $dtimeNow,
        //     // "bl >=" => "12",
        // );
        // $live_condites = array(
        //     // "th" => "2020",
        //     "date(dtime) >=" => $prev_date,
        //     "date(dtime) <"  => $dtimeNow,
        //     // "sum(qty_kredit) as 'sum_qty_k',extern_id"
        // );

        //---- cek kiriman jenisTR dari pembelian, uri segment 3
        // arrPrint($this->uri->segment_array());
        // matiHEre();
        // if (NULL != ($this->uri->segment(3))) {
        //     $mode = "viewBiPurchasing";
        //     $jenisTr = $this->uri->segment(3);
        //     $cCode = "_TR_" . $jenisTr;
        //     $vendorID = isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : 0;
        //     if ($vendorID > 0) {
        //
        //         $pps = New MdlProdukPerSupplier();
        //         $pps->addFilter("suppliers_id='$vendorID'");
        //         $pps->setSortBy(array("mode" => "ASC", "kolom" => "produk_id"));
        //         $produksVendor = $pps->lookupAll()->result();
        //         //            showLast_query("biru");
        //         //            cekHitam(sizeof($produksVendor));
        //         $pIDs = array();
        //         $produks = array();
        //         if (sizeof($produksVendor) > 0) {
        //             foreach ($produksVendor as $spec) {
        //                 $pIDs[$spec->produk_id] = $spec->produk_id;
        //             }
        //
        //             $pr->addFilter("id in ('" . implode("','", $pIDs) . "')");
        //             $tmpPr = $pr->lookupAll();
        //             //                    showLast_query("kuning");
        //             $produks = $tmpPr->result();
        //         }
        //     }
        //     else {
        //         $produks = array();
        //         $pIDs = array();
        //     }
        //
        //     //----bi->getStokNowAll()
        //     if (sizeof($pIDs) > 0) {
        //         $this->db->where_in("extern_id", $pIDs);
        //     }
        // }
        // else {
        //     $mode = $modes[$setPeriode];
        //
        //     if (isset($_GET['limit'])) {
        //         $this->db->limit($_GET['limit']);
        //     }
        //     else {
        //         $this->db->limit(20);
        //     }
        //     $tmpPr = $pr->lookupAll();
        //     $produks = $tmpPr->result();
        // }
        $this->db->limit(1);
        $tmpPr = $pr->lookupAll();
        $produks = $tmpPr->result();

        // arrPrint($produks);
        // matiHEre();

        // if (sizeof($produks) > 0) {
        //     $tmpStok = $bi->getStokNowAll();
        //     // showLast_query("hitam");
        //     // cekHitam(sizeof($tmpStok));
        //     // $rp->setDebug(true);
        //     $rp->setJenis("penjualan");
        //     $rp->setPeriode("bulanan");
        //     $rp->setCondites($condites);
        //     $rp->setOrder("tanggal asc");
        //
        //     $koloms = array(
        //         // "bln",
        //         "extern_id",
        //         // "sum(qty_kredit) as 'sum_qty_kredit'",
        //         "sum(qty_kredit) as 'unit_af'",
        //         "sum(kredit) as 'sum_kredit'",
        //         "cabang_id",
        //         "date(dtime) as 'tgl'",
        //         "month(dtime) as 'bln'",
        //         "year(dtime) as 'thn'",
        //     );
        //     $this->db->group_by("extern_id");
        //     $this->db->select($koloms);
        //     $this->db->where($live_condites);
        //     $tmpPenjualan = $rp->callPenjualan()->result();
        //     // showLast_query("pink");
        //     // cekPink(sizeof($tmpPenjualan));
        //     //        mati_disini();
        //
        //     $pnjualans = array();
        //     foreach ($tmpPenjualan as $pnjSpecs) {
        //         $th = $pnjSpecs->thn;
        //         $bl = $pnjSpecs->bln;
        //         // $subject_id = $pnjSpecs->subject_id;
        //         $subject_id = $pnjSpecs->extern_id;
        //         $datas['bl'] = $bl;
        //         $datas['unit_ot'] = 0;
        //         $datas['unit_in'] = 1;
        //         // $datas['unit_ot'] = $pnjSpecs->unit_ot;
        //         // $datas['unit_in'] = $pnjSpecs->unit_in;
        //         $datas['unit_af'] = $pnjSpecs->unit_af;
        //
        //         $pnjualans[$th][$bl][$subject_id] = $datas;
        //     }
        //
        // }

        // arrPrint($pnjualans);
        //         arrPrintWebs($_SESSION[$class]);
        // arrPrint($this->uri->segment_array());
        //region logic button dari modul atau reguler
        //         if ($this->uri->segment(3) != null) {
        // //            $btnShopCrt = base_url() . $this->uri->segment(4) . "/_processSelectProduct/multiSelectBi/$jenisTr";
        //         }
        //         else {
        // //            $btnShopCrt = "Selectors/_processSelectProduct/multiSelectBi/$jenisTr/";
        //         }
        //endregion
        // cekLime($btnShopCrt);
        $end = microtime(true);
        $execTime = $end - $tres;
        // matiHEre("exec time get data from database ".$execTime);
        // cekMErah($mode);
        $data = array(
            "mode" => "viewSetupBi",
            "title" => "Seting stok limit produk",
            "subTitle" => $setPeriode,
            "periode" => isset($periode) ? $periode : 0,
            // "dateDiffDay"      => $date_diff->days,
            "dateDiffDay" => "",
            "indeks" => isset($indeks) ? $indeks : 0,
            "buffer" => isset($buffer) ? $buffer : 0,
            "leadTime" => isset($leadTime) ? $leadTime : 0,
            "limitTime" => isset($leadTime) ? $leadTime : 0,
            "moqTime" => isset($moqTime) ? $moqTime : 0,
            "navigasi" => $arrBi,
            "navigasiAttr" => $arrBiAttr,
            "notes" => $notes,
            "heads_2" => $heads_2,
            "produks" => $produks,
            "stokNow" => isset($tmpStok["sums"]) ? $tmpStok["sums"] : array(),
            "penjualanBulanan" => isset($pnjualans) ? $pnjualans : array(),
            // "penjualanBulanan"  => isset($pnjualans) ? $pnjualans : array(),
            // "returnPenjualan" => $tmpReturnPenjualan["sums"],
            // "btnToShoppingCart" => isset($jenisTr) ? "Selectors/_processSelectProduct/multiSelectBi/$jenisTr" : NULL,
            "btnToShoppingCart" => isset($jenisTr) ? $btnShopCrt : NULL,
            //            "jenisTr"           => isset($jenisTr) ? $jenisTr : NULL,
            "arrBiAttr" => $prevSeting,
            "dataPeriode" => $periode,
            // "vendorNama" => isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] : NULL,
            // "vendorId" => isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : NULL,
        );
        $this->load->view("bi", $data);
    }

    // -----------------------------------
    public function cekStokLimit()
    {
        // show_develope();
        // cekMerah("***" . __LINE__);
        // arrprint($this->uri->segment_array());

        $judul = "stok limit produk";
        $source_data = "report"; // report //// mutasi
        $setPeriode = "bulanan"; // bulanan//// harian
        $modes = array(
            "bulanan" => "viewMonthly",
            "harian" => "viewDaily",
        );
        $this->load->config("heBi");
        $this->load->model("Mdls/MdlReportSql");
        $this->load->model("Mdls/MdlBi");
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $this->load->model("Mdls/MdlSupplier");
        $this->load->model("Mdls/MdlCalcStokLimit");
        $rp = new MdlReportSql();
        $class = get_class();
        $pr = new MdlProduk();
        $bi = new MdlBi();

        // $mc = new MdlCalcStokLimit();
        // $cabang_id = "1";
        // $prevSeting = $mc->lookUpRelation();
        // showLast_query("merah");
        // // foreach($prevSeting as )
        // foreach ($prevSeting as $biKey => $arrBY) {
        //     $biValues = $arrBY["nilai"];
        //     $arrBi[$biKey] = $biValues;
        //     $$biKey = $biValues;
        // }
        // arrprint($prevSeting);

        /* ------------------------------------------------------------------------------------
         * defauld nilai navigasi diatur dr confiq ini yuaaaa
         * ------------------------------------------------------------------------------------*/
        $heBis = $this->config->item("heBi");
        //         arrPrint($heBis);
        /* ---------------------------------------------------
         * settUx
         * ---------------------------------------------------*/
        $this->load->model("Mdls/MdlSettUx");
        $ux = new MdlSettUx();
        $uxCondites = array(
            "employee_id" => my_id(),
            // "menu"        => "stok_limit",
            "judul" => $judul,
        );
        $uxsatas = $ux->lookupByCondition($uxCondites)->result();
        // showLast_query("merah");;
        // arrPrint($uxsatas);
        foreach ($uxsatas as $uxsata) {
            $defKey = $uxsata->default_key;
            $defNilai = $uxsata->default_nilai;

            $paramStokLimit[$defKey] = $defNilai;
        }

        $periode = isset($paramStokLimit['periode']) ? $paramStokLimit['periode'] : 30;
        // cekMerah("$periode");
        // matiHEre();
        $cheaders = $heBis['pembelian']['produk']['headerField'];
        // $content_note = "";
        $notes = array();
        $heads_2 = array();
        // arrPrint($cheaders);
        // foreach ($cheaders as $cpKey => $cpValues) {
        //     $legenda = $cpValues['label'];
        //     if (isset($cpValues['formula'])) {
        //         $lNote = isset($cpValues['formula']) ? $cpValues['formula'] : "-";
        //
        //         $notes[$legenda] = $lNote;
        //     }
        //
        //     // if (isset($cpValues['label'])) {
        //     $heads_2[] = $legenda;
        //     // }
        // }

        // arrPrintWebs($arrBi);
        // arrPrintWebs($heads_2);
        // matiHere();
        // $refSegmen2 = strlen(url_referer()) > 3 ? explode("/", url_referer())[4] : "";

        $jml_hari_penjualan = $periode * 1;
        // $_SESSION[$class] = $arrBi;

        //region jml hari pembelian
        $dtime = $kemarin = date('Y-m-d', strtotime("-$jml_hari_penjualan day", strtotime(date("Y-m-d"))));
        // $arrPembelian_terakhir_1[$barang_id] = $dtime;
        // $arrJml_hari_penjualan_1[$barang_id] = $jml_hari_penjualan;
        // $str_metode = "dari rata-rata penjualan selama $jml_hari_penjualan hari terakhir === $dtime";
        // cekHere("$str_metode");
        //endregion

        //cekMerah("menyimpan ke session $class");
        $dtimeNow = dtimeNow('Y-m-d');
        $periode_X = ($periode) > 0 ? ($periode) * -1 : 0;
        $stop_date = date('Y-m-d', strtotime($dtimeNow . ' -1 day'));
        $prev_date = date('Y-m-d', strtotime($dtimeNow . " " . $periode_X . ' day'));
        $dtime_t = formatTanggal($stop_date, 't');

        $bln_lalu = previousMonth($dtimeNow);
        // $prev_date = "$bln_lalu-01";
        // $stop_date = "$bln_lalu-". formatTanggal(strtotime($prev_date),'t');
        $condites = array(
            // "th" => "2020",
            "tanggal >=" => $prev_date,
            "tanggal <" => $dtimeNow,
            // "bl >=" => "12",
        );
        // arrPrint($condites);
        // matiHEre($periode);
        $live_condites = array(
            // "th" => "2020",
            "date(dtime) >=" => $prev_date,
            "date(dtime) <" => $stop_date,
            // "sum(qty_kredit) as 'sum_qty_k',extern_id"
        );
        // arrPrint($live_condites);
        // matiHEre();
//arrPrint($_GET);
        /* --------------------------------------------------------
         * produk
         * --------------------------------------------------------*/
        if (ipadd() == "202.65.117.72") {
            if (isset($_GET['limit']) && $_GET['limit'] > 0) {
                $this->db->limit($_GET['limit']);
            } else {
                $this->db->limit(20);
            }
        }

        if(isset($_GET["sp"]) && ($_GET["sp"]>0)){
            $pp = New MdlProdukPerSupplier();
            $produks = $pp->callSpecs($_GET["sp"]);

        }
        else{
            $produks = $pr->callSpecs();
        }
        // showLast_query("kuning");
        // cekKuning(count($produks));
//         arrPrint($produks);

        // region supplier
        $this->load->model("Mdls/MdlSupplier");
        $sp = New MdlSupplier();
        $spTmp = $sp->lookupAll()->result();
        foreach ($spTmp as $spSpec){
            $arrSupplierData[$spSpec->id] = $spSpec->nama;
        }
        // endregion supplier


        if (sizeof($produks) > 0) {

            /* ---------------------------------------------
             * setok sekarang
             * ---------------------------------------------*/
            $bi->setTokoId(my_toko_id());
            $tmpStok = $bi->getStokNowAll();
            $stokNow = $tmpStok['sums'];
            $stokCabangNow = $tmpStok['cabang'];
//            showLast_query("hitam");
            // arrprint();
            // cekHitam(sizeof($tmpStok));
            // matiHEre();
            // $rp->setDebug(true);

            /* -------------------------------------------
            * omset penjualan dalam x
            * -------------------------------------------*/
            // $rp->setJenis("penjualan");
            // $rp->setPeriode("bulanan");
            // $rp->setCondites($condites);
            // $rp->setOrder("tanggal asc");
            $koloms = array(
                "produk_id",
                "extern_id",
                // "sum(qty_kredit) as 'sum_qty_kredit'",
                "sum(qty_kredit) as 'unit_af'",
                "sum(kredit) as 'sum_kredit'",
                "cabang_id",
                "date(dtime) as 'tgl'",
                "month(dtime) as 'bln'",
                "year(dtime) as 'thn'",
            );
            $this->db->group_by("cabang_id,produk_id");
            $this->db->select($koloms);
            // $live_condites['produk_id'] = "54958";
            $this->db->where($live_condites);
            $tmpPenjualan = $rp->callPenjualan()->result();
//             showLast_query("orange");
            // cekKuning(count($tmpPenjualan));
//             arrPrint($tmpPenjualan);
            foreach ($tmpPenjualan as $pnjSpecs) {
                $th = $pnjSpecs->thn;
                $bl = $pnjSpecs->bln;
                // $subject_id = $pnjSpecs->subject_id;
                $subject_id = $pnjSpecs->produk_id;

            }
            // mati_disini();
            $omset_xd_0 = $this->callOmsetHariCabang($tmpPenjualan);
//             arrPrintKuning($omset_xd);
            $qtyis = array();
            foreach ($omset_xd_0 as $cb_id => $omsets) {
                foreach ($omsets as $prod_id => $item_xd) {

                    if (!isset($omset_xd[$prod_id])) {
                        $omset_xd[$prod_id] = 0;
                    }
                    $omset_xd[$prod_id] += $item_xd;

                    $omset_cb[$cb_id][$prod_id] = $item_xd;

                    // $qtyis[$prod_id]["omset_$cb_id"] = $item_xd;
                    // $qtyis[$prod_id]["avg_$cb_id"] = $item_xd / 30;
                }
            }
            // arrPrint($qtyis);
            // arrPrint($omset_xd);
            // cekHijau(count($omset_xd));

            /* -------------------------------------------------------
             * $cabangs
             * -------------------------------------------------------*/
            $this->load->model("Mdls/MdlCabang");
            $cb = new MdlCabang();
            $cb->setFilters(array());
            $cb->addFilter("status='1'");
            $cb->addFilter("trash='0'");
            $cb->addFilter("jenis='cabang'");
            $src_cbs = $cb->lookupAll()->result_array();
            foreach ($src_cbs as $src_cb) {
                // arrPrint($src_cb);
                // if ($src_cb['id'] > 0) {
                if ($src_cb['id'] == -1) {
                    $src_cb['nama'] = "DC";
                }

                $cabangs[$src_cb['id']] = $src_cb;
                // }
            }
            // arrPrint($cabangs);


            /* ---------------------------------------------
             * master data
             * ---------------------------------------------*/
            // cekHere($dtime_t . " $buffer_nilai");
            $bufer_hari = isset($paramStokLimit['buffer_nilai']) ? $paramStokLimit['buffer_nilai'] : 15;
            // cekHere($bufer_hari);
            $omset_hari = $jml_hari_penjualan;
            $master_datas = array();
            foreach ($produks as $produk_speks) {
                $produk_id = $produk_speks->id;
                $data_speks = (array)$produk_speks;

                foreach ($cabangs as $cab_id => $cabang) {
                    $omset_cab = isset($omset_cb[$cab_id][$produk_id]) ? $omset_cb[$cab_id][$produk_id] : 0;
                    $data_speks["omset_$cab_id"] = $omset_cab;

                    $stok_cabang = $stokCabangNow[$cab_id];
                    $stok_aktif_cab = isset($stok_cabang[$produk_id]) ? $stok_cabang[$produk_id]["qty_debet_sum"] : 0;
                    $data_speks["qty_debet_sum_$cab_id"] = $stok_aktif_cab;
                    $qty_avg_cab = $omset_cab / $omset_hari;
                    $data_speks["avg_$cab_id"] = $qty_avg_cab;
                    $stok_ideal_cab = $qty_avg_cab * $bufer_hari;
                    $data_speks["qty_ideal_$cab_id"] = $stok_ideal_cab;
                    $qty_order_cab = $stok_ideal_cab - $stok_aktif_cab;
                    $data_speks["qty_order_$cab_id"] = $qty_order_cab;
                    $umur_stok_cab = $qty_avg_cab > 0 ? $stok_aktif_cab / $qty_avg_cab : 0;
                    $data_speks["umur_stok_$cab_id"] = $umur_stok_cab;
                }

                $omset = isset($omset_xd[$produk_id]) ? $omset_xd[$produk_id] : 0;
                $data_speks["omset"] = $omset;
                /* ----- ----------------------------------------------
                 * $qty_avg_hari = $qty_omset / $omset_hari;
                 * ----- ----------------------------------------------*/

                $qty_avg = $omset / $omset_hari;
                $data_speks["avg"] = $qty_avg;
                /* ----- ----------------------------------------------
                 * $tok_aktif
                 * ----- ----------------------------------------------*/
                $stok_aktif = isset($stokNow[$produk_id]) ? $stokNow[$produk_id]["qty_debet_sum"] : 0;
                $data_speks["qty_debet_sum"] = $stok_aktif;
                /* ----- ----------------------------------------------
                 * $qty_stok_ideal = $qty_avg_harian * $qty_buffer_hari;
                 * ----- ----------------------------------------------*/
                $stok_ideal = $qty_avg * $bufer_hari;
                $data_speks["qty_ideal"] = $stok_ideal;
                /* ----- ----------------------------------------------
                 * $qty_order = $stok_ideal - $stok_aktif;
                 * ----- ----------------------------------------------*/
                $qty_order = $stok_ideal - $stok_aktif;
//                cekHere("$produk_id :: $qty_order");
                $data_speks["qty_order"] = ceil($qty_order);
                /* ----- ----------------------------------------------
                 * $umur_stok_hari = $qty_stok_aktif / $qty_avg_harian;
                 * cara membuat umur stok minus bagaimana?
                 * ----- ----------------------------------------------*/
                $umur_stok_now = $qty_avg > 0 ? ($stok_aktif / $qty_avg) : 0;
                $umur_stok_now_round = floor($umur_stok_now);
                $data_speks["umur_stok"] = $umur_stok_now_round;
                // ---------------------------------------------------
                $tgl_habis = date('Y-m-d', strtotime($dtimeNow . " " . ($umur_stok_now_round * 1) . ' day'));
                $data_speks["tgl_stok_habis"] = $tgl_habis;


                $master_datas[$produk_id] = (object)$data_speks;
            }
        }

        $jenisTr = "466";
        $btnShopCrt = base_url() . "pembelian/_processSelectProduct/multiSelectBi/$jenisTr";
        $supplierNama_f = isset($_GET["sp"]) ? $arrSupplierData[$_GET["sp"]] : NULL;
        $data = array(
            "mode" => "cekStokLimit",
            "title" => "$judul, $supplierNama_f",
            "subTitle" => "stok limit",
            // "returnPenjualan" => $tmpReturnPenjualan["sums"],
            // "btnToShoppingCart" => isset($jenisTr) ? "Selectors/_processSelectProduct/multiSelectBi/$jenisTr" : NULL,
            "btnToShoppingCart" => isset($jenisTr) ? $btnShopCrt : NULL,
            "jenisTr" => isset($jenisTr) ? $jenisTr : NULL,
            // "arrBiAttr"         => $prevSeting,
            "cabangs" => $cabangs,
            // "produks"           => $produks,
            // "qtyis"             => $qtyis,
            // "stokNow"           => $stokNow,
            "master_datas" => $master_datas,
            "bufer_hari" => $bufer_hari,
            "omset_hari" => $omset_hari,
            "vendorNama" => isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] : isset($_GET["sp"]) ? $arrSupplierData[$_GET["sp"]] : NULL,
            "vendorId" => isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : isset($_GET["sp"]) ? $_GET["sp"] : NULL,
            "arrSupplierData" => isset($arrSupplierData) ? $arrSupplierData : array(),

            // "tabHistoryFields"  => $tabHistoryFields,
            // "arrayOnProgress" => $listLimitProduk,
            // "arrayOnProgress"   => array(),
            // "vendorRelasi"      => $iiVendorDataRelasi,
            // "vendorData"        => isset($vendorData) ? $vendorData : "",
            // "arrayOnProgress2"  => $tmpData,
        );
        $this->load->view("bi", $data);
    }

    public function showStokLimit()
    {
        $data = array(
            "mode" => "indek",
            "title" => callMenuLabel_he_menu(),
            "subTitle" => "stok limit",
            // "returnPenjualan" => $tmpReturnPenjualan["sums"],
            // "btnToShoppingCart" => isset($jenisTr) ? "Selectors/_processSelectProduct/multiSelectBi/$jenisTr" : NULL,
            // "btnToShoppingCart" => isset($jenisTr) ? $btnShopCrt : NULL,
            // "jenisTr"           => isset($jenisTr) ? $jenisTr : NULL,
            // // "arrBiAttr"         => $prevSeting,
            // "cabangs"           => $cabangs,
            // // "produks"           => $produks,
            // // "qtyis"             => $qtyis,
            // // "stokNow"           => $stokNow,
            // "master_datas"      => $master_datas,
            // "bufer_hari"        => $bufer_hari,
            // "omset_hari"        => $omset_hari,

            "wadah_stok_limit" => base_url() . "addons/Bi/cekStokLimit",
        );
        $this->load->view("bi", $data);
    }

    public function doSaveUxParams()
    {
        arrPrint($_GET);
        $this->load->model("Mdls/MdlSettUx");
        $ux = new MdlSettUx();
        $key_nilai = $_GET['n'];
        $nilai = $_GET['v'];
        $judul = $_GET['j'];
        $condites = array(
            "menu" => "stok_limit",
            "judul" => $judul,
            "default_key" => $key_nilai,
            "employee_nama" => my_name(),
        );
        $ux->setCondites($condites);
        $ux->simpanUx(my_id(), $key_nilai, $nilai);
        showLast_query("biru");

        // $('#wadah_stok_limit').load('https://jodo.mayagrahakencana.com/addons/Bi/cekStokLimit');
        $link_stok_limit = base_url() . "addons/Bi/cekStokLimit";
        echo "<script>
                $('#wadah_stok_limit').load('$link_stok_limit');
            </script>";
    }

    // -----------------------------------------end------

    public function fetch_data()
    {
        $mdlName = isset($_POST['mdl']) ? $_POST['mdl'] : $_GET['mdl'];
        $mdlName = "MdlProduk";
        //        $foldId = isset($_POST['fid']) ? $_POST['fid'] : isset($_GET['fid']) ? $_GET['fid'] : "";

        $this->load->model("Mdls/" . $mdlName);

        //        if ($foldId > 0) {
        //            if (method_exists($this->$mdlName, "getNavFilters")) {
        //                $navFilter = $this->$mdlName->getNavFilters();
        //                $strCase = $navFilter['mdlFilter'];
        //                $strLabel = $navFilter['label'];
        //                $strKolom = $navFilter['kolomKey'];
        //                $this->db->where($strKolom, "$foldId");
        //            }
        //        }

        $listedFields = $this->$mdlName->getListedFields();

        //        //handle order by chpy
        //        //order di MdlMother di matikan karena error
        //        $arrListed = array();
        //        $nn=1;
        //        foreach($listedFields as $key => $title){
        //            $arrListed[$nn] = $key;
        //            $nn++;
        //        }
        //        $ord="";
        //        $dir="";
        //        if(isset($_REQUEST['order'][0])){
        //            $ord_column = $_REQUEST['order'][0]['column'];
        //            $ord_dir = $_REQUEST['order'][0]['dir'];
        //            $ord = isset($arrListed[$ord_column])? $arrListed[$ord_column] : "id";
        //            $dir = isset($ord_dir)? $ord_dir : "ASC";
        //            $this->db->order_by($ord, $dir);
        //        }
        //        else{
        //            $this->db->order_by("id", "DESC");
        //        }
        //        //handle order by chpy

        //        $fetch_data_0 = $this->$mdlName->make_datatables();
        $this->db->where("toko_id", my_toko_id());
        $fetch_data_0 = $this->$mdlName->make_datatables_all();

        $query_makedatatable = $this->db->last_query();

        $fetch_data = $fetch_data_0;

        $class = get_class();
        $this->load->config("heBi");
        $this->load->model("Mdls/MdlReportSql");
        $this->load->model("Mdls/MdlBi");
        $this->load->model("Mdls/MdlCalcStokLimit");

        $rp = new MdlReportSql();
        $bi = new MdlBi();
        $mc = new MdlCalcStokLimit();
        $prevSeting = $mc->lookUpRelation();

        $heBis = $this->config->item("heBi");
        $arrBiAttr = $arrBies = $heBis['pembelian']['produk']['setting'];
        $arrBi = array();
        // foreach ($arrBies as $biKey => $arrBY) {
        //     $biValues = isset($_SESSION[$class][$biKey]) ? $_SESSION[$class][$biKey] : (isset($arrBY['default']) ? $arrBY['default'] : 0);
        //     $arrBi[$biKey] = $biValues;
        //     $$biKey = $biValues;
        // }
        foreach ($prevSeting as $biKey => $arrBY) {
            $biValues = $arrBY["nilai"];
            $arrBi[$biKey] = $biValues;
            $$biKey = $biValues;
            // $temp_99[]=$biKey;
        }
        $periode = $prevSeting["periode"]["nilai"];
        $cheaders = $heBis['pembelian']['produk']['headerField'];

        $notes = array();
        $heads_2 = array();

        foreach ($cheaders as $cpKey => $cpValues) {
            $legenda = $cpValues['label'];
            if (isset($cpValues['formula'])) {
                $lNote = isset($cpValues['formula']) ? $cpValues['formula'] : "-";
                $notes[$legenda] = $lNote;
            }
            $heads_2[] = $legenda;
        }

        $jml_hari_penjualan = $periode * 1;
        $_SESSION[$class] = $arrBi;

        $dtime = $kemarin = date('Y-m-d', strtotime("-$jml_hari_penjualan day", strtotime(date("Y-m-d"))));

        $dtimeNow = dtimeNow('Y-m-d');
        $periode_X = ($periode) > 0 ? ($periode) * -1 : 0;
        $stop_date = date('Y-m-d', strtotime($dtimeNow . ' -1 day'));
        $prev_date = date('Y-m-d', strtotime($dtimeNow . " " . $periode_X . ' day'));
        $condites = array(
            "tanggal >=" => $prev_date,
            "tanggal <" => $dtimeNow,
        );
        $live_condites = array(
            "date(dtime) >=" => $prev_date,
            "date(dtime) <" => $dtimeNow,
        );
        $stokNow = array();
        $penjualanBulanan = array();
        if (sizeof($fetch_data) > 0) {
            $bi->setTokoId(my_toko_id());
            $tmpStok = $bi->getStokNowAll();
            $stokNow = $tmpStok["sums"];

            $rp->setJenis("penjualan");
            $rp->setPeriode("harian");
            $rp->setCondites($condites);
            $rp->setOrder("tanggal asc");
            $koloms = array(
                "extern_id",
                "sum(qty_kredit) as 'unit_af'",
                "sum(kredit) as 'sum_kredit'",
                "cabang_id",
                "date(dtime) as 'tgl'",
                "month(dtime) as 'bln'",
                "year(dtime) as 'thn'",
            );
            $this->db->group_by("extern_id");
            $this->db->select($koloms);
            $this->db->where($live_condites);
            $tmpPenjualan = $rp->callPenjualan()->result();

            $pnjualans = array();
            $pnjualanDay = array();
            foreach ($tmpPenjualan as $pnjSpecs) {
                $th = $pnjSpecs->thn;
                $bl = $pnjSpecs->bln;
                $tgl = $pnjSpecs->tgl;
                $subject_id = $pnjSpecs->extern_id;
                $datas['tgl'] = $tgl;
                $datas['bl'] = $bl;
                $datas['unit_ot'] = 0;
                $datas['unit_in'] = 1;
                $datas['unit_af'] = $pnjSpecs->unit_af;
                if (!isset($totalPenjualan[$subject_id]["unit_af"])) {
                    $totalPenjualan[$subject_id]["unit_af"] = 0;
                }
                $totalPenjualan[$subject_id]["unit_af"] += $pnjSpecs->unit_af;
                $pnjualans[$th][$bl][$subject_id] = $datas;
                $pnjualanDay[$th][$bl][$tgl][$subject_id] = $datas;
            }
            $penjualanBulanan = $pnjualans;

        }


        $objState = "0";
        $draw = isset($_POST['draw']) ? $_POST['draw'] : "";
        $data = array();
        $sub_array = array();

        $no = 0;
        $xi = 0;

        $xbt = 2000;
        $xb = 4000;
        $xlt = 6000;
        $xmt = 8000;
        $xm = 10000;

        $bgDb_b = "";
        $bgDb_bt = "";
        $bgDb_lt = "";
        $bgDb_l = "";
        $bgDb_mt = "";
        $bgDb_m = "";

        $bulans = array();
        $bulanDatas = array();
        $dayDatas = array();
        foreach ($pnjualanDay as $thn => $datas_2) {
            foreach ($datas_2 as $bln => $datas_3) {
                $bulans[] = "$thn<br>$bln";
                $bulanDatas[] = $datas_3;
                foreach ($datas_3 as $day => $datas_4) {
                    $days[] = "$bln<br>$day";
                    $dayDatas[] = $datas_4;
                }
            }
        }
        $jmlBulan = sizeof($bulans);
        $jmlDay = sizeof($dayDatas);

        $arrProdukID_order = array();
        foreach ($fetch_data as $key => $val) {

            //region incerement
            $no++;
            $xi++;
            $xb++;
            $xbt++;
            $xlt++;
            $xmt++;
            $xm++;
            //endregion

            $id = $val->id;
            // $limit = isset($val->limit) ? $val->limit : 0;
            // $limit_time = isset($val->limit_time)  ? $val->limit_time : 0;
            // $lead_time = isset($val->lead_time) ? $val->lead_time : "";
            // $indeks_db = isset($val->indeks) ? $val->indeks : "";
            // $moq = isset($val->moq) ? $val->moq : "";
            // $moq_time = isset($val->moq_time) ? $val->moq_time : "";
            // $kode = isset($val->barcode) ? $val->barcode : "";

            $limit = $val->limit * 1;
            $limit_time = $val->limit_time;
            $lead_time = $val->lead_time;
            $indeks_db = $val->indeks;
            $moq = $val->moq;
            $moq_time = $val->moq_time;
            $kode = isset($val->barcode) ? $val->barcode : "";

            // foreach ($bulanDatas as $bulanData) {
            //     // $stok_out_ = isset($bulanData[$id]) ? $bulanData[$id]['unit_af'] : 0;
            //
            //     if (!isset($jml{$id})) {
            //         $jml[$id] = 0;
            //     }
            //     $jml[$id] += $stok_out_;
            // }

            $sub_array = array();
            $vendorID = "";

            $link_buffer = base_url() . "Bi/updateProdukLimit/$id";
            $link_bufferTime = base_url() . "Bi/updateProdukLimitTime/$id";
            $link_indeks = base_url() . "Bi/updateProdukIndeks/$id";
            $link_leadTime = base_url() . "Bi/updateProdukLeadTime/$id";
            $link_moqTime = base_url() . "Bi/updateProdukMoqTime/$id";
            $link_moq = base_url() . "Bi/updateProdukMoq/$id";
            //            $link_katalog =     base_url() . "Katalog/viewProduk?q=$id";//tadinya $kode bukan $id

            $link_katalog = "javascript:void(0)";
            $link_ceklist = base_url() . "Bi/checklistBi/$vendorID/?mode=item&pid=$id";

            $stok_now = isset($stokNow[$val->id]) ? $stokNow[$id]["qty_debet_sum"] : 0;
            //            $stok_now_l = "<a href='$link_katalog' title='lokasi persediaan' atarget='_blank'>$stok_now</a>";
            $stok_now_l = $stok_now;

            $bgDb_bt = $limit_time > 0 ? "bg-danger" : "";
            $bgDb_b = $limit > 0 ? "bg-danger" : "";
            $bgDb_lt = $lead_time > 0 ? "bg-danger" : "";
            $bgDb_i = $indeks_db > 0 ? "bg-danger" : "";
            $bgDb_mt = $moq_time > 0 ? "bg-danger" : "";
            $bgDb_m = $moq > 0 ? "bg-danger" : "";
            $bg_color = "";

            $nama_f = strlen($val->nama) > 18 ? substr($val->nama, 0, 18) . "..." : $val->nama;

            $sub_array[] = $no;
            $sub_array[] = $val->id;
            $sub_array[] = $kode;
            $sub_array[] = "<span title='" . $val->nama . "' class='text-capitalize'>" . $nama_f . "</span>";

            $stok_out = isset($totalPenjualan[$id]["unit_af"]) ? $totalPenjualan[$id]["unit_af"] : 0;
            $avg = $stok_out > 0 ? ($stok_out / $jmlDay) : 0;
            $avg_f = $avg > 0 ? formatField("angka", $avg) : 0;

            $leadTimex = $lead_time > 0 ? $lead_time : $leadTime;
            $limitTimex = $limit_time > 0 ? $limit_time : $limitTime;
            $moqTimex = $moq_time > 0 ? $moq_time : 1;
            $indeksx = $indeks_db > 0 ? $indeks_db : $indeks;

            $moqx = $moq > 0 ? ($moq * $moqTimex) : ($avg * $moqTimex);
            $bufferx = $limit > 0 ? $limit : floor($avg) * $limitTimex;
            $bufferx_f = $bufferx;
            $moqx_f = $moqx;

            $ideal_stok = ($avg * ($indeks / 100)) * ($leadTimex / 1) + $bufferx;

            $newPo = ($ideal_stok) - ($stok_now);
            $newPox = $newPo > 0 ? $newPo : 0;

            $preHAristok = $stok_now > 0 ? ($avg > 0 ? ($stok_now / $avg) : 0) : 0;
            $dayestimasiStokBefore = floor($preHAristok);
            $tglHabisStok = after_x_Date(dtimeNow("Y-m-d"), $dayestimasiStokBefore);


            $newPo_f = ceil($newPox);
            $ideal_stok_f = ceil($ideal_stok);
            $dayHariStokAfter = $newPo > 0 ? ($avg > 0 ? floor($ideal_stok / $avg) : 0) : 0;
            $tglHabisStokAfter = after_x_Date(dtimeNow("Y-m-d"), ($dayHariStokAfter + 1));

            $strIndex = "<input type='number' tabindex='$xi'  name='indeks' id='indeks_$id' class='text-center no-padding no-margin border-none $bgDb_i' style='width: 50px' value='$indeksx' onclick=\"this.select();\" onblur=\"getData('$link_indeks?v='+this.value,'update_buffer');\">";
            $strBuffer = "<input type='number' tabindex='$xb'  name='buffer' id='buffer_$id' class='text-center no-padding no-margin border-none $bgDb_b' style='width: 50px' value='$bufferx_f' onclick=\"this.select();\" onblur=\"getData('$link_buffer?v='+this.value,'update_buffer');\">";
            $strBufferTime = "<input type='number' tabindex='$xbt' name='bufferTime' id='bufferTime_$id' class='text-center no-padding no-margin border-none $bgDb_bt' style='width: 50px' value='$limitTimex' onclick=\"this.select();\" onblur=\"getData('$link_bufferTime?v='+this.value,'update_buffer');\">";
            $strLeadTime = "<input type='number' tabindex='$xlt' name='leadTime' id='leadTime_$id' class='text-center no-padding no-margin border-none $bgDb_lt' style='width: 50px' value='$leadTimex' onclick=\"this.select();\" onblur=\"getData('$link_leadTime?v='+this.value,'update_buffer');\">";
            $strMoqTime = "<input type='number' tabindex='$xmt' name='moqTime' id='moqTime_$id' class='text-center no-padding no-margin border-none $bgDb_mt' style='width: 50px' value='$moqTimex' onclick=\"this.select();\" onblur=\"getData('$link_moqTime?v='+this.value,'update_buffer');\">";
            $strMoq = "<input type='number' tabindex='$xm'  name='moq' id='moq_$id' class='text-center no-padding no-margin border-none $bgDb_m' style='width: 50px' value='$moqx_f' onclick=\"this.select();\" onblur=\"getData('$link_moq?v='+this.value,'update_buffer');\">";
            $strnewPox = "<h4 class='text text-bold text-right' >$newPo_f</h4>";
            $sub_array[] = $stok_out;
            $sub_array[] = $avg;
            $sub_array[] = $strBufferTime;
            $sub_array[] = $strBuffer;
            // $sub_array[] = $strMoqTime;
            // $sub_array[] = $strMoq;
            $sub_array[] = $strIndex;
            $sub_array[] = $stok_now_l;

            $sub_array[] = $dayestimasiStokBefore;
            $sub_array[] = $tglHabisStok;
            $sub_array[] = $strLeadTime;
            $sub_array[] = $ideal_stok_f;
            // $sub_array[] = $dayHariStokAfter;

            $sub_array[] = $tglHabisStokAfter;
            $sub_array[] = $strnewPox;

            $data[] = $sub_array;
        }

        if (isset($_REQUEST['order'][0])) {
            $ord_dir = $_REQUEST['order'][0]['dir'];
            if ($ord_dir == "asc") {
                usort($data, function ($a, $b) {
                    $ord_column = $_REQUEST['order'][0]['column'];
                    return $a["$ord_column"] - $b["$ord_column"];
                });
            } else {
                usort($data, function ($a, $b) {
                    $ord_column = $_REQUEST['order'][0]['column'];
                    return $b["$ord_column"] - $a["$ord_column"];
                });
            }
        } else {
            usort($data, function ($a, $b) {
                return $b['5'] * 1 - $a['5'] * 1;
            });
        }
        //sorting ulang

        //handle pages
        $jml_data = count($data);

        $plength = isset($_POST["length"]) ? $_POST["length"] : "";
        $pstart = isset($_POST['start']) ? $_POST["start"] : 0;

        if ($plength * 1 > 0) {
            $data = array_slice($data, $pstart, $plength);
        } else {
            $data = array_slice($data, 10);
        }

        $this->db->where("toko_id", my_toko_id());
        $recordsTotal = $this->$mdlName->get_all_data();
        $this->db->where("toko_id", my_toko_id());
        $recordsFiltered = $this->$mdlName->get_filtered_data();
        $output = array(
            "draw" => intval($draw),
            "query_makedatatable" => $query_makedatatable,
            "jml_data" => $jml_data,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
            "dummy" => $mdlName,
            // "cek"=>$temp_99,
            //            "fetch_data"      => $fetch_data,
        );

        echo json_encode($output);
    }

    public function showVendorRelation()
    {
        // arrPrint($this->uri->segment_array());
        // arrprint($_GET);
        // matiHere();

        $extendateHari = 1;
        $origJenisTr = $this->uri->segment(3);
        $jenisTr = isset($this->configUi[$origJenisTr]['aliasMainTrans']) ? $this->configUi[$origJenisTr]['aliasMainTrans'] : $origJenisTr;
        $modul = $_GET["modul"];
        $this->jenisTr = $jenisTr;
        $this->configUi = loadConfigModulJenis_he_misc($jenisTr, "coTransaksiUi");
        $pihakModel = $this->configUi['pihakModel'];
        $targetForm = $this->configUi["selectorProcessorBi"];
        $btnShopCrt = base_url() . $modul . "/" . $targetForm . "/$jenisTr/$pihakModel/";
        $pid = $_GET["pid"];

        $this->load->config("heBi");
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlProdukPerSupplier");
        $this->load->model("Mdls/MdlReportSql");
        $this->load->model("Mdls/MdlBi");
        $this->load->model("Mdls/MdlCalcStokLimit");
        $this->load->model("Mdls/MdlSupplier");

        $heBis = $this->config->item("heBi");
        $arrBies = $heBis['pembelian']['produk']['setting'];


        $rp = new MdlReportSql();
        $p = new MdlProduk();
        $bi = new MdlBi();
        $vs = new MdlSupplier();
        $s = new MdlProdukPerSupplier();
        $s->addFilter("produk_per_supplier.produk_id='$pid'");
        $this->db->order_by("produk_per_supplier.id", "desc");
        $this->db->limit(1);
        $temp = $s->lookUpAll()->result();
        // matiHEre($this->db->last_query());
        $vendorID = $temp[0]->suppliers_id;
        $iiVendorDataRelasi = $vs->lookUpSupplierName($vendorID);

        $ps = new MdlProdukPerSupplier();
        $ps->addFilter("suppliers_id='$vendorID'");
        $tempRelVendor = $ps->lookUpAll()->result();
        $listProduk = array();
        foreach ($tempRelVendor as $tempRelVendor_0) {
            $listProduk[$tempRelVendor_0->produk_id] = $tempRelVendor_0->produk_id;
        }

        // matiHEre(sizeof($listProduk));
        //region load seting master
        $mc = new MdlCalcStokLimit();
        $prevSeting = $mc->lookUpRelation();
        $extraDay = 1;
        foreach ($prevSeting as $biKey => $arrBY) {
            // cekHitam($biKey);
            $biValues = $arrBY["nilai"];
            $arrBi[$biKey] = $biValues;
            $$biKey = $biValues;
        }
        //endregion

        $dtimeNow = dtimeNow('Y-m-d');
        $periode_X = ($periode) > 0 ? ($periode) * -1 : 0;
        $stop_date = date('Y-m-d', strtotime($dtimeNow . ' -1 day'));
        $prev_date = date('Y-m-d', strtotime($dtimeNow . " " . $periode_X . ' day'));
        $condites = array(
            "tanggal >=" => $prev_date,
            "tanggal <" => $dtimeNow,
        );
        $live_condites = array(
            "date(dtime) >=" => $prev_date,
            "date(dtime) <" => $dtimeNow,
        );

        if (sizeof($listProduk) > 0) {
            $bi->setTokoId(my_toko_id());
            $tmpStok = $bi->getStokNowAll();
            // cekHitam($this->db->last_query());
            $stokNow = $tmpStok["sums"];
            $rp->setJenis("penjualan");
            $rp->setPeriode("harian");
            $rp->setCondites($condites);
            $rp->setOrder("tanggal asc");
            $koloms = array(
                "extern_id",
                "sum(qty_kredit) as 'unit_af'",
                "sum(kredit) as 'sum_kredit'",
                "cabang_id",
                "date(dtime) as 'tgl'",
                "month(dtime) as 'bln'",
                "year(dtime) as 'thn'",
            );
            $this->db->group_by("extern_id");
            $this->db->select($koloms);
            $this->db->where($live_condites);
            $tmpPenjualan = $rp->callPenjualan()->result();
            // cekBiru($this->db->last_query());
            $pnjualans = array();
            $pnjualanDay = array();
            foreach ($tmpPenjualan as $pnjSpecs) {
                $th = $pnjSpecs->thn;
                $bl = $pnjSpecs->bln;
                $tgl = $pnjSpecs->tgl;
                $subject_id = $pnjSpecs->extern_id;
                $datas['tgl'] = $tgl;
                $datas['bl'] = $bl;
                $datas['unit_ot'] = 0;
                $datas['unit_in'] = 1;
                $datas['unit_af'] = $pnjSpecs->unit_af;
                if (!isset($totalPenjualan[$subject_id]["unit_af"])) {
                    $totalPenjualan[$subject_id]["unit_af"] = 0;
                }
                $totalPenjualan[$subject_id]["unit_af"] += $pnjSpecs->unit_af;
                $pnjualans[$th][$bl][$subject_id] = $datas;
                $pnjualanDay[$th][$bl][$tgl][$subject_id] = $datas;
            }
            $penjualanBulanan = $pnjualans;
            $bulans = array();
            $bulanDatas = array();
            $dayDatas = array();
            foreach ($pnjualanDay as $thn => $datas_2) {
                foreach ($datas_2 as $bln => $datas_3) {
                    $bulans[] = "$thn<br>$bln";
                    $bulanDatas[] = $datas_3;
                    foreach ($datas_3 as $day => $datas_4) {
                        $days[] = "$bln<br>$day";
                        $dayDatas[] = $datas_4;
                    }
                }
            }
            $jmlBulan = sizeof($bulans);
            $jmlDay = sizeof($dayDatas);
        }


        //region data produk
        $produks = $p->callSpecs($listProduk);
        foreach ($produks as $PID => $produkData) {

            $stok_now = isset($tmpStok["sums"][$produkData->id]["qty_debet_sum"]) ? $tmpStok["sums"][$produkData->id]["qty_debet_sum"] : 0;
            $stok_out = isset($totalPenjualan[$produkData->id]["unit_af"]) ? $totalPenjualan[$produkData->id]["unit_af"] : 0;

            $id = $produkData->id;
            $limit = $produkData->limit * 1;
            $limit_time = $produkData->limit_time;
            $lead_time = $produkData->lead_time;
            $indeks_db = $produkData->indeks;
            $moq = $produkData->moq;
            $moq_time = $produkData->moq_time;
            // $kode = $produkData->kode;
            // matiHEre($totalPenjualan[$produkData->id]["unit_af"]);
            $avg = $stok_out > 0 ? ($totalPenjualan[$produkData->id]["unit_af"] / $jmlDay) : 0;
            $avg_f = $avg > 0 ? formatField("diskon", $avg, ".") : 0;
            $avg_f = $avg > 0 ? number_format($avg, "6") : 0;

            $leadTimex = $lead_time > 0 ? $lead_time : $leadTime;
            $limitTimex = $limit_time > 0 ? $limit_time : $limitTime;
            $moqTimex = $moq_time > 0 ? $moq_time : 1;
            $indeksx = $indeks_db > 0 ? $indeks_db : $indeks;

            $moqx = $moq > 0 ? ($moq * $moqTimex) : ($avg * $moqTimex);
            $bufferx = $limit > 0 ? $limit : floor($avg) * $limitTimex;
            $bufferx_f = $bufferx;
            $moqx_f = $moqx;

            $ideal_stok = ($avg * ($indeksx / 100)) * ($leadTimex / 1) + $bufferx;
            $newPo = ($ideal_stok) - ($stok_now);
            $newPox = $newPo > 0 ? $newPo : 0;

            $newPo_f = ceil($newPox);
            $ideal_stok_f = ceil($ideal_stok);

            $preHaristok = $stok_now > 0 ? ($avg > 0 ? ($stok_now / $avg) : 0) : 0;
            $dayestimasiStokBefore = floor($preHaristok);
            $tglHabisStok = after_x_Date(dtimeNow("Y-m-d"), $dayestimasiStokBefore);


            /*
             *tambahan logic jika tanggal habis stok == hari ini + extendedday wajib muncul direkomendsai pembelian
             */
            if ($tglHabisStok == after_x_Date(dtimeNow("Y-m-d"), $extendateHari)) {

            }
            $tglBSK = after_x_Date(dtimeNow("Y-m-d"), $extendateHari);

            $newPo = ($ideal_stok) - ($stok_now);
            $newPox = $newPo > 0 ? $newPo : 0;
            $newPo_f = ceil($newPox);
            $dayHariStokAfter = $newPo > 0 ? ($avg > 0 ? floor($ideal_stok / $avg) : 0) : 0;
            $tglHabisStokAfter = after_x_Date(dtimeNow("Y-m-d"), ($dayHariStokAfter + 1));
            $ideal_stok_f = ceil($ideal_stok);
            if (($stok_now < $ideal_stok)) {
                if ($newPox > 0) {
                    // arrprint($produkData);
                    $idsLimits[] = $produkData->id;
                    $listLimitProduk[$produkData->id] = array(
                        "id" => $produkData->id,
                        "pid" => $produkData->id,
                        "produk_id" => $produkData->id,
                        "kode" => $produkData->kode,
                        "barcode" => $produkData->barcode,
                        "nama" => $produkData->nama,
                        "satuan" => $produkData->satuan,
                        "omset" => $stok_out,
                        "average" => $avg_f,
                        "stok" => $stok_now,
                        "indeks" => $indeksx,
                        "tgl_stok_habis" => $tglHabisStok,
                        "buffer_hari" => $dayHariStokAfter,
                        "buffer_qty" => $bufferx,
                        "rekomendasi_hari" => $dayHariStokAfter,
                        "ideal_stok" => $ideal_stok_f,
                        "tgl_habis_proyeksi" => $tglHabisStokAfter,
                        "new_order" => $newPo_f,

                    );
                }
                // cekMErah($produkData->nama);
            } else {
                if (($tglHabisStok == $tglBSK)) {
                    if (!isset($listLimitProduk[$produkData->id])) {
                        $listLimitProduk[$produkData->id] = array(
                            "id" => $produkData->id,
                            "pid" => $produkData->id,
                            "produk_id" => $produkData->id,
                            "kode" => $produkData->kode,
                            "barcode" => $produkData->barcode,
                            "nama" => $produkData->nama,
                            "satuan" => $produkData->satuan,
                            "omset" => $stok_out,
                            "average" => $avg_f,
                            "stok" => $stok_now,
                            "indeks" => $indeksx,
                            "tgl_stok_habis" => $tglHabisStok,
                            "buffer_hari" => $dayHariStokAfter,
                            "buffer_qty" => $bufferx,
                            "rekomendasi_hari" => $dayHariStokAfter . "**",
                            "ideal_stok" => $ideal_stok_f,
                            "tgl_habis_proyeksi" => $tglHabisStokAfter,
                            "new_order" => $ideal_stok_f,
                        );
                    }
                }
            }
        }
        //endregion
        // arrPrint($listLimitProduk);


        //region header fields
        $bulans = array();
        $heads_1 = array(
            // // "no",
            // "pid"=>"pid",
            // "barcode"=>"barcode",
            // "nama"=>"item produk",
        );
        $heads_2 = array(
            "pid" => "pid",
            "kode" => "kode",
            "nama" => "item produk",
            "omset" => "omzet <br> ($periode) Hari</p>",
            "average" => "average<br>harian",
            "buffer_qty" => "<span class='text-blue'>buffer<br>(qty)</span>",
            "indeks" => "index",
            "stok" => "stok<br>tersedia",
            // "umur_stok"=>"<span class='text-red'> umur stok<br>(hari)</span>",
            "tgl_stok_habis" => "<span class='text-red'> tgl<br> habis</span>",

            "rekomendasi_hari" => "<span class='text-green'>proyeksi<br>stok(hari)</span>",
            "ideal_stok" => "<span class='text-green'>proyeksi<br>sto(qty)</span>",
            "tgl_habis_proyeksi" => "<span class='text-green'>tgl habis <br>proyeksi stok</span>",
            "new_order" => "<h7 class='text text-bold'>rekomendasi <br>order</h7>",
            // "new_order"=>"<h4 class='text text-bold'>rekomendasi <br>order</h4>",
            //------------


            // "<span class='text-green'>proyeksi stok<br>(hari)</span>",

            // "<span class='text-green'>umur proyeksi stok <br>(hari)</span>",


        );
        $heads = array_merge($heads_1, $heads_2);


        //endregion
        $vendorName = $iiVendorDataRelasi[$vendorID];
        //mode=toitem&pid=$pID&order=$order
        $data = array(
            "mode" => "showVendorRelation",
            "title" => "stok limit (nama vendor)",
            // "returnPenjualan" => $tmpReturnPenjualan["sums"],
            // "btnToShoppingCart" => isset($jenisTr) ? "Selectors/_processSelectProduct/multiSelectBi/$jenisTr" : NULL,
            // "btnToShoppingCart" => isset($jenisTr) ? $btnShopCrt : NULL,
            "jenisTr" => isset($jenisTr) ? $jenisTr : NULL,
            // "arrayOnProgress" => $listLimitProduk,
            "arrayProgressHeader" => $heads,
            "arrayOnProgress" => $listLimitProduk,
            "vendorData" => $iiVendorDataRelasi,
            "vendorID" => $vendorID,
            "vendorName" => $vendorName,
            "link_ceklist" => base_url() . get_class() . "/checklist_toitem/$vendorID/?",
            "targetForm_link" => $btnShopCrt,
            // "submit_link"=>$btnShopCrt,
            // "perodeDay"=>$periode,

        );
        $this->load->view("bi", $data);
    }

}