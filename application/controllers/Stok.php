<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/20/2018
 * Time: 8:35 PM
 */
//include "Menu.php";
class Stok extends CI_Controller
{
    protected $jenis;
    protected $cabang_id;
    private $arrayKoloms;

    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);
//        $this->load->helper("left_menu");
//        $this->load->helper("he_lib_angka");
        $this->jenis = $this->uri->segment(3);
        $this->cabang_id = my_cabang_id();
        $this->arrayKoloms = array(
            "name" => "produk",
            "qty" => "jumlah",
            "hpp" => "hpp average",
            "value" => "nilai",

        );


    }

    public function index()
    {
//        $leftMenu = callMenuleft();
//
//        $data = array(
//            "mode"      => $this->uri->segment(2),
//            "left_menu" => $leftMenu,
//        );
//        $this->load->view("stok", $data);

    }

    public function viewStocks()
    {
        arrPrint($this->uri->segment_array());
        $relName = $this->uri->segment(4);
        $rekName = urldecode($this->uri->segment(5));

        $balConfig = isset($this->config->item('accountBalanceColumns')[$relName]) ? $this->config->item('accountBalanceColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();

//        arrprint($this->uri->segment_array());
        if ($this->uri->segment(3) == "produk") {

            $lableProd = "Produk";


        }
        else {
            $lableProd = "Supplies";
//            $link = base_url()."Opname/view/ $lableProd = \"Produk\";";
        }
//        cekHijau($rekName);
        $link = base_url() . "Opname/view/$lableProd/" . $this->uri->segment(5);
//        cekHere($link);
        $historyClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:' Stok opname ',
                                        message: $('<div></div>').load('" . $link . "'),
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
        $btn_top = "<div class='rrow'>";
        $btn_top .= "<span><a class='btn btn-warning' href='javascript:void(0)' data-toggle-tip='tooltip' data-placement='left' title='' onclick=\"$historyClick\">stok opname</a></span>";
        $btn_top .= "</div>";
        $btn_top .= "<div class='row'></div>";


        $mdlName = "Com" . $relName;
//cekHEre("$relName || $rekName");
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $com = new $mdlName();

        $com->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");

        if (sizeof($accountFilters) > 0) {
            foreach ($accountFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $com->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $com->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    }
                }
            }
        }

        $tmp = $com->fetchBalances($rekName);

