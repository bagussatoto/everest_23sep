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
        }
        else {
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
        $source_data = "report"; // report //// mutasi
        $setPeriode = "bulanan"; // bulanan//// harian
        $modes = array(
            "bulanan" => "viewMonthly",
            "harian" => "viewDaily",
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
            "tanggal <" => $dtimeNow,
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

            "mode" => $mode,
            "title" => "calc stok",
            "subTitle" => $setPeriode,
            "periode" => isset($periode) ? $periode : 0,
            // "dateDiffDay"      => $date_diff->days,
            "indeks" => isset($indeks) ? $indeks : 0,
            "buffer" => isset($buffer) ? $buffer : 0,
            "leadTime" => isset($leadTime) ? $leadTime : 0,
            "moqTime" => isset($moqTime) ? $moqTime : 0,
            "navigasi" => $arrBi,
            "navigasiAttr" => $arrBiAttr,
            "notes" => $notes,
            "heads_2" => $heads_2,
            "produks" => $produks,
            "stokNow" => $tmpStok["sums"],
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
        $pnjualans = array();
        $qrtPenjualans = array();
        $qrtDatas = array();
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

        $nilaiPenjualanFireNet = array();
        $nilaiPenjualanNonFireNet = array();
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
            "mode" => "viewGraph",
            "title" => "Graph Reports",
            "subTitle" => "",
            "periode" => $periode,
            "indeks" => $indeks,
            "buffer" => $buffer,
            "leadTime" => $leadTime,
            "navigasi" => $arrBi,
            "navigasiAttr" => $arrBiAttr,
            "biayaBulanan" => $biayas,
            "hppBulanan" => $hpps,
            "pembelianBulanan" => $pembelianNet,
            "penjualanBulanan" => $pnjualans,
            "penjualanFireBulanan" => $nilaiPenjualanFireNet,
            "penjualanNonFireBulanan" => $nilaiPenjualanNonFireNet,
            "dataQuarter" => $qrtDatas,
            "penjualanQuarter" => $qrtPenjualans,
            "add_link" => "",
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
        $tmp1 = $d->lookupAll()->result();
//        cekMerah($this->db->last_query());

        $rTemp1 = array();
        if (sizeof($tmp1) > 0) {
            foreach ($tmp1 as $row) {
                $bl = $row->bln;
                $yr = $row->thn;
                $rTemp1["$yr-$bl"][] = $row;
            }
        }
        $arrBiaya = array();
        if (sizeof($rTemp1) > 0) {
            $tmpResult = array();
            foreach ($rTemp1 as $title => $row2) {
                $kredit = 0;
                $debet = 0;
                $totalKredit = array();
                $totalDebet = array();
                $tmps = array();
                $total = 0;
                foreach ($row2 as $row3) {
                    if (!isset($totalKredit[$row3->rekening])) {
                        $totalKredit[$row3->rekening] = 0;
                    }
                    if (!isset($totalDebet[$row3->rekening])) {
                        $totalDebet[$row3->rekening] = 0;
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
            $arrBiaya = $tmpResult;
        }
        //=============PENJUALAN=============
        $d->addFilter("kategori='penghasilan'");
        $d->addFilter("periode='bulanan'");
//        $d->addFilter("rekening='penjualan'");
        $tmp2 = $d->lookupAll()->result();
//        cekMerah($this->db->last_query());
        $rTemp2 = array();
        if (sizeof($tmp2) > 0) {
            foreach ($tmp2 as $row) {
                $bl = $row->bln;
                $yr = $row->thn;
                $rTemp2["$yr-$bl"][] = $row;
            }
        }
        $arrPenjualan = array();
        if (sizeof($rTemp2) > 0) {
            $tmpResult = array();
            foreach ($rTemp2 as $title => $row2) {
                $kredit = 0;
                $debet = 0;
                $totalKredit = array();
                $totalDebet = array();
                $total = 0;
                $tmps = array();
                foreach ($row2 as $row3) {
                    if (!isset($totalKredit[$row3->rekening])) {
                        $totalKredit[$row3->rekening] = 0;
                    }
                    if (!isset($totalDebet[$row3->rekening])) {
                        $totalDebet[$row3->rekening] = 0;
                    }
                    $totalKredit[$row3->rekening] += $row3->kredit;
                    $totalDebet[$row3->rekening] += $row3->debet;
                    $tmps[$row3->rekening] = array(
                        "total_kredit" => $totalKredit[$row3->rekening],
                        "total_debet" => $totalDebet[$row3->rekening],
                    );
                    $tmpResult[$title] = $tmps + $arrBiaya[$title];
                }
            }
            $arrPenjualan = $tmpResult;
        }

        $result = array();
        //PENJUALAN MURNI
        if (sizeof($arrPenjualan) > 0) {
            $aPenjualan = "";
            $arrLabel = array();
            $arrBruto = array();
            $arrNetto = array();
            $arrHpp = array();
            $arrBiaya = array();
            $arrPenj = array();

            foreach ($arrPenjualan as $periode => $data) {

                $penjualan = isset($data['penjualan']['total_kredit']) ? $data['penjualan']['total_kredit'] - $data['penjualan']['total_debet'] : 0;
                $return_penjualan = isset($data['return penjualan']['total_kredit']) ? $data['return penjualan']['total_kredit'] - $data['return penjualan']['total_debet'] : 0;
                $jasa_kirim = isset($data['jasa kirim']['total_kredit']) ? $data['jasa kirim']['total_kredit'] - $data['jasa kirim']['total_debet'] : 0;
                $laba_lain_lain = isset($data['laba lain lain']['total_kredit']) ? $data['laba lain lain']['total_kredit'] - $data['laba lain lain']['total_debet'] : 0;
                $hpp = isset($data['hpp']['total_kredit']) ? $data['hpp']['total_kredit'] - $data['hpp']['total_debet'] : 0;
                $kerugian = isset($data['kerugian']['total_kredit']) ? $data['kerugian']['total_kredit'] - $data['kerugian']['total_debet'] : 0;
                $kerugian_kurs = isset($data['kerugian kurs']['total_kredit']) ? $data['kerugian kurs']['total_kredit'] - $data['kerugian kurs']['total_debet'] : 0;
                $keuntungan_kurs = isset($data['keuntungan kurs']['total_kredit']) ? $data['keuntungan kurs']['total_kredit'] - $data['keuntungan kurs']['total_debet'] : 0;

                //biaya
                $biaya_umum = isset($data['biaya umum']['total_kredit']) ? $data['biaya umum']['total_kredit'] - $data['biaya umum']['total_debet'] : 0;
                $biaya_produksi = isset($data['biaya produksi']['total_kredit']) ? $data['biaya produksi']['total_kredit'] - $data['biaya produksi']['total_debet'] : 0;
                $biaya_usaha = isset($data['biaya usaha']['total_kredit']) ? $data['biaya usaha']['total_kredit'] - $data['biaya usaha']['total_debet'] : 0;
                $quality = isset($data['quality']['total_kredit']) ? $data['quality']['total_kredit'] - $data['quality']['total_debet'] : 0;
                $delivery_cost = isset($data['delivery cost']['total_kredit']) ? $data['delivery cost']['total_kredit'] - $data['delivery cost']['total_debet'] : 0;
                $direct_labor = isset($data['direct labor']['total_kredit']) ? $data['direct labor']['total_kredit'] - $data['direct labor']['total_debet'] : 0;

                $beban_lain_lain = isset($data['beban lain lain']['total_kredit']) ? $data['beban lain lain']['total_kredit'] - $data['beban lain lain']['total_debet'] : 0;
                $pendapatan = isset($data['pendapatan']['total_kredit']) ? $data['pendapatan']['total_kredit'] - $data['pendapatan']['total_debet'] : 0;

                $penjualan_net = $penjualan + $return_penjualan + $jasa_kirim;
                $laba_rugi_perubahan_grade_produk = isset($data['laba(rugi) perubahan grade produk']['total_kredit']) ? $data['laba(rugi) perubahan grade produk']['total_kredit'] - $data['laba(rugi) perubahan grade produk']['total_debet'] : 0;
                $laba_rugi_selisih_adjusment = isset($data['laba(rugi) selisih adjustment']['total_kredit']) ? $data['laba(rugi) selisih adjustment']['total_kredit'] - $data['laba(rugi) selisih adjustment']['total_debet'] : 0;

                $total_biaya = $biaya_umum + $biaya_produksi + $biaya_usaha;
                $laba_rugi_lain_lain = $kerugian + $jasa_kirim + $laba_lain_lain + $laba_rugi_perubahan_grade_produk + $laba_rugi_selisih_adjusment + $quality + $delivery_cost + $direct_labor + $beban_lain_lain + $pendapatan;

                $bruto = ($penjualan + $return_penjualan) + $hpp;
                $netto = $bruto + $total_biaya + $laba_rugi_lain_lain;

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
                $arrHpp[] = $hpp * -1;
                $arrBiaya[] = $total_biaya * -1;
                $arrPenj[] = $penjualan_net;

                $result = array(
                    "label" => $arrLabel,
                    "bruto" => $arrBruto,
                    "netto" => $arrNetto,
                    "hpp" => $arrHpp,
                    "biaya" => $arrBiaya,
                    "penjualan" => $arrPenj,
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
            "mode" => "viewGraph",
            "title" => "Graph Reports",
            "subTitle" => "",
            "content" => $result,
            "periode" => '',
            "indeks" => '',
            "buffer" => '',
            "leadTime" => '',
            "navigasi" => '',
            "navigasiAttr" => '',
            "biayaBulanan" => '',
            "hppBulanan" => '',
            "pembelianBulanan" => '',
            "penjualanBulanan" => '',
            "penjualanFireBulanan" => '',
            "penjualanNonFireBulanan" => '',
            "dataQuarter" => '',
            "penjualanQuarter" => '',
            "add_link" => "",
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
        }
        else {
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

    public function showStokLimit()
    {
        // cekMerah("***" . __LINE__);
        // arrprint($this->uri->segment_array());

        $origJenisTr = $this->uri->segment(3);
        $jenisTr = isset($this->configUi[$origJenisTr]['aliasMainTrans']) ? $this->configUi[$origJenisTr]['aliasMainTrans'] : $origJenisTr;
        $modul = $this->config->item('heTransaksi_ui')[$jenisTr]["modul"];

        $this->jenisTr = $jenisTr;
        $this->configUi = loadConfigModulJenis_he_misc($jenisTr, "coTransaksiUi");
        $pihakModel = $this->configUi['pihakModel'];
        // arrPrint($this->configUi);
        // matiHEre($modul);
        // $cCode = $this->cCode;

        $cCode = "_TR_" . $this->jenisTr;
        $cCodeOrig = "_TR_" . $origJenisTr;

        $tabFieldsItems = isset($this->configUi['tabFieldsItems']) ? $this->configUi['tabFieldsItems'] : array();
        $tabHistoryFields = isset($this->configUi['tabHistoryFields']) ? $this->configUi['tabHistoryFields'] : array();
        $targetForm = isset($this->configUi["selectorProcessorBi"]) ? $this->configUi["selectorProcessorBi"] : "";

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
        $this->load->model("Mdls/MdlCalcStokLimit");
        $rp = new MdlReportSql();
        $class = get_class();
        $pr = new MdlProduk();
        $bi = new MdlBi();
        $mc = new MdlCalcStokLimit();
        $cabang_id = "1";
        $prevSeting = $mc->lookUpRelation();
        // foreach($prevSeting as )
        foreach ($prevSeting as $biKey => $arrBY) {
            $biValues = $arrBY["nilai"];
            $arrBi[$biKey] = $biValues;
            $$biKey = $biValues;
        }
        // arrprint($prevSeting);

        /* ------------------------------------------------------------------------------------
         * defauld nilai navigasi diatur dr confiq ini yuaaaa
         * ------------------------------------------------------------------------------------*/
        $heBis = $this->config->item("heBi");
        //         arrPrint($heBis);
        // matiHEre();
        $arrBiAttr = $arrBies = $heBis['pembelian']['produk']['setting'];
        // arrPrint($prevSeting);
        foreach ($arrBies as $biKey => $arrBY) {
            $biValues = isset($_SESSION[$class][$biKey]) ? $_SESSION[$class][$biKey] : (isset($arrBY['default']) ? $arrBY['default'] : 0);
            $arrBi[$biKey] = $biValues;
            $$biKey = $biValues;
        }
        $periode = $prevSeting["periode"]["nilai"];
        // cekMerah("$periode");
        // matiHEre();
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

        $jml_hari_penjualan = $periode * 1;
        $_SESSION[$class] = $arrBi;

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
            "date(dtime) <" => $dtimeNow,
            // "sum(qty_kredit) as 'sum_qty_k',extern_id"
        );
        // arrPrint($live_condites);
        // matiHEre();
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
        // $this->db->limit(100);
        $tmpPr = $pr->lookupAll();
        $produks = $tmpPr->result();

        // arrPrint($produks);
        // matiHEre();

        if (sizeof($produks) > 0) {
            $tmpStok = $bi->getStokNowAll();
            // showLast_query("hitam");
            // arrprint($tmpStok);
            // cekHitam(sizeof($tmpStok));
            // matiHEre();
            // $rp->setDebug(true);
            $rp->setJenis("penjualan");
            $rp->setPeriode("harian");
            $rp->setCondites($condites);
            $rp->setOrder("tanggal asc");

            $koloms = array(
                // "bln",
                "extern_id",
                // "sum(qty_kredit) as 'sum_qty_kredit'",
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
            // showLast_query("pink");
            // cekPink(sizeof($tmpPenjualan));
            //        mati_disini();

            $pnjualans = array();
            $pnjualanDay = array();
            // $totalPenjualan = 0;
            foreach ($tmpPenjualan as $pnjSpecs) {
                $th = $pnjSpecs->thn;
                $bl = $pnjSpecs->bln;
                $tgl = $pnjSpecs->tgl;
                // $subject_id = $pnjSpecs->subject_id;
                $subject_id = $pnjSpecs->extern_id;
                $datas['tgl'] = $tgl;
                $datas['bln'] = $bl;
                $datas['unit_ot'] = 0;
                $datas['unit_in'] = 1;
                // $datas['unit_ot'] = $pnjSpecs->unit_ot;
                // $datas['unit_in'] = $pnjSpecs->unit_in;
                $datas['unit_af'] = $pnjSpecs->unit_af;
                if (!isset($totalPenjualan[$subject_id]["unit_af"])) {
                    $totalPenjualan[$subject_id]["unit_af"] = 0;
                }
                $totalPenjualan[$subject_id]["unit_af"] += $pnjSpecs->unit_af;

                $pnjualans[$th][$bl][$subject_id] = $datas;
                $pnjualanDay[$th][$bl][$tgl][$subject_id] = $datas;
            }

        }

        // arrprint($pnjualansDay);
        // matiHEre();
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
        // ceklime($jmlDay);
        // matiHEre();
        // arrPrint($totalPenjualan);
        $listLimitProduk = array();
        $idsLimits = array();
        foreach ($produks as $produkData) {
            if (isset($totalPenjualan[$produkData->id]["unit_af"])) {
                $stok_now = isset($tmpStok["sums"][$produkData->id]["qty_debet_sum"]) ? $tmpStok["sums"][$produkData->id]["qty_debet_sum"] : 0;
                $stok_out = isset($totalPenjualan[$produkData->id]["unit_af"]) ? $totalPenjualan[$produkData->id]["unit_af"] : 0;

                $id = $produkData->id;
                $limit = $produkData->limit * 1;
                $limit_time = $produkData->limit_time;
                $lead_time = $produkData->lead_time;
                $indeks_db = $produkData->indeks;
                $moq = $produkData->moq;
                $moq_time = $produkData->moq_time;
                $kode = $produkData->kode;
                // matiHEre($totalPenjualan[$produkData->id]["unit_af"]);
                $avg = $stok_out > 0 ? ($totalPenjualan[$produkData->id]["unit_af"] / $jmlDay) : 0;
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
                $newPo_f = ceil($newPox);
                $ideal_stok_f = ceil($ideal_stok);
                $preHaristok = $stok_now > 0 ? ($avg > 0 ? ($stok_now / $avg) : 0) : 0;
                $dayestimasiStokBefore = floor($preHaristok);
                $tglHabisStok = after_x_Date(dtimeNow("Y-m-d"), $dayestimasiStokBefore);
                $dayHariStokAfter = $newPo > 0 ? ($avg > 0 ? floor($ideal_stok / $avg) : 0) : 0;
                $tglHabisStokAfter = after_x_Date(dtimeNow("Y-m-d"), ($dayHariStokAfter + 1));

                if ($stok_now < $ideal_stok) {

                    if ($newPox > 0) {
                        // arrprint($produkData);
                        $idsLimits[] = $produkData->id;
                        $listLimitProduk[$produkData->id] = array(
                            "id" => $produkData->id,
                            "pid" => $produkData->id,
                            "produk_id" => $produkData->id,
                            "kode" => $produkData->kode,
                            "nama" => $produkData->nama,
                            "satuan" => $produkData->satuan,
                            "omset" => $stok_out,
                            "average" => $avg,
                            "stok" => $stok_now,
                            "buffer" => $bufferx,
                            "indeks" => $indeksx,
                            "tgl_stok_habis" => $tglHabisStok,
                            "buffer_hari" => $dayHariStokAfter,
                            "buffer_qty" => $bufferx,
                            "rekomendasi_hari" => $dayHariStokAfter,
                            "ideal_stok" => $ideal_stok_f,
                            "tgl_habis_proyeksi" => $tglHabisStokAfter,
                            "moq" => $moqx_f,
                            // "ideal_stok" => $ideal_stok_f,
                            "new_order" => $newPo_f,
                        );
                    }

                    // cekMErah($produkData->nama);
                }

            }
        }
        // cekHitam(sizeof($idsLimits));
        $iiVendorDataRelasi = array();
        // $vendorData = array();
        $vendorsID = array();
        if (sizeof($idsLimits) > 0) {
            $ps = new MdlProdukPerSupplier();
            $ps->addFilter("produk_per_supplier.produk_id in ('" . implode("','", $idsLimits) . "')");
            $lsData = $ps->lookUpAll()->result();
            foreach ($lsData as $lsData_0) {
                // $vendorData[$lsData_0->suppliers_id]=$lsData_0->suppliers_nama;
                $vendorsID[$lsData_0->suppliers_id] = $lsData_0->suppliers_id;
                $iiVendorDataRelasi[$lsData_0->suppliers_id][$lsData_0->produk_id] = $lsData_0->produk_id;
                $preProdukVendor[$lsData_0->produk_id] = $lsData_0->suppliers_id;
            }
        }
        if (sizeof($vendorsID) > 0) {
            $this->load->model("Mdls/MdlSupplier");
            $ss = new MdlSupplier();
            $vendorData = $ss->lookUpSupplierName($vendorsID);
        }

        //region builder tab
        $btnShopCrt = base_url() . $modul . "/" . $targetForm . "/$jenisTr/$pihakModel/";
        $hipo_target = base_url() . "Bi/createSession";
        $baseUrl = base_url();
        // cekHitam($btnShopCrt);
        // matiHEre();
        $linkModel = base_url() . get_class() . "/showVendorRelation/$jenisTr/?modul=$modul&";

        $tmpData = array();
        foreach ($tabHistoryFields as $key => $row) {
            // cekLime($key);
            switch ($key) {
                case "produk_id":
                    if (isset($tabFieldsItems[$key]) && (sizeof($tabFieldsItems[$key]) > 0)) {

                        $tmpData[$key] = $listLimitProduk;
                        $tmp = "<table style='font-family: monospace;font-size:12px;' class='table cTable table-bordered compact' id='data_produk'>";
                        $tmp .= "<thead style='background: lightgrey;'>";
                        $tmp .= "<tr class='text-capitalize'>";
                        $tmp .= "<th>No</th>";
                        foreach ($tabFieldsItems[$key] as $src => $srcLabel) {
                            $tmp .= "<th>$srcLabel</th>";
                        }
                        $tmp .= "</tr>";
                        $tmp .= "</thead>";
                        $tmp .= "<tbody>";
                        $ii = 0;
                        if (sizeof($listLimitProduk) > 0) {

                            foreach ($listLimitProduk as $pid => $pidData) {
                                $ii++;
                                $extendDay = $pidData["buffer_hari"];
                                $tmp .= "<tr>";
                                $tmp .= "<td>$ii</td>";
                                //                                        size:BootstrapDialog.SIZE_WIDE,

                                foreach ($tabFieldsItems[$key] as $src => $srcLabel) {
                                    if (isset($preProdukVendor[$pid])) {
                                        $editLink = "BootstrapDialog.show({
                                            title:'Stok limit',
                                            message: $('<div class=\\'text-center\\'><img width=\\'8%\\' src=\\'$baseUrl/assets/images/loading_16_p.gif\\'><br>SEDANG MENGUMPULKAN DATA</div>').load('" . $linkModel . "pid=" . $pid . "&extendDay=$extendDay'),
                                            size:BootstrapDialog.SIZE_WIDE,
                                            draggable:false,
                                            closable:true,
                                        });";
                                        $list = "<span><a href='javascript:void(0)' class='text-link text-capitalize' onclick=\"$editLink\" >$pidData[$src]</a></span>";
                                    }
                                    else {
                                        //region buat relasi produk ke supplier disni
                                        //                                $uriEncode = blobEncode($linkModel."&pid=$pid&extendDay=$extendDay");
                                        //                                $base_uri = base_url()."Data/add/ProdukPerSupplier?reqField=produk_id&reqVal=$pid&produk_id=$pid&pId=yes&preModul=$modul&uriEncode=$uriEncode&";
                                        //                                $base_uri = $linkModel . "uriEncode=$uriEncode&";
                                        $editLink = "BootstrapDialog.show({
                                            title:'Relasi produk vendor',
                                            message: $('<div class=\\'text-center\\'><img width=\\'8%\\' src=\\'$baseUrl/assets/images/loading_16_p.gif\\'><br>CHECK RELASI VENDOR</div>').load('" . $linkModel . "pid=" . $pid . "&extendDay=$extendDay'),
                                            size:BootstrapDialog.SIZE_WIDE,
                                            draggable:false,
                                            closable:true,
                                        });";
                                        // $list ="<span class='text-bold' title='produk belum terelasi dengan vendor manapun, silahkan relasikan terlebih dahulu dari menu data produk'>$pidData[$src]</span>";
                                        $list = "<span><a href='javascript:void(0)' class='text-link text-capitalize' title='produk belum terelasi dengan supplier. klik disini untuk melakukan relasi' onclick=\"$editLink\" >$pidData[$src]</a></span>";
                                        //endregion
                                    }

                                    $tmp .= "<td data-order='$pidData[$src]'>$list</td>";
                                }
                                $tmp .= "</tr>";
                            }
                        }
                        $tmp .= "</tbody>";

                        $tmp .= "</table>";
                    }


                    $tmpData[$key] = isset($tmp) ? $tmp : "";

                    break;
                case "suppliers_id":

                    $tmp = "<div class='row'>";
                    $tmp .= "<div class='col-md-12 no-padding'>";
                    foreach ($vendorData as $sID => $idLabel) {
                        $tmp .= "<div class='col-md-3'>";
                        $tmp .= "<div class='box box-info collapsed-box'>";
                        $tmp .= "<div class='box-header with-border' ><h3 class='text-capitalize'>$idLabel</h3>" . "<span class='text-danger text-bold'>(" . sizeof($iiVendorDataRelasi[$sID]) . ") Produk</span>";
                        $tmp .= "<div class='box-tools pull-right'><button class='btn btn-box-tool' data-widget=\"collapse\" type='button'><i class='fa fa-plus'></i></button></div>";
                        $tmp .= "</div>";
                        $tmp .= "<form method='post' id='$sID' name='$sID' target='result' action='$btnShopCrt?sid=$sID'>";
                        $tmp .= "<div class='box-body no-padding table-responsive'>";

                        $tmp .= "<table id='$sID' style='font-size: 12px;' class='table table-bordered table-hover compact'>";
                        $tmp .= "<thead style='background: lightgrey;'>";
                        $tmp .= "<tr>";
                        $tmp .= "<th width='1%'>No</th>";
                        foreach ($tabFieldsItems[$key] as $src => $srcLabel) {
                            $tmp .= "<th>$srcLabel</th>";
                        }
                        $tmp .= "<th>pilih<input type='checkbox' id='$sID'  class='calcCheckAll' onclick='calcCheckAll(this)'></th>";
                        $tmp .= "</tr>";
                        $tmp .= "</thead>";
                        $tmp .= "<tbody>";
                        if (isset($iiVendorDataRelasi[$sID])) {
                            $ix = 0;
                            foreach ($iiVendorDataRelasi[$sID] as $pID => $pidData) {
                                $ix++;
                                $rel_id = "produk_" . $sID . "_" . $pID;
                                $tmp .= "<tr>";
                                $tmp .= "<td>$ix</td>";
                                if (isset($listLimitProduk[$pID])) {
                                    foreach ($tabFieldsItems[$key] as $src => $srcLabel) {
                                        // $tmp .= "<td>" . formatField_he_format($src,$listLimitProduk[$pID][$src]) . "</td>";
                                        $tmp .= "<td>" . $listLimitProduk[$pID][$src] . "</td>";

                                    }
                                    // arrPrint($listLimitProduk[$pid]);

                                }
                                $order = $listLimitProduk[$pID]['new_order'];
                                $link_ceklist = base_url() . "Bi/checklist_toitem/$sID/?mode=toitem&pid=$pID&order=$order";
                                $tmp .= "<td><input type='checkbox' id='$rel_id' name=\"produk_$sID" . "[]" . "\" onchange=\"$('#result').load('$link_ceklist&val='+$(this).prop('checked'))\"></td>";
                                $tmp .= "</tr>";

                            }
                        }


                        $tmp .= "</tbody>";
                        $tmp .= "</table>";
                        $tmp .= "<div style='padding-top: 12px;'>";
                        $tmp .= "<button type='button' id='btn_" . $sID . "' class='btn btn-warning btn-flat pull-right' onclick=\"document.getElementById('$sID').submit();\">Masukan ke shopingcart</button>";
                        $tmp .= "</div>";
                        $tmp .= "</form>";
                        $tmp .= "\n\n<script>

                                    $('table#$sID.table').DataTable({
                                        paging: false,
                                        searching: false,
                                        info: false,
                                        order: [[ 9, 'desc' ]],
                                    });

                                \n</script>";

                        $tmp .= "</div>";
                        $tmp .= "</div>";
                        $tmp .= "</div>";

                    }
                    $tmp .= "</div>";
                    $tmp .= "<div>";
                    $tmp .= "</div>";
                    $tmp .= "</div>";

                    $tmp .= "\n\n<script>

                                function calcCheckAll(e){
                                    var idsid = $(e).prop('id');
                                    var arrCheck = $(\"input[name='produk_\"+idsid+\"\[\]']\");
                                        jQuery.each(arrCheck, function(i, b){
                                            if( $(e).is(':checked') ){
                                                $(b).prop('checked', true);
                                                $(b).trigger('change');
                                                // console.log(b);
                                            }
                                            else{
                                                $(b).prop('checked', false);
                                                $(b).trigger('change');
                                                // console.error(b);
                                            }
                                        })
                                }

                                arrBox = $('.box.box-info')
                                jQuery.each(arrBox, function(a, b){
                                    $('button.btn-box-tool', $(b)).on('click', function(aa){
                                        if( $(b).hasClass('collapsed-box') ){
                                            $(b).parent().toggleClass('col-md-12').toggleClass('col-md-3');
                                        }
                                        else{
                                            $(b).parent().toggleClass('col-md-3').toggleClass('col-md-12');
                                        }
                                    })
                                })

                                \n</script>";
                    $tmpData[$key] = $tmp;
                    break;
            }
        }

        //endregion

        $data = array(
            "mode" => "showStokLimit",
            "title" => "stok limit",
            // "returnPenjualan" => $tmpReturnPenjualan["sums"],
            // "btnToShoppingCart" => isset($jenisTr) ? "Selectors/_processSelectProduct/multiSelectBi/$jenisTr" : NULL,
            "btnToShoppingCart" => isset($jenisTr) ? $btnShopCrt : NULL,
            "jenisTr" => isset($jenisTr) ? $jenisTr : NULL,
            "arrBiAttr" => $prevSeting,
            "tabFieldsItems" => isset($tabFieldsItems) ? $tabFieldsItems : array(),
            "tabHistoryFields" => isset($tabHistoryFields) ? $tabHistoryFields : array(),
            // "arrayOnProgress" => $listLimitProduk,
            "arrayOnProgress" => array(),
            "vendorRelasi" => isset($iiVendorDataRelasi) ? $iiVendorDataRelasi : array(),
            "vendorData" => isset($vendorData) ? $vendorData : array(),
            "arrayOnProgress2" => isset($tmpData) ? $tmpData : array(),
        );
        $this->load->view("bi", $data);
    }


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
            // cekHitam($this->db->last_query());
            // matiHere();

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
            }
            else {
                usort($data, function ($a, $b) {
                    $ord_column = $_REQUEST['order'][0]['column'];
                    return $b["$ord_column"] - $a["$ord_column"];
                });
            }
        }
        else {
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
        }
        else {
            $data = array_slice($data, 10);
        }


        $output = array(
            "draw" => intval($draw),
            "query_makedatatable" => $query_makedatatable,
            "jml_data" => $jml_data,
            "recordsTotal" => $this->$mdlName->get_all_data(),
            "recordsFiltered" => $this->$mdlName->get_filtered_data(),
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
            }
            else {
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