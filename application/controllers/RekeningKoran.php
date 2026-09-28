<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/20/2018
 * Time: 8:35 PM
 */

//include "Menu.php";
class RekeningKoran extends CI_Controller
{
    protected $jenis;
    protected $cabang_id;
    private $arrayKoloms;
    private $dates;


    public function getDates()
    {
        return $this->dates;
    }
    public function setDates($dates)
    {
        $this->dates = $dates;
    }

    public function __construct()
    {
        parent::__construct();
//        $this->load->helper("left_menu");
//        $this->load->helper("he_lib_angka");
        $this->load->model("MdlTransaksi");
        $trd = new MdlTransaksi();
        $this->load->model("Mdls/MdlPlafonHutangBank");
        $this->load->model('Mdls/MdlLockerPlafonHutangBankCache');
        $this->load->model('Coms/ComRekeningPembantuRekeningKoran');
        $this->jenis = $this->uri->segment(3);
        $this->cabang_id = my_cabang_id();
        $this->dates = $trd->lookupDates();
        $this->dates['entries'][date("y-m-d")] = date("y-m-d");

    }


    public function index()
    {
        arrPrint($this->uri->segment_array());
        $p = new MdlPlafonHutangBank();
        $c = new MdlLockerPlafonHutangBankCache();
        $r = new ComRekeningPembantuRekeningKoran();

        $listedFields = $p->getListedFields();
        $cacheFields = array("kredit" => "realisasi", "debet" => "saldo");
        $tmpData = $p->lookupAll()->result();
        $tmpCache = $c->lookupAll()->result();

        $r->addFilter("periode='forever'");
        $tmpRel = $r->lookUpAll()->result();
//        arrPrint($tmpRel);


        $headerFields = array_merge($listedFields, $cacheFields);
        $tmp = array();
        $tmpCacheData = array();
        $tmpCacheData0 = array();
        $tmpLink = array();
        if (sizeof($tmpData) > 0) {
            foreach ($tmpData as $tmp0) {
                $data = array();
                foreach ($listedFields as $field => $alias) {
                    $data[$field] = $tmp0->$field;
                }
                $tmp[$tmp0->extern_id] = $data;
                $tmpLink[$tmp0->extern_id] = base_url() . $this->uri->segment(1) . "/viewMove/?extern_id=" . $tmp0->extern_id;
//                arrPrintWebs($tmp);
            }
            foreach ($tmpCache as $tmpCache0) {
                $tmpCacheData[$tmpCache0->extern_id] = array("debet" => $tmpCache0->debet);
            }
            foreach ($tmpRel as $tmpRel0) {
                $tmpCacheData0[$tmpRel0->extern_id] = array("kredit" => $tmpRel0->kredit);
            }

        }
//        arrPrint($tmpLink);
        $finalData = array();
        foreach ($tmp as $extID => $tmpX) {
            $dta2 = isset($tmpCacheData[$extID]) ? $tmpCacheData[$extID] : array("debet" => "0");
            $dta3 = isset($tmpCacheData0[$extID]) ? $tmpCacheData0[$extID] : array("kredit" => "0");
            $finalData[$extID] = array_merge($tmpX, $dta2, $dta3);
        }
//        arrPrint($finalData);
//        matiHEre();

        $data = array(
            "mode" => "viewRekeningKoran",
            "title" => "Realisasi rekening koran",
            "subTitle" => " ",
            "items" => $finalData,
            "headerFields" => $headerFields,
            "btnTop" => "",
            "link" => $tmpLink,

        );

        //endregion

        $this->load->view("data", $data);
    }

    public function viewMove()
    {
//        arrPrint($this->uri->segment_array());
        $cabangID = $this->session->login['cabang_id'];
        $jenisAliases = arrCodeAliasing($cabangID);
        $this->load->model("MdlTransaksi");
        $headerFields = array(
//            "oleh_nama" => "by",
            "dtime" => "date",
//            "keterangan" => "description",
            "jenis" => "note",
            "oleh_nama" => "by",
            "ids_his" => "reference number",
            "transaksi_no" =>"receipt number",

        );

        $this->load->model("Mdls/MdlLockerPlafonHutangBankMutasi");
        $r = new MdlLockerPlafonHutangBankMutasi();
        $externID = $_GET['extern_id'];
        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            $limit = 20;
            $this->db->limit("$limit");
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $subTitle_date = "";
            $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
        }
        else {
            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
            $this->db->order_by("id", "DESC");
            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
            $subSubTitle_date = "";
        }
        $tmp = $r->fetchMoves($externID);
        $position = array(
            "in" => "debet",
            "out" => "kredit",
        );
//arrPrint($tmp);
//die();
        $labelAlias = array(
            "in" => "in(idr)",
            "out" => "out(idr)",
        );
        $arrProds = array();
        if (sizeof($tmp) > 0) {
            $trID = array();
            $saldo_value_berjalan = 0;
            foreach ($tmp as $temX) {
//                arrPrint($temX);
                $transaksi_id = $temX->transaksi_id;
                $position = $temX->debet > 0 ? "debet" : "kredit";
                $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;

                if ($position == "debet") {
                    $arrMovedDetail["in"][$temX->transaksi_jenis] = $position;
                }
                else {
                    $arrMovedDetail["out"][$temX->transaksi_jenis] = $position;
                }

                $arrProds[][$temX->transaksi_jenis] = array(
                    "$position" => "$val **",
//                    "qty_" . $position => $qtyVal,
                );
                $tmpTrIds[$transaksi_id] = $transaksi_id;


                // mengurusi saldo berjalan...
//                $saldo_qty_berjalan += ($temX->qty_debet - $temX->qty_kredit);
                $saldo_value_berjalan = ($temX->debet_awal - $temX->kredit);
//                cekBiru();
//                $saldoBerjalan[$temX->id]['qty'] = $saldo_qty_berjalan;
//                $saldoBerjalan[$temX->id]['value'] = $saldo_value_berjalan;
                $trID[] = $temX->transaksi_id;
            }

            $tmpTrs = array();
            if (sizeof($trID) > 0) {
                $tr = new MdlTransaksi();
                $trIDS = implode("','", $trID);
                $tr->setFilters(array());
                $tr->addFilter("id in ('" . $trIDS . "')");
                $tmpTrs = $tr->lookupAll()->result();
                cekBiru($this->db->last_query());
            }



//            cekHitam($trIDS);
        }
//        arrPrint($tmpTrs);
//        matiHere();
        // region builder data transaksi yg perlu muncul
        $trDatas = array();
        foreach ($tmpTrs as $tmpTr) {
            $datas = array();
            foreach ($headerFields as $hField => $hAlias) {
                if (array_key_exists($hField, $tmpTr)) {
//                    cekBiru("ada ".$hField);
                    if (array_key_exists($tmpTr->$hField, $jenisAliases)) {
                        $datas[$hField] = $jenisAliases[$tmpTr->$hField];
                    }
                    else {
                        if (isset($tmpTr->$hField) && !is_numeric($tmpTr->$hField)) {
                            if ($tmpTr->$hField === base64_encode(base64_decode($tmpTr->$hField))) {
//                                arrPrint(unserialize(base64_decode($tmpTr->$hField)));
                                $datas[$hField] = isset($tmpTr->$hField) ? unserialize(base64_decode($tmpTr->$hField)) : "";
                            }
                            else {
                                $datas[$hField] = $tmpTr->$hField;
//                                cekHitam($hField);
                            }

                        }
                        else {
                            $datas[$hField] = $tmpTr->$hField;
                            //                            cekPink("$hField diambil dari tmpTr $hField");
                        }
                    }
                }
            }
            $trDatas[$tmpTr->id] = $datas;
        }
        // endregion builder data transaksi yg perlu muncul
//arrPrint($trDatas);
        $headerValueFields = array(
            "debet_awal" => "prev (IDR)",
            "in" => "in (IDR)",
            "out" => "out (IDR)",
//            "saldo_berjalan" => "saldo jalan (IDR)",

            "debet_akhir" => "balance (IDR)",
        );
        $headerFields = $headerFields + $headerValueFields;
        //region buld yIndex
//                arrPrint($arrMovedDetail);
//                matiHEre();
        $itemsCek = array();
        $items0 = array();
                krsort($arrProds);
//        arrPrint($arrProds);
        foreach ($arrProds as $row0) {
            $temX = array();
            foreach ($arrMovedDetail as $yparent => $ySpec) {
//                cekHitam($yparent);
                $subs0 = array();
                foreach ($ySpec as $jn => $colloumb) {

                    if (isset($row0[$jn][$colloumb])) {
                        $val = $row0[$jn][$colloumb];
                    }
                    else {
                        $val = 0;
                    }
                    $subs0[$jn] = $val;

                    $subItemCek[$yparent] = $val;
                }
                $temX[$yparent] = $subs0;
            }
            $items0[] = $temX;
            $itemsCek[] = $subItemCek;
        }
        //endregion
//arrPrint($items0);
//matiHere();
        //region label detil transaksi
        $childHeaderLabels = $this->config->item('heTransaksi_ui');
        $detailsLabel = array();
        foreach ($childHeaderLabels as $tempSpec) {
            $xxTemp = $tempSpec["steps"];
            foreach ($xxTemp as $tempLabels) {
                $keyLabel = $tempLabels["target"];
                $keyValue = $tempLabels["label"];
                $detailsLabel[$keyLabel] = $keyValue;
            }
        }
        //endregion


        $title = "";
        // data transaksi dab
//        arrPrint($tmp);
//        cekHitam("pisah");
        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
//                        arrPrint($tmp);
            foreach ($tmp as $row) {
//                arrPrint($row);
                if (isset($trDatas[$row->transaksi_id])) {
                    foreach ($trDatas[$row->transaksi_id] as $key => $val) {
                        $row->$key = $val;
                    }
                }
                $position = "debet";
                switch ($position) {
                    case "debet":
                        if ($row->kredit_awal > 0) {
                            $row->debet_awal = $row->kredit_awal * -1;
                            $row->kredit_awal = 0;
                        }
                        if ($row->kredit_akhir > 0) {
                            $row->debet_akhir = $row->kredit_akhir * -1;
                            $row->kredit_akhir = 0;
                        }
                        break;
                    case "kredit":
                        if ($row->debet_awal > 0) {
                            $row->kredit_awal = $row->debet_awal * -1;
                            $row->debet_awal = 0;
                        }
                        if ($row->debet_akhir > 0) {
                            $row->kredit_akhir = $row->debet_akhir * -1;
                            $row->debet_akhir = 0;
                        }
                        break;
                }

                $subs_r = array();
                foreach ($headerFields as $key => $label) {
                    if (array_key_exists($key, $row)) {
//                        if ((isset($row->$key)) && (!is_numeric($row->$key))) {
////                            cekHitam($row->$key);
////                            cekBiru(blobDecode($row->$key));
//                            if ($row->$key === blobDecode($row->$key)) {
//
////                                arrPrint(blobDecode($row->$key));
//                                $subs_r[$key] =blobDecode($row->$key);
//
//                            }
//                            else {
//                                $subs_r[$key] = $row->$key;
//                            }
//                        }
//                        else {
                            $subs_r[$key] = $row->$key;
//                        }
                    }
                }


//                $subs_r['saldo_qty_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['qty'] : 0;
                $subs_r['saldo_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['value'] : 0;


                $subs_r['transaksi_id'] = $row->transaksi_id;
//                $subs_r['review_details'] = $row->transaksi_id;
                $items[] = $subs_r;
                //arrPrint($subs_r);

                $title = $row->extern_nama;
            }
        }
//        arrPrint($items);
//        arrPrint($items0);

        $data = array(
            "mode" => "mutasiDetailLocker",
            "title" =>   "TEST  &nbsp;"  ,
            "subTitle" => "  ini subtitle",
            "items" => $items,
            "items2" => $items0,
            "headerFields" => $headerFields,
            "headerFields2" => $arrMovedDetail,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => $detailsLabel,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",

            "itemsCek" => $itemsCek,
            "summary" => isset($this->summaryKey) ? $this->summaryKey : array(),
            "addStyle" => isset($transaksiIDStyle) ? $transaksiIDStyle : array(),
        );

        $this->load->view("ledger", $data);

//        cekHitam($this->db->last_query());
//    arrPrint($tmp);


//    cekHitam($externID);

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
                    } else {
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
            } else {
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
                } else {
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
        } else {
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
            } else {
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
            } else {
                $hasil = "<p class='list-group-item list-group-item-info'>Tidak ada data. Ketikan nama lain</p>";
            }
        } else {
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