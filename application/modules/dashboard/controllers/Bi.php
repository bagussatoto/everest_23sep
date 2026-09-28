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
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);
        $this->load->library("SmtpMailer");

        $this->reportJenis = array(
            "pre_penjualan"          => array(
                "582spo",
                "382spo",
                // "582so",
            ),
            "pre_penjualan_canceled" => array(
                "582spo",
                "382spo",
                // "582so",
            ),
            "penjualan"              => array(
                "582spd",
                "982",
                "382spd",
                // "982",
            ),
            "pembelian_supplies"     => array(
                "461",
                "961",
            ),
            "pembelian_produk"       => array(
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
        }
        else {
            $arrBi = $_SESSION[$class];
        }
        $arrBiAttr["indeks"] = array(
            "label"   => "index",
            "minimal" => "100",
        );
        $arrBiAttr["buffer"] = array(
            "label"   => "buffer",
            "minimal" => "1",
        );
        $arrBiAttr["periode"] = array(
            "label"   => "month periode",
            "minimal" => "1",
        );
        $arrBiAttr["leadTime"] = array(
            "label"   => "index lead time",
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
            "mode"            => "view",
            "title"           => "BI",
            "subTitle"        => "Penjualan vs stok",
            "periode"         => $periode,
            "indeks"          => $indeks,
            "buffer"          => $buffer,
            "leadTime"        => $leadTime,
            "navigasi"        => $arrBi,
            "navigasiAttr"    => $arrBiAttr,
            "produks"         => $produks,
            "stokNow"         => $tmpStok["sums"],
            "penjualan"       => $tmpPenjualan["sums"],
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
        $class = get_class();
        // cekHijau($class);
        // arrPrint($_REQUEST);
        $name = $_GET['n'];
        $value = $_GET['v'];
        // cekOrange("$class $name $value");

        $srr2 = array();
        $srr2[$name] = $value;
        if (!isset($_SESSION[$class][$name])) {

            $_SESSION[$class][$name] = $value;
        }

        $_SESSION[$class][$name] = $value;
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
        $produk_id = $this->uri->segment(3);
        $newLimit = $_GET['v'];

        $this->db->trans_begin();
        $upd = $pr->updateIndeks($produk_id, $newLimit);
        // showLast_query("lime");

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
            "tanggal <"  => $dtimeNow,
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
            "mode"             => "viewMonthly",
            "title"            => "monthly sales",
            "subTitle"         => "",
            "periode"          => $periode,
            "indeks"           => $indeks,
            "buffer"           => $buffer,
            "leadTime"         => $leadTime,
            "navigasi"         => $arrBi,
            // "navigasiAttr"     => $arrBiAttr,
            "produks"          => $produks,
            "stokNow"          => $tmpStok["sums"],
            "penjualanBulanan" => $pnjualans,
            // "returnPenjualan" => $tmpReturnPenjualan["sums"],
        );
        $this->load->view("bi", $data);
    }

    public function viewProdukSales()
    {
        $source_data = "report"; // report //// mutasi
        $setPeriode = "bulanan"; // bulanan//// harian
        $modes = array(
            "bulanan" => "viewMonthly",
            "harian"  => "viewDaily",
        );
        $this->load->config("heBi");
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
        $heBis = $this->config->item("heBi");
        // arrPrint($heBis);
        $arrBiAttr = $arrBies = $heBis['pembelian']['produk']['setting'];
        foreach ($arrBies as $biKey => $arrBY) {
            $biValues = isset($_SESSION[$class][$biKey]) ? $_SESSION[$class][$biKey] : (isset($arrBY['default']) ? $arrBY['default'] : 0);
            $arrBi[$biKey] = $biValues;
            $$biKey = $biValues;
        }

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
        $refSegmen2 = strlen(url_referer()) > 3 ? explode("/", url_referer())[4] : "";
        $jml_hari_penjualan = $periode * 30;
        $_SESSION[$class] = $arrBi;

        //region jml hari pembelian
        $dtime = $kemarin = date('Y-m-d', strtotime("-$jml_hari_penjualan day", strtotime(date("Y-m-d"))));
        // $arrPembelian_terakhir_1[$barang_id] = $dtime;
        // $arrJml_hari_penjualan_1[$barang_id] = $jml_hari_penjualan;
        // $str_metode = "dari rata-rata penjualan selama $jml_hari_penjualan hari terakhir === $dtime";
        // cekHere("$str_metode");
        //endregion
        if (isset($_GET['limit'])) {
            $this->db->limit($_GET['limit']);
        }

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
            "tanggal <"  => $dtimeNow,
            // "bl >=" => "12",
        );
        // $rp->setDebug(true);
        $rp->setJenis("penjualan");
        $rp->setPeriode("bulanan");
        $rp->setCondites($condites);
        $rp->setOrder("tanggal asc");
        $tmpPenjualan = $rp->lookupPenjualanProdukAll();
        $pnjualans = array();
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
        $mode = $modes[$setPeriode];
        $data = array(

            "mode"             => $mode,
            "title"            => "calc stok",
            "subTitle"         => $setPeriode,
            "periode"          => isset($periode) ? $periode : 0,
            // "dateDiffDay"      => $date_diff->days,
            "indeks"           => isset($indeks) ? $indeks : 0,
            "buffer"           => isset($buffer) ? $buffer : 0,
            "leadTime"         => isset($leadTime) ? $leadTime : 0,
            "moqTime"          => isset($moqTime) ? $moqTime : 0,
            "navigasi"         => $arrBi,
            "navigasiAttr"     => $arrBiAttr,
            "notes"            => $notes,
            "heads_2"          => $heads_2,
            "produks"          => $produks,
            "stokNow"          => $tmpStok["sums"],
            "penjualanBulanan" => $pnjualans,
            // "returnPenjualan" => $tmpReturnPenjualan["sums"],
        );
        $this->load->view("bi", $data);
    }

    // ===================================== //

    public function viewGraphSales_old()
    {

        $this->load->model("Mdls/MdlReport");
        $this->load->model("Mdls/MdlProduk");
        $rp = new MdlReport();
        $by = new MdlReport();
        $hpp = new MdlReport();
        $pr = new MdlProduk();
        $class = get_class();

        //region navigasi
        // arrPrint($_SERVER);
        $refSegmen2 = explode("/", url_referer())[4];
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
            "label"   => "index",
            "minimal" => "100",
        );
        $arrBiAttr["buffer"] = array(
            "label"   => "buffer",
            "minimal" => "1",
        );
        $arrBiAttr["periode"] = array(
            "label"   => "show month",
            "minimal" => "1",
        );
        $arrBiAttr["leadTime"] = array(
            "label"   => "index lead time",
            "minimal" => "100",
        );
        //endregion

        // $tmpPr = $pr->lookupAll();
        $tmpPr = $pr->callProdukFire();
        // $produks = $tmpPr->result();
        $fireProduks = $tmpPr['fire'];
        $nonFireProduks = $tmpPr['nonFire'];
        // $produks = $tmpPr['all'];
        // $nonFireProduks = array();
        // $fireProduks = array();
        foreach ($fireProduks as $produkSrc) {
            $fireProdukIds[] = $produkSrc->id;
        }
        foreach ($nonFireProduks as $produkSrc) {
            $nonFireProduksIds[] = $produkSrc->id;
        }

        // arrPrint($fireProdukIds);
        // arrPrint($fireProduks);
        // arrPrint(sizeof($fireProduks));
        // arrPrint(sizeof($nonFireProduks));
        // arrPrint(sizeof($produks));
        // matiHere();

        // $periode = 1;


        $firstDtimeNow = dtimeNow('Y-m') . "-01";
        $dtimeNow = dtimeNow('Y-m-d');
        // $dtimeNow = dtimeNow('Y-m-d');
        $periode_X = ($periode) > 0 ? ($periode) * -1 : 0;
        $stop_date = date('Y-m-d', strtotime($firstDtimeNow . ' -1 month'));
        $prev_date = date('Y-m-d', strtotime($firstDtimeNow . " " . $periode_X . ' month'));
        $condites = array(
            // "th" => "2020",
            "tanggal >=" => $prev_date,
            "tanggal <=" => $dtimeNow,
            // "bl >=" => "12",
        );
        // cekOrange("$periode $periode_X");
        // arrPrintWebs($condites);
        //         $rp->setDebug(true);
        $rp->setJenis("penjualan");
        $rp->setPeriode("bulanan");
        $rp->setCondites($condites);
        $rp->setOrder("tanggal asc");

        $by->setJenis("biaya");
        $by->setPeriode("bulanan");
        $by->setCondites($condites);
        $by->setOrder("tanggal asc");

        $hpp->setJenis("hpp");
        $hpp->setPeriode("bulanan");
        $hpp->setCondites($condites);
        $hpp->setOrder("tanggal asc");

        $tmpPembelian = $rp->callPembelianAll();
        // arrPrint($tmpPembelian);
        // matiHere(__LINE__ . " stop");
        $tmpPenjualan = $rp->lookupPenjualanAll();
        $tmpBiaya = $by->lookupBiaya();
        $tmpHpp = $hpp->lookupHpp();
        $tmpPenjualanProduk = $rp->lookupPenjualanProdukAll();
        $kolomDatas = array(
            "bl",
            "nilai_ot",
            "nilai_in",
            "nilai_af",
            "quarter",
            "counter",
        );
        $kolomShows = array(
            "nilai_af" => "penjualan"
        );
        // arrPrint($tmpPenjualan->result());
        $pnjualans=array();
        $qrtPenjualans=array();
        $qrtDatas=array();
        foreach ($tmpPenjualan->result() as $pnjSpecs) {
            $th = $pnjSpecs->th;
            $bl = $pnjSpecs->bl;
            $qrt = $pnjSpecs->quarter;

            foreach ($kolomDatas as $kolomData) {
                $datas[$kolomData] = $pnjSpecs->$kolomData;
            }

            $pnjualans[$th][$bl] = $datas;

            // $qrtPenjualans[$th]['bulan'] = $bl;
            $qrtDatas[$th][$qrt]['bl'] = $bl;
            if (!isset($qrtPenjualans[$th][$qrt])) {
                $qrtPenjualans[$th][$qrt] = 0;
            }
            $qrtPenjualans[$th][$qrt] += ($pnjSpecs->nilai_af / 3);
        }

//        arrPrint($tmpBiaya->result());
//        matiHere();

        foreach ($tmpHpp->result() as $hppSpecs) {
            $th = $hppSpecs->th;
            $bl = $hppSpecs->bl;
            $qrt = isset($hppSpecs->quarter) ? $hppSpecs->quarter : 0;
            foreach ($kolomDatas as $kolomData) {
                $datas[$kolomData] = isset($hppSpecs->$kolomData) ? $hppSpecs->$kolomData : 0;
            }
            $hpps[$th][$bl] = $datas;
            $qrtDatas[$th][$qrt]['bl'] = $bl;
            if (!isset($qrtBiayas[$th][$qrt])) {
                $qrtHpps[$th][$qrt] = 0;
            }
            $qrtHpps[$th][$qrt] += ($hppSpecs->nilai_af / 3);
        }

        foreach ($tmpBiaya->result() as $bySpecs) {
            $th = $bySpecs->th;
            $bl = $bySpecs->bl;
            $qrt = isset($bySpecs->quarter) ? $bySpecs->quarter : 0;

            foreach ($kolomDatas as $kolomData) {
                $datas[$kolomData] = isset($bySpecs->$kolomData) ? $bySpecs->$kolomData : 0;
            }
            $biayas[$th][$bl] = $datas;
            $qrtDatas[$th][$qrt]['bl'] = $bl;
            if (!isset($qrtBiayas[$th][$qrt])) {
                $qrtBiayas[$th][$qrt] = 0;
            }
            $qrtBiayas[$th][$qrt] += ($bySpecs->nilai_af / 3);

        }

        // arrPrint($qrtPenjualans);
        // arrPrint($pnjualans);
        // arrPrint($qrtDatas);

        $nilaiPenjualanFireNet=array();
        $nilaiPenjualanNonFireNet=array();
        foreach ($tmpPenjualanProduk->result() as $item) {
            $th = $item->th;
            $bl = $item->bl;
            if (in_array($item->subject_id, $fireProdukIds)) {
                if (!isset($nilaiPenjualanFireNet[$th][$bl])) {
                    $nilaiPenjualanFireNet[$th][$bl] = 0;
                }
                $nilaiPenjualanFireNet[$th][$bl] += $item->nilai_af;
            }
            else {
                if (!isset($nilaiPenjualanNonFireNet[$th][$bl])) {
                    $nilaiPenjualanNonFireNet[$th][$bl] = 0;
                }
                $nilaiPenjualanNonFireNet[$th][$bl] += $item->nilai_af;
            }

            if (!isset($nilaiPenjualan[$th][$bl])) {
                $nilaiPenjualan[$th][$bl] = 0;
            }
            $nilaiPenjualan[$th][$bl] += $item->nilai_af;
        }
        // arrPrint($nilaiPenjualanFireNet);
        // arrPrint($nilaiPenjualanNonFireNet);
        // arrPrintWebs($nilaiPenjualan);
        // arrPrint($tmpPenjualanProduk->result());
        $pembelianNet = $tmpPembelian;
        $data = array(
            "mode"                    => "viewGraph",
            "title"                   => "Graph Reports",
            "subTitle"                => "",
            "periode"                 => $periode,
            "indeks"                  => $indeks,
            "buffer"                  => $buffer,
            "leadTime"                => $leadTime,
            "navigasi"                => $arrBi,
            "navigasiAttr"            => $arrBiAttr,
            "biayaBulanan"            => $biayas,
            "hppBulanan"              => $hpps,
            "pembelianBulanan"        => $pembelianNet,
            "penjualanBulanan"        => $pnjualans,
            "penjualanFireBulanan"    => $nilaiPenjualanFireNet,
            "penjualanNonFireBulanan" => $nilaiPenjualanNonFireNet,
            "dataQuarter"             => $qrtDatas,
            "penjualanQuarter"        => $qrtPenjualans,
            "add_link"        => "",
        );
        $this->load->view("bi", $data);
    }
    public function viewGraphSales()
    {

        $this->load->model("Mdls/MdlRugilaba");
        $d = new MdlRugilaba();

        //============= B I A Y A =============
        $d->addFilter("kategori='biaya'");
        $d->addFilter("periode='bulanan'");
//        $d->addFilter("rekening='penjualan'");
        $tmp1=$d->lookupAll()->result();
//        cekMerah($this->db->last_query());

        $rTemp1=array();
        if(sizeof($tmp1)>0){
            foreach($tmp1 as $row){
                $bl = $row->bln;
                $yr = $row->thn;
                $rTemp1["$yr-$bl"][] = $row;
            }
        }
        $arrBiaya=array();
        if(sizeof($rTemp1)>0){
            $tmpResult=array();
            foreach($rTemp1 as $title=>$row2){
                $kredit=0;
                $debet=0;
                $totalKredit=array();
                $totalDebet=array();
                $tmps=array();
                $total=0;
                foreach($row2 as $row3){
                    if(!isset($totalKredit[$row3->rekening])){
                        $totalKredit[$row3->rekening]=0;
                    }
                    if(!isset($totalDebet[$row3->rekening])){
                        $totalDebet[$row3->rekening]=0;
                    }
                    $totalKredit[$row3->rekening] += $row3->kredit;
                    $totalDebet[$row3->rekening] += $row3->debet;
                    $tmps[$row3->rekening] = array(
                        "total_kredit" => $totalKredit[$row3->rekening],
                        "total_debet" => $totalDebet[$row3->rekening],
                    );
                    $tmpResult[$title] = $tmps;
                }
            }
            $arrBiaya=$tmpResult;
        }
        //=============PENJUALAN=============
        $d->addFilter("kategori='penghasilan'");
        $d->addFilter("periode='bulanan'");
//        $d->addFilter("rekening='penjualan'");
        $tmp2=$d->lookupAll()->result();
//        cekMerah($this->db->last_query());
        $rTemp2=array();
        if(sizeof($tmp2)>0){
            foreach($tmp2 as $row){
                $bl = $row->bln;
                $yr = $row->thn;
                $rTemp2["$yr-$bl"][] = $row;
            }
        }
        $arrPenjualan=array();
        if(sizeof($rTemp2)>0){
            $tmpResult=array();
            foreach($rTemp2 as $title=>$row2){
                $kredit=0;
                $debet=0;
                $totalKredit=array();
                $totalDebet=array();
                $total=0;
                $tmps=array();
                foreach($row2 as $row3){
                    if(!isset($totalKredit[$row3->rekening])){
                        $totalKredit[$row3->rekening]=0;
                    }
                    if(!isset($totalDebet[$row3->rekening])){
                        $totalDebet[$row3->rekening]=0;
                    }
                    $totalKredit[$row3->rekening] += $row3->kredit;
                    $totalDebet[$row3->rekening] += $row3->debet;
                    $tmps[$row3->rekening] = array(
                        "total_kredit" => $totalKredit[$row3->rekening],
                        "total_debet" => $totalDebet[$row3->rekening],
                    );
                    $tmpResult[$title] = $tmps+$arrBiaya[$title];
                }
            }
            $arrPenjualan=$tmpResult;
        }

        $result=array();
        //PENJUALAN MURNI
        if( sizeof($arrPenjualan)>0 ){
            $aPenjualan="";
            $arrLabel=array();
            $arrBruto=array();
            $arrNetto=array();
            $arrHpp=array();
            $arrBiaya=array();
            $arrPenj=array();

            foreach($arrPenjualan as $periode => $data){

                $penjualan          = isset($data['penjualan']['total_kredit'])          ? $data['penjualan']['total_kredit']-$data['penjualan']['total_debet'] : 0;
                $return_penjualan   = isset($data['return penjualan']['total_kredit'])   ? $data['return penjualan']['total_kredit']-$data['return penjualan']['total_debet'] : 0;
                $jasa_kirim         = isset($data['jasa kirim']['total_kredit'])         ? $data['jasa kirim']['total_kredit']-$data['jasa kirim']['total_debet'] : 0;
                $laba_lain_lain     = isset($data['laba lain lain']['total_kredit'])     ? $data['laba lain lain']['total_kredit']-$data['laba lain lain']['total_debet'] : 0;
                $hpp                = isset($data['hpp']['total_kredit'])                ? $data['hpp']['total_kredit']-$data['hpp']['total_debet'] : 0;
                $kerugian           = isset($data['kerugian']['total_kredit'])           ? $data['kerugian']['total_kredit']-$data['kerugian']['total_debet'] : 0;
                $kerugian_kurs      = isset($data['kerugian kurs']['total_kredit'])      ? $data['kerugian kurs']['total_kredit']-$data['kerugian kurs']['total_debet'] : 0;
                $keuntungan_kurs    = isset($data['keuntungan kurs']['total_kredit'])    ? $data['keuntungan kurs']['total_kredit']-$data['keuntungan kurs']['total_debet'] : 0;

                //biaya
                $biaya_umum         = isset($data['biaya umum']['total_kredit'])        ? $data['biaya umum']['total_kredit']-$data['biaya umum']['total_debet'] : 0;
                $biaya_produksi     = isset($data['biaya produksi']['total_kredit'])    ? $data['biaya produksi']['total_kredit']-$data['biaya produksi']['total_debet'] : 0;
                $biaya_usaha        = isset($data['biaya usaha']['total_kredit'])       ? $data['biaya usaha']['total_kredit']-$data['biaya usaha']['total_debet'] : 0;
                $quality            = isset($data['quality']['total_kredit'])           ? $data['quality']['total_kredit']-$data['quality']['total_debet'] : 0;
                $delivery_cost      = isset($data['delivery cost']['total_kredit'])     ? $data['delivery cost']['total_kredit']-$data['delivery cost']['total_debet'] : 0;
                $direct_labor       = isset($data['direct labor']['total_kredit'])      ? $data['direct labor']['total_kredit']-$data['direct labor']['total_debet'] : 0;

                $beban_lain_lain       = isset($data['beban lain lain']['total_kredit'])      ? $data['beban lain lain']['total_kredit']-$data['beban lain lain']['total_debet'] : 0;
                $pendapatan       = isset($data['pendapatan']['total_kredit'])      ? $data['pendapatan']['total_kredit']-$data['pendapatan']['total_debet'] : 0;

                $penjualan_net = $penjualan+$return_penjualan+$jasa_kirim;
                $laba_rugi_perubahan_grade_produk = isset($data['laba(rugi) perubahan grade produk']['total_kredit'])      ? $data['laba(rugi) perubahan grade produk']['total_kredit']-$data['laba(rugi) perubahan grade produk']['total_debet'] : 0;
                $laba_rugi_selisih_adjusment = isset($data['laba(rugi) selisih adjustment']['total_kredit'])      ? $data['laba(rugi) selisih adjustment']['total_kredit']-$data['laba(rugi) selisih adjustment']['total_debet'] : 0;

                $total_biaya        = $biaya_umum+$biaya_produksi+$biaya_usaha;
                $laba_rugi_lain_lain = $kerugian+$jasa_kirim+$laba_lain_lain+$laba_rugi_perubahan_grade_produk+$laba_rugi_selisih_adjusment+$quality+$delivery_cost+$direct_labor+$beban_lain_lain+$pendapatan;

                $bruto = ($penjualan+$return_penjualan) + $hpp;
                $netto =  $bruto + $total_biaya + $laba_rugi_lain_lain;

//arrPrint("<br>========================");
//cekMerah($periode);
//
//arrPrint("===========PLUS=============");
//arrPrint("***********penjualan************* " . number_format($penjualan, 2) );
//arrPrint("***********laba_lain_lain************* " . number_format($laba_lain_lain, 2) );
//arrPrint("***********jasa_kirim************* " . number_format($jasa_kirim, 2) );
//arrPrint("***********keuntungan_kurs************* " . number_format($keuntungan_kurs, 2) );
//arrPrint("***********TOTAL PLUS************* " . number_format($penjualan+$laba_lain_lain+$jasa_kirim, 2) ."<br><br>");
//
//arrPrint("===========MINUS=============");
//arrPrint("***********kerugian************* " . number_format($kerugian, 2) );
//arrPrint("***********kerugian_kurs************* " . number_format($kerugian_kurs, 2) );
//arrPrint("***********total_biaya************* " . number_format($total_biaya, 2) );
//arrPrint("***********return_penjualan************* " . number_format($return_penjualan, 2) );
//arrPrint("***********hpp************* " . number_format($hpp, 2) );
//arrPrint("***********quality************* " . number_format($quality, 2) );
//arrPrint("***********delivery_cost************* " . number_format($delivery_cost, 2) );
//arrPrint("***********direct_labor************* " . number_format($direct_labor, 2) );
//arrPrint("***********beban_lain_lain************* " . number_format($beban_lain_lain, 2) );
//arrPrint("***********TOTAL MINUS************* " . number_format($kerugian+$total_biaya+$return_penjualan+$hpp, 2) ."<br><br>");
//
//arrPrint("***********laba_rugi_perubahan_grade_produk************* " . number_format($laba_rugi_perubahan_grade_produk, 2) );
//arrPrint("***********laba_rugi_selisih_adjusment************* " . number_format($laba_rugi_selisih_adjusment, 2) );
//arrPrint("***********laba_rugi_lain_lain************* " . number_format($laba_rugi_lain_lain, 2) ."<br><br>");
//
//arrPrint("===================================");
//arrPrint("***********bruto************* " . number_format($bruto, 2) );
//arrPrint("***********netto************* " . number_format($netto, 2) );
//arrPrint($data);

                $arrLabel[] = $periode;
                $arrBruto[] = $bruto;
                $arrNetto[] = $netto;
                $arrHpp[]   = $hpp*-1;
                $arrBiaya[] = $total_biaya*-1;
                $arrPenj[]  = $penjualan_net;

                $result = array(
                    "label"=> $arrLabel,
                    "bruto"=> $arrBruto,
                    "netto"=> $arrNetto,
                    "hpp"=> $arrHpp,
                    "biaya"=> $arrBiaya,
                    "penjualan"=> $arrPenj,
                );
            }

        }

        $class = get_class();

//        $data = array(
//            "mode"                    => "viewGraph",
//            "title"                   => "Graph Reports",
//            "subTitle"                => "",
//            "periode"                 => $periode,
//            "indeks"                  => $indeks,
//            "buffer"                  => $buffer,
//            "leadTime"                => $leadTime,
//            "navigasi"                => $arrBi,
//            "navigasiAttr"            => $arrBiAttr,
//            "biayaBulanan"            => $biayas,
//            "hppBulanan"              => $hpps,
//            "pembelianBulanan"        => $pembelianNet,
//            "penjualanBulanan"        => $pnjualans,
//            "penjualanFireBulanan"    => $nilaiPenjualanFireNet,
//            "penjualanNonFireBulanan" => $nilaiPenjualanNonFireNet,
//            "dataQuarter"             => $qrtDatas,
//            "penjualanQuarter"        => $qrtPenjualans,
//            "add_link"        => "",
//        );


        $data = array(
            "mode"                    => "viewGraph",
            "title"                   => "Graph Reports",
            "subTitle"                => "",
            "content"                 => $result,
            "periode"                 => '',
            "indeks"                  => '',
            "buffer"                  => '',
            "leadTime"                => '',
            "navigasi"                => '',
            "navigasiAttr"            => '',
            "biayaBulanan"            => '',
            "hppBulanan"              => '',
            "pembelianBulanan"        => '',
            "penjualanBulanan"        => '',
            "penjualanFireBulanan"    => '',
            "penjualanNonFireBulanan" => '',
            "dataQuarter"             => '',
            "penjualanQuarter"        => '',
            "add_link"                => "",
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
            "mode"     => "modal",
            "field"    => "",
            // "template"       => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "template" => "application/template/profile.html",
            "heading"  => "setting BI pembelian",
            "forms"    => $forms,
            "footer"   => form_submit("submit", "Save", "class='btn btn-primary pull-right'"),
            "target"   => "result",
            "actions"  => "/Bi/saveSetting",
            "notes"    => $strNotes,
            "headTpl"  => headTpl(),
            "footTpl"  => footTpl(),
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
}