//        arrprint($tmp);die();

        $items = array();
        if (sizeof($tmp) > 0) {
            $tmpRow = array();
            foreach ($tmp as $row) {
                foreach ($balConfig['viewedColumns'] as $key => $label) {
                    $tmpRow[$key] = $row->$key;
                }
//                $tmpRow['link'] = base_url() . "Ledger/viewMoves_l2/$relName/$rekName/" . $row->extern_id;
                $tmpRow['link'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id;
                $items[] = $tmpRow;
            }
        }

        $dataTmp = array(
            "mode" => "saldo",
            "title" => "$rekName",
            "subTitle" => "mutasi $rekName",
            "items" => $items,
            "headerFields" => $balConfig['viewedColumns'],
            "btnTop" => $btn_top,

            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",


        );
        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "$rekName",
            "subTitle" => "mutasi $rekName",
            "items" => $items,
            "headerFields" => $balConfig['viewedColumns'],
            "tmp" => $dataTmp,
            "btnTop" => $btn_top,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",


        );
        //endregion

        $this->load->view("stok", $data);
    }

    public function viewStocks__()
    {
//        matiHEre("this");
//        arrPrint($_SESSION);
        $cabang_id = $this->cabang_id;
        $pJenis = $this->uri->segment(3);
        $new_target = base_url() . "Ledger" . "/viewBalances_l1/" . "/" . $pJenis . "/persediaan produk";
        $content = "<iframe id='result2' frameborder='0' width=100% height=100% style='width:100%;height:500px;position:relative;top:0px;left:0px;right:0px;bottom:0px;overflow:hidden;' src='$new_target'>";
        $content .= "</iframe>";
//        cekHEre($content);

//        arrPrint($_SESSION);

        switch ($this->jenis) {
            case "bahan_":
                $this->load->model("Coms/ComRekeningPembantuBahan");
                $rb = new ComRekeningPembantuBahan();
                $tmpRb = $rb->lookupLastEntries(date("Y-m-d"));
//                arrPrint($tmpRb);

                $items = array();
                if (sizeof($tmpRb) > 0) {
                    foreach ($tmpRb as $row) {
                        $items[] = array(
                            "cabangID" => $row->cabang_id,
                            "id" => $row->produk_id,
                            "name" => $row->nama,
                            "qty" => $row->jml,
                            "hpp" => $row->hpp,
                            "value" => $row->jml_nilai,
                        );
                    }
                }
                break;
            case "produk_":
                $this->load->model("Coms/ComRekeningPembantuProduk");
                $this->load->model("Mdls/MdlCabang");
                $this->load->model("Mdls/MdlProduk");
                $rb = new ComRekeningPembantuProduk();
                $pr = new MdlProduk();
                $cb = new MdlCabang();

                $cabangTmp = $cb->lookupCabangData();
                $prods_data = $pr->lookupProdukData();
                $tmpRb = $rb->lookupLastEntries($cabang_id);

                //region persiapan cabang
                $arrCabang = array();
                foreach ($cabangTmp as $cabang_data) {
                    $arrCabang[$cabang_data->id] = $cabang_data->nama;
                }

                if (sizeof($tmpRb) > 0) {
                    foreach ($tmpRb as $row) {
                        $items[$row->cabang_id][$row->produk_id] = array(
//                            "cabangID" => $row->cabang_id,
//                            "id" => $row->produk_id,
                            "name" => $row->nama,
                            "qty" => $row->jml,
                            "hpp" => formatAngkaDesimal($row->hpp),
                            "value" => formatAngkaDesimal($row->jml_nilai),
                        );
                    }
                }
                //endregion

                //region list produk
                $arrProduk = array();
                foreach ($prods_data as $p_data) {
                    $arrProduk[$p_data->id] = array(
                        "kode" => $p_data->kode,
                        "nama" => $p_data->nama,
                        "label" => $p_data->label,
                    );
                }

                //endregion

                break;
        }
        $items = array();//untuk reset dulu
        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Persediaan " . $this->jenis,
            "subTitle" => "Pusat",
//            "arrayKoloms"  => $this->arrayKoloms,    //=== nama2 kolom yang nampil
            "items" => $content,    //=== data perbarisnya
//            "array_produk" => $arrProduk,
//            "array_cabang" => $arrCabang,
        );
//        echo "$data";
//die();
        $this->load->view("stok", $data);
    }

    public function viewLastMutasi()
    {
        $this->load->model("ComRekeningPembantuBahan");
        $rb = new ComRekeningPembantuBahan();
        $tmpRb = $rb->lookupLastMutasi();
        $jenisMutasi = array(
            "in" => array(
                "467" => "pembelian",//in),
                "976" => "return produksi",//in),
            ),
            "ot" => array(
                "967" => "return pembelian",//out
                "776" => "produksi", //out),
            ),

        );
        $arrayTempMutasi = $tmpRb->result();
        $arrayKoloms = array(
            "dtime" => "tanggal",
            "name" => "nama produk",
            "transaksi_no" => "invoice",
            "transaksi_jenis" => "keterangan",
            "cabang_nama" => "gudang",
            //            "oleh_nama" => "petugas",
            "unit_be" => "awal",
            "pembelian" => "pembelian",
            "return_produksi" => "return produksi",
            "produksi" => "produksi",
            "retrun_pembelian" => "return pembelian",
            "unit_af" => "akhir",

        );
        $items = array();
        if (sizeof($tmpRb) > 0) {
            foreach ($arrayTempMutasi as $row) {
                $arrUnitMutasi = array();
                $jenis_transaksi = $row->transaksi_jenis;
                foreach ($jenisMutasi as $jn => $tempJenis) {
                    if ($jn == "in") {
                        if (array_key_exists($row->transaksi_jenis, $tempJenis)) {
                            $unit = $row->unit_in;
                            $jns = $tempJenis[$jenis_transaksi];
                        }
                    }
                    else {
                        if (array_key_exists($row->transaksi_jenis, $tempJenis)) {
                            $arrUnitMutasi[$tempJenis[$row->transaksi_jenis]] = $row->unit_ot;
                            $jns = $tempJenis[$jenis_transaksi];
                        }
                    }
                }
                $items[] = array(
                    "cabangID" => $row->cabang_id,
                    "keterangan" => $jns,
                    "invoice" => $row->transaksi_no,
                    "awal" => $row->unit_be,
                    "akhir" => $row->unit_af,
                    "id" => $row->produk_id,
                    "nama produk" => $row->produk_nama,
                    "produk_id" => $row->produk_id,
                    "tanggal" => $row->dtime,
                    "gudang" => $row->cabang_id,
                    "$jns" => $unit,
                    //                        "oleh nama" => $row->oleh_nama,
                );

            }
        }
        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Mutasi bahan ",
            "subTitle" => "20 terakhir",
            "arrayKoloms" => $arrayKoloms,    //=== nama2 kolom yang nampil
            "items" => $items,    //=== data perbarisnya
            "jenis" => $jenisMutasi,// jenis mutasi
            "arrayUnit" => $arrUnitMutasi,// jenis mutasi
        );
        $this->load->view("stok", $data);
    }

    public function viewDetailMutasi()
    {
//        arrPrint($_REQUEST);
        $prods_id = $_REQUEST['j'];
        $limit = 20;
        $maxPageNum = 20;
        $this->load->model("MdlBahan");
        $this->load->model("ComRekeningPembantuBahan");
        $rb = new ComRekeningPembantuBahan();
        $rb->addFilter("produk_id=$prods_id");
        $mb = new MdlBahan();
        $bahanData = $mb->lookupBahanData($prods_id);
        $date2 = !isset($_POST['date2']) ? dtimeNow("y-m-d", "") : $_POST['date2'];

        $countData = $rb->lookupMutasiCount();

        $numPages = ceil($countData / $limit);
        $page = isset($_GET['page']) ? $_GET['page'] : $numPages;
        $pages = array();
        if ($countData > 0) {
            $factor = ($maxPageNum / 2);
            $selisihDepan = ($page - $factor);
            $selisihBelakang = ($page + $factor);

            $firstNum = 0;
            $lastNum = 0;
            if ($selisihDepan >= 0) {
                $pages["<span class='glyphicon glyphicon-home'></span> "] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?j=$_REQUEST[j]&page=1";
                $firstNum = $selisihDepan;
                $lastNum = $selisihBelakang;
            }
            else {
                $firstNum = 1;
                $lastNum = abs($selisihDepan) + $selisihBelakang;
            }
            if ($lastNum > $numPages) {
                $lastNum = $numPages;
            }

            for ($i = $firstNum; $i <= $lastNum; $i++) {
                $pages[$i] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?j=$_REQUEST[j]&page=$i";
            }
            if ($lastNum <= $numPages) {
                $pages[" <span class='glyphicon glyphicon-fire'></span>"] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?j=$_REQUEST[j]&page=$numPages";
            }
//            cekHEre("$firstNum | $lastNum|$page");
        }
//        $tmpRb = $rb->lookupRangedEntries($_REQUEST['j'],"","","$date2");//rencana untuk tambahan range tanggal filter  bro
        $arrayTempMutasi = $rb->lookupByPaging($limit, $page)->result();
        $jenisMutasi = array(
            "467" => "pembelian",//in),
            "976" => "return produksi",//in),
            "967" => "return pembelian",//out
            "776" => "produksi", //out),
        );
        $labelMutasi = array(
            "467" => "unit_in",//in),
            "976" => "unit_in",//in),
            "967" => "unit_ot",//out
            "776" => "unit_ot", //out),
        );

        $arrayKoloms = array(
            "dtime" => "tanggal",
            "name" => "nama produk",
            "transaksi_no" => "invoice",
            "transaksi_jenis" => "keterangan",
            "cabang_nama" => "gudang",
            //            "oleh_nama" => "petugas",
            "unit_be" => "awal",
            "pembelian" => "pembelian",
            "return_produksi" => "return produksi",
            "produksi" => "produksi",
            "retrun_pembelian" => "return pembelian",
            "unit_af" => "akhir",

        );
        $items = array();
        if (sizeof($arrayTempMutasi) > 0) {
            foreach ($arrayTempMutasi as $row) {
                $arrUnitMutasi = array();
                $jenis_transaksi = $row->transaksi_jenis;
                if (array_key_exists($row->transaksi_jenis, $jenisMutasi)) {
                    $jenis = $jenisMutasi[$row->transaksi_jenis];
                    $kolom_select = $labelMutasi[$row->transaksi_jenis];
                    $unit = $row->$kolom_select;
                }
                else {
                    $jenis = "";
                    $unit = 0;
                }

                $items[] = array(
                    "cabangID" => $row->cabang_id,
                    "keterangan" => $jenis,
                    "invoice" => $row->transaksi_no,
                    "awal" => $row->unit_be,
                    "akhir" => $row->unit_af,
                    "id" => $row->produk_id,
                    "nama produk" => $row->produk_nama,
                    "produk_id" => $row->produk_id,
                    "tanggal" => $row->dtime,
                    "gudang" => $row->cabang_id,
                    "$jenis" => $unit,

                );

            }
        }

        if (sizeof($arrayTempMutasi) > 0) {
            $data = array(
//            "mode" => $this->uri->segment(2),
                "mode" => "viewLastMutasi",
                "title" => "Mutasi bahan ",
                "subTitle" => "$row->produk_nama",
                "arrayKoloms" => $arrayKoloms,    //=== nama2 kolom yang nampil
                "items" => $items,    //=== data perbarisnya
                "jenis" => $jenisMutasi,// jenis mutasi
                "arrayUnit" => $arrUnitMutasi,// jenis mutasi
                "page" => $page,
                "pages" => $pages,
                "pageCount" => $numPages,
            );
        }
        else {
            $data = array(
                //            "mode" => $this->uri->segment(2),
                "mode" => "viewLastMutasi",
                "title" => "Mutasi bahan ",
                "subTitle" => "$bahanData->nama",
                "arrayKoloms" => $arrayKoloms,    //=== nama2 kolom yang nampil
                "items" => "",    //=== data perbarisnya
                "jenis" => "",// jenis mutasi
                "arrayUnit" => "",// jenis mutasi
                "page" => $page,
                "pages" => $pages,
                "pageCount" => $numPages,
            );
        }

        $this->load->view("stok", $data);
    }

    public function findBahan()
    {
//        $base_url = base_url();
//        $uri_segment = $this->uri->segment(1);
        $link = base_url() . get_class($this) . "/viewDetailMutasi";

        if (strlen($_REQUEST['p']) >= 3) {
            $this->load->model("MdlBahan");
            $cu = new MdlBahan();
            $cu->setSearch($_REQUEST['p']);

            $result = $cu->lookupLimitedBySelected();
            if (sizeof($result) > 0) {
                $new_result = $result->result();
            }
            else {
                $new_result = array();
            }

            if (sizeof($new_result) > 0) {
                $result_x = "";
//                $result_x = "<div><span>BAHAN</span><span>STOK</span></div>";

                foreach ($new_result as $data) {
                    $produk_nama = $data->nama;
                    $produk_id = $data->id;

                    $result_x .= "<p class='list-group-item '><a href='$link?j=$produk_id' title='klik untuk memilih bahan'>$produk_nama</a></p>";

                }
                $hasil = " $result_x";
            }
            else {
                $hasil = "<p class='list-group-item list-group-item-info'>Tidak ada data. Ketikan nama lain</p>";
            }
        }
        else {
            $hasil = "";
        }

        echo "<div class='list-group pure-u-1-1' style='z-index: 1025;position: absolute;'>";
        echo $hasil;
        echo "</div>";


    }

    public function view()
    {
        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "",
            "subTitle" => "",
            "items" => "",
            "headerFields" => ['viewedColumns'],

            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",


        );
        //endregion

        $this->load->view("stok", $data);
    }


}