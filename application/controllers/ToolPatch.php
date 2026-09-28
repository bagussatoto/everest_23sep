<?php


class ToolPatch extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
        $this->load->helper("he_angka");
    }

    function index()
    {

    }

    public function taxPatch()
    {
        $this->load->model("MdlTransaksi");


        $trIDs = array(
            27283,
            27285,
            27287,
            27293,
            27303,
            27305,
            27557,
            27563,
            27567,
            27575,
            27669,
            27673,
            27677,
            27865,
            27873,
            28195,
            28479,
            28887,
            28905,
            28911,
            30933,
            30959,
            30965,
            30975,
            30981,
            31017,
            31846,
            31850,
            32268,
            32270,
            32492,
            32498,
            32516,
            32518,
            32524,
            32540,
            32572,
            32654,
            32758,
            33642,
            33648,
            33650,
            33658,
            33662,
            33682,
            33684,
            33686,
            34516,
            34590,
            35660,
            36481,
            36517,
            37640,
            37648,
            37660,
            40301,
            38700,
            40065,
            41055,
            41145,
            42919,
            42939,
            42951,
            42953,
            42995,
            43003,
            42955,
            42961,
            42977,
            43021,
            43025,
            43047,
            43081,
            43527,
            43537,
            43541,
            43563,
            48136,
            48138,
            43567,
            44918,
            45134,
            45452,
            46136,
            46158,
            45422,
            48100,
            48134,
            48168,
            48198,
            48252,
            48268,
            48260,
            48420,
            48458,
            48474,
            48480,
            48482,
            48486,
            48490,
            48492,
            48494,
            48496,
            48648,
            48684,
            52855,
            52867,
            52879,
            53225,
            53241,
            55388,
            56073,
            66819,
            66835,
            66839,
            66879,
            66961,
            66975,
            66977,
            66991,
            67027,
            67035,
            67063,
            67231,
            67235,
            67239,
            67419,
            67423,
            67429,
            67431,
            67435,
            53127,
            53139,
            53143,
            53177,
            53181,
            66945,
            67073,
            67127,
            67199,
            67215,
            67437,
            67441,
            67445,
            67449,
            67453,
            67473,
            67477,
            68381,
            67485,
            67487,
            67503,
            68391,
            67505,
            67509,
            68399,
            53167,
            55354,
            53209,
            53705,
            55412,
            55422,
            55456,
            66375,
            55462,
            57635,
            66407,
            66503,
            68431,
            66519,
            66529,
            66541,
            66597,
            66619,
            66777,
            66795,
            66857,
            66893,
            66921,
            66915,
            66925,
            66929,
            66933,
            68443,
            70559,
            70577,
            70627,
            70687,
            70689,
            70693,
            70699,
            70703,
            71195,
            71201,
            71205,
            71217,
            70625,
            72035,
            72153,
            72159,
            72319,
            72373,
            73001,
            74175,
            74189,
            73033,
            73037,
            73057,
            75417,
            75421,
            75441,
            74867,
            74877,
            74883,
            74925,
            75435,
            75459,
            75477,
            75481,
            75485,
            75493,
            75509,
            75525,
            75531,
            75543,
            75559,
            77183,
            77381,
            77609,
            85380,
            85404,
            85408,
            86016,
            86906,
            89308,
            89399,
            91531,
            91533,
            89310,
            91547,
            91537,
            91549,
            91551,
            91555,
            91563,
            91567,
            91569,
            94574,
            94584,
            94604,
            98949,
            101027,
            105993,
            109538,
            109661,
            109887,
            109893,
            109895,
            96066,
            96092,
            96104,
            96112,
            96272,
            96284,
            96306,
            96330,
            98831,
            98849,
            98855,
            98875,
            98895,
            96116,
            96336,
            97440,
            97442,
            98901,
            98921,
            98947,
            98931,
            98937,
            98955,
            101005,
            98963,
            101011,
            109897,
            100923,
            102797,
            102801,
            109809,
            109899,
            109905,
            109907,
            109915,
            109919,
            109925,
            109931,
            109937,
            109942,
            109952,
            109958,
            112844,
            112854,
            112864,
            102821,
            105089,
            105107,
            105109,
            109522,
            112878,
            112904,
            113120,
            113122,
            113126,
            113128,
            113132,
            113134,
            113356,
            113358,
            113690,
            113696,
            116494,
            116696,
            116702,
            117238,
            117246,
            117248,
            117262,
            117264,
            117270,
            117278,
            117288,
            117290,
            117296,
            117300,
            118194,
            122160,
            122170,
            122180,
            122652,
            122654,
            122658,
            122666,
            122668,
            122690,
            122694,
            122702,
            122706,
            122710,
            122714,
            122722,
            122734,
            122916,
            122928,
            122956,
            127816,
            127824,
            127826,
            127828,
            127830,
            127832,
            127834,
            127836,
            127838,
            127840,
            127842,
            127850,
            130044,
            130048,
            130052,
            130125,
            130201,
            130203,
            131553,
            131569,
            131753,
            131755,
            131757,
            131763,
            131867,
            131869,
            131871,
            132033,
            131875,
            132061,
            132085,
            132095,
            132101,
            132111,
            132131,
            132189,
            132193,
            132199,
            132217,
            132223,
            132227,
            132239,
            132245,
            133964,
            134440,
            134458,
            134476,
            134494,
            135770,
            135842,
            135848,
            135854,
            136498,
            136504,
            136670,
            136722,
            136724,
            136734,
            136740,
            136748,
            137472,
            137526,
            137596,
            137624,
            137628,
            142284,
            142340,
            142346,
            142358,
            142362,
            142364,
            142526,
            142528,
            142532,
            142534,
            142540,
            142542,
            142548,
            143730,
            143802,
            143804,
            143734,
            143758,
            143826,
            145800,
            145818,
            145824,
            145834,
            145924,
            145926,
            145928,
            145934,
            147736,
            147744,
            148418,
            148542,
            148552,
            148574,
            148582,
            148584,
            148620,
            148626,
            148630,
            148636,
            148644,
            148658,
            148664,
            148676,
            148686,
            148682,
            148694,
            148748,
            148870,
            149042,
            148888,
            149044,
            149046,
            150275,
            150277,
            150279,
            150915,
            150925,
            150927,
            150935,
            150937,
            150955,
            150949,
            150953,
            150957,
            150967,
            150969,
            150971,
            150973,
            150977,
            151011,
            151045,
            151061,
            151073,
            152369,
            152411,
            152873,
            152875,
            153029,
            153807,
            153805,
            153813,
            153809,
            153948,
            153960,
            153962,
            154042,
            154054,
            154058,
            154032,
            154062,
            154330,
            154378,
            154382,
            154390,
            154392,
            154394,
            154408,
            154529,
            154816,
            156365,
            156371,
            156375,
            156377,
            156379,
            156423,
            156427,
            156433,
            156641,
            156797,
            156803,
            156813,
            156873,
            156885,
            156887,
            159441,
            159515,
            159519,
            159523,
            159525,
            159527,
            159545,
            159547,
            159573,
            159585,
            159587,
            159589,
            159599,
            159605,
            159607,
            159609,
            159613,
            159615,
            159621,
            159623,
            160299,
            160323,
            161653,
            161661,
            161665,
            161679,
        );

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id in ('" . implode("','", $trIDs) . "')");
        $tr->setJointSelectFields("transaksi_id,main");
        $trReg = $tr->lookupDataRegistries()->result();
        showLast_query("biru");
//        arrPrint($trReg);
        $arrDataUpdate = array();
        foreach ($trReg as $spec) {
            $trid = $spec->transaksi_id;
            $main = blobDecode($spec->main);
//            arrPrint($main);
            $arrDataUpdate[$trid] = array(
                "extern_date2" => $main["dateFaktur"],
            );
        }
//        arrPrint($arrDataUpdate);


        $this->db->trans_start();


        foreach ($arrDataUpdate as $trid => $update) {
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $where = array(
                "transaksi_id" => $trid,
                "target_jenis" => "0000",
                "jenis" => "489",
            );
            $tr->updatePaymentSrc($where, $update);
            showLast_query("orange");
        }


//        matiHere(__LINE__);


        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3> SELESAI </h3>");

    }

    public function cekDiskon()
    {
        $this->load->model("MdlTransaksi");

        $jenisTr = "467";
        $supplierID = "11";
        $diskonID = "2";
        $diskonKey = "diskon_" . $diskonID;
        $subDiskonKey = "sub_diskon_" . $diskonID . "_nilai";
        $tbl_mutasi = "__rek_pembantu_subpiutangsupplier__1010020030";
        $tbl_mutasi_master = "__rek_master__1010020030";
        $arrDiskonValue = array();
        cekHere("[$subDiskonKey]");

        $tr = new MdlTransaksi();
        $tr->addFilter("suppliers_id='$supplierID'");
        $tr->addFilter("jenis='$jenisTr'");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        cekBiru(count($trTmp));

        $trIDs = array();
        $trDatas = array();
        foreach ($trTmp as $trSpec) {
            $trIDs[$trSpec->id] = $trSpec->id;
            $trDatas[$trSpec->id] = array(
                "id" => $trSpec->id,
                "nomer" => $trSpec->nomer,
            );
        }


        // region reg items
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->setJointSelectFields("transaksi_id, items");
        $tr->addFilter("transaksi_id in ('" . implode("','", $trIDs) . "')");
        $trReg = $tr->lookupDataRegistries()->result();
        showLast_query("pink");
        foreach ($trReg as $regSpec) {
            $trid = $regSpec->transaksi_id;
            $items = blobDecode($regSpec->items);

            foreach ($items as $iid => $iSpec) {
//                arrPrintWebs($iSpec);
//                if(!isset($arrDiskonValue[$trid][$diskonKey]["reg"])){
//                    $arrDiskonValue[$trid][$diskonKey]["reg"] = 0;
//                }
//                $arrDiskonValue[$trid][$diskonKey]["reg"] += isset($iSpec[$subDiskonKey]) ? $iSpec[$subDiskonKey] : 0;

            }


//            break;

        }
        // endregion reg items

        // region mutasi
        $arrWhere = array(
            "extern_id" => $diskonID,
            "extern2_id" => $supplierID,
            "jenis" => $jenisTr,
        );
        $this->db->where($arrWhere);
        $this->db->where_in('transaksi_id', $trIDs);
        $query = $this->db->get($tbl_mutasi)->result();
//        showLast_query("biru");
        cekBiru(count($query));
        foreach ($query as $querySpec) {
            $trid = $querySpec->transaksi_id;
            if (!isset($arrDiskonValue[$trid][$diskonID]["mutasi"])) {
                $arrDiskonValue[$trid][$diskonID]["mutasi"] = 0;
            }
            $arrDiskonValue[$trid][$diskonID]["mutasi"] += isset($querySpec->debet) ? $querySpec->debet : 0;

        }
        // endregion mutasi

        // region mutasi main
//        $arrWhere = array(
//            "extern_id" => $diskonID,
//            "extern2_id" => $supplierID,
//            "jenis" => $jenisTr,
//        );
//        $this->db->where($arrWhere);
//        $this->db->where_in('transaksi_id', $trIDs);
//        $query = $this->db->get($tbl_mutasi_master)->result();
////        showLast_query("biru");
//        cekBiru(count($query));
//        foreach ($query as $querySpec){
//            $trid = $querySpec->transaksi_id;
//            if(!isset($arrDiskonValue[$trid]["mutasi_master"])){
//                $arrDiskonValue[$trid]["mutasi_master"] = 0;
//            }
//            $arrDiskonValue[$trid]["mutasi_master"] += isset($querySpec->debet) ? $querySpec->debet : 0;
//
//        }
        // endregion mutasi


//        $total_item = 0;
//        $total_mutasi = 0;
//        $arrBeda = array();
//        foreach ($arrDiskonValue as $trid => $data){
//            $mutasi_item = isset($data["mutasi"]) ? $data["mutasi"] : 0;
//            $mutasi_main = isset($data["mutasi_master"]) ? $data["mutasi_master"] : 0;
//            $total_item += $mutasi_item;
//            $total_mutasi_master += $mutasi_main;
//
//            $selisih = $mutasi_item - $mutasi_main;
//            $selisih = ($selisih < 0) ? ($selisih *-1) : $selisih;
//            if($selisih > 1){
//                $arrBeda[$trid] = $data;
//            }
//        }
//        $total_selisih = $total_item - $total_mutasi_master;
//        cekMerah("total item: [$total_item],  total main: [$total_mutasi_master], total selisih: [$total_selisih]");
//        arrPrintWebs($arrBeda);


        //-----------------------------------------------
        // region realisasi diskon
        cekHijau("<h3>REALISASI DISKON...</h3>");
        $jenisTr = "3333";
//        $supplierID = "11";
//        $diskonID = "2";
        $diskonKey = "diskon_" . $diskonID;
        $subDiskonKey = "sub_diskon_" . $diskonID . "_nilai";
        $tbl_mutasi = "__rek_pembantu_subpiutangsupplier__1010020030";
        $tbl_mutasi_master = "__rek_master__1010020030";
//        $arrDiskonValue = array();
        cekHere("[$subDiskonKey]");

        $tr = new MdlTransaksi();
        $tr->addFilter("suppliers_id='$supplierID'");
        $tr->addFilter("jenis='$jenisTr'");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        cekBiru(count($trTmp));
        $trIDs = array();
        foreach ($trTmp as $trSpec) {
            $trIDs[$trSpec->id] = $trSpec->id;
        }

        // region reg items
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->setJointSelectFields("transaksi_id, items");
        $tr->addFilter("transaksi_id in ('" . implode("','", $trIDs) . "')");
        $trReg = $tr->lookupDataRegistries()->result();

        foreach ($trReg as $regSpec) {
            $trid = $regSpec->transaksi_id;
            $items = blobDecode($regSpec->items);
//arrPrint($items);
            foreach ($items as $iid => $iSpec) {
                $grn_id_realisasi = $iSpec["pym_src_id"];
                $diskon_id = isset($iSpec["diskon_id"]) ? $iSpec["diskon_id"] : $iSpec["pihakMainID"];
                $diskon_nilai = isset($iSpec["sub_diskon_supplier_nilai"]) ? $iSpec["sub_diskon_supplier_nilai"] : 0;

                if (!isset($arrDiskonRealisasi[$grn_id_realisasi][$diskon_id])) {
                    $arrDiskonRealisasi[$grn_id_realisasi][$diskon_id] = 0;
                }
                $arrDiskonRealisasi[$grn_id_realisasi][$diskon_id] += $diskon_nilai;

                if (!isset($arrDiskonRealisasiReference[$grn_id_realisasi][$diskon_id])) {
                    $arrDiskonRealisasiReference[$grn_id_realisasi][$diskon_id] = array();
                }
//                $arrDiskonRealisasiReference[$grn_id_realisasi][$diskon_id] = array(
//                    "trid_realisasi" => $trid,
//                    "nomer_realisasi" => $iSpec["nomer"],
//                );
                $arrDiskonRealisasiReference[$grn_id_realisasi][$diskon_id]["trid_realisasi"][] = $trid;
                $arrDiskonRealisasiReference[$grn_id_realisasi][$diskon_id]["nomer_realisasi"][] = $iSpec["nomer"];


            }
//            break;
        }
//        arrPrintPink($arrDiskonRealisasi);
        // endregion reg items

        // region mutasi
        $arrWhere = array(
            "extern_id" => $diskonID,
            "extern2_id" => $supplierID,
            "jenis" => $jenisTr,
        );
        $this->db->where($arrWhere);
        $this->db->where_in('transaksi_id', $trIDs);
        $query = $this->db->get($tbl_mutasi)->result();
//        showLast_query("biru");
        cekBiru(count($query));
        foreach ($query as $querySpec) {
            $trid = $querySpec->transaksi_id;
            if (!isset($arrDiskonValue[$trid]["mutasi"])) {
                $arrDiskonValue[$trid]["mutasi"] = 0;
            }
            $arrDiskonValue[$trid]["mutasi"] += isset($querySpec->debet) ? $querySpec->debet : 0;

        }
        // endregion mutasi


        // endregion realisasi diskon

        $str = "<table rules='all' style='border:1px solid black;' width='100%'>";
        $str .= "<tr>";
        $str .= "<th>no</th>";
        $str .= "<th>trid</th>";
        $str .= "<th>grn</th>";
        $str .= "<th>trid realisasi</th>";
        $str .= "<th>nomer realisasi</th>";
        $str .= "<th>masuk diskon</th>";
        $str .= "<th>realisasi klaim</th>";
        $str .= "<th>belum realisasi</th>";
        $str .= "<th>over realisasi</th>";
        $str .= "<tr>";
        $no = 0;
        $diskon_masuk_total = 0;
        $diskon_realisasi_total = 0;
        $over_realisasi_total = 0;
        $belum_realisasi_total = 0;
        foreach ($trDatas as $trid => $spec) {
//            $trid_realisasi = isset($arrDiskonRealisasiReference[$trid][$diskonID]["trid_realisasi"]) ? $arrDiskonRealisasiReference[$trid][$diskonID]["trid_realisasi"] : 0;
//            $nomer_realisasi = isset($arrDiskonRealisasiReference[$trid][$diskonID]["nomer_realisasi"]) ? $arrDiskonRealisasiReference[$trid][$diskonID]["nomer_realisasi"] : 0;
            $idd = "";
            if (isset($arrDiskonRealisasiReference[$trid][$diskonID]["trid_realisasi"])) {
                foreach ($arrDiskonRealisasiReference[$trid][$diskonID]["trid_realisasi"] as $id) {
                    if ($idd == "") {
                        $idd = "$id";
                    }
                    else {
                        $idd .= ",<br>$id";
                    }
                }
            }
            $inn = "";
            if (isset($arrDiskonRealisasiReference[$trid][$diskonID]["nomer_realisasi"])) {
                foreach ($arrDiskonRealisasiReference[$trid][$diskonID]["nomer_realisasi"] as $in) {
                    if ($inn == "") {
                        $inn = "$in";
                    }
                    else {
                        $inn .= ",<br>$in";
                    }
                }
            }
            $trid_realisasi = $idd;
            $nomer_realisasi = $inn;

            $diskon_masuk = isset($arrDiskonValue[$trid][$diskonID]["mutasi"]) ? $arrDiskonValue[$trid][$diskonID]["mutasi"] : 0;
            $diskon_realisasi = isset($arrDiskonRealisasi[$trid][$diskonID]) ? $arrDiskonRealisasi[$trid][$diskonID] : 0;
            if ($diskon_realisasi > $diskon_masuk) {
                $over_realisasi = $diskon_realisasi - $diskon_masuk;
                $bgcolor = "red";
            }
            elseif ($diskon_masuk > $diskon_realisasi) {
                $belum_realisasi = $diskon_masuk - $diskon_realisasi;
                $bgcolor = "yellow";
            }
            else {
                $over_realisasi = 0;
                $belum_realisasi = 0;
                $bgcolor = "";
            }
            $diskon_masuk_total += $diskon_masuk;
            $diskon_realisasi_total += $diskon_realisasi;
            $over_realisasi_total += $over_realisasi;
            $belum_realisasi_total += $belum_realisasi;


            $no++;
            $str .= "<tr style='background-color:$bgcolor;'>";
            $str .= "<th>$no</th>";
            $str .= "<th>$trid</th>";
            $str .= "<th>" . $spec["nomer"] . "</th>";
            $str .= "<th>$trid_realisasi</th>";
            $str .= "<th>$nomer_realisasi</th>";
            $str .= "<th>$diskon_masuk</th>";
            $str .= "<th>$diskon_realisasi</th>";
            $str .= "<th>$belum_realisasi</th>";
            $str .= "<th>$over_realisasi</th>";
            $str .= "<tr>";
        }
        $str .= "<tr>";
        $str .= "<th>-</th>";
        $str .= "<th>-</th>";
        $str .= "<th>-</th>";
        $str .= "<th>-</th>";
        $str .= "<th>-</th>";
        $str .= "<th>$diskon_masuk_total</th>";
        $str .= "<th>$diskon_realisasi_total</th>";
        $str .= "<th>$belum_realisasi_total</th>";
        $str .= "<th>$over_realisasi_total</th>";
        $str .= "<tr>";

        $str .= "</table>";
        echo $str;


    }

    public function salahpPiutangPos()
    {
        $this->load->model("MdlTransaksi");

        $jenisTr = "5823spd";
        $tbl_mutasi = "__rek_pembantu_customer__1010020010";

        $tr = new MdlTransaksi();
        $tr->addFilter("jenis='$jenisTr'");
        $tr->addFilter("trash_4='0'");
        $tr->addFilter("pembayaran<>'cash'");
        $trTmp = $tr->lookupAll()->result();
//        showLast_query("biru");
//        cekBiru(count($trTmp));
        $trIDs = array();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $trIDs[$trSpec->id] = $trSpec->id;
            }
        }

        $arrWhere = array(
            "jenis" => $jenisTr,
        );
        $this->db->where($arrWhere);
        $this->db->where_in('transaksi_id', $trIDs);
        $query = $this->db->get($tbl_mutasi)->result();
//        showLast_query("kuning");
//        cekKuning(count($query));
        foreach ($query as $querySpec) {
            $trid = $querySpec->transaksi_id;
            $konsumen_id = $querySpec->extern_id;
            $konsumen_nama = $querySpec->extern_nama;
            $transaksi_no = $querySpec->transaksi_no;
            $dtime = $querySpec->dtime;

            //----------------
            $arrPiutangSalah[$trid]["trid"] = $trid;
            $arrPiutangSalah[$trid]["dtime"] = $dtime;
            $arrPiutangSalah[$trid]["cusid"] = $konsumen_id;
            $arrPiutangSalah[$trid]["cusnama"] = $konsumen_nama;
            $arrPiutangSalah[$trid]["nomer"] = $transaksi_no;
            if (!isset($arrPiutangSalah[$trid]["debet"])) {
                $arrPiutangSalah[$trid]["debet"] = 0;
            }
            $arrPiutangSalah[$trid]["debet"] += isset($querySpec->debet) ? $querySpec->debet : 0;

            //----------------
            $arrPiutangSalahKonsumen[$konsumen_id]["id"] = $konsumen_id;
            $arrPiutangSalahKonsumen[$konsumen_id]["nama"] = $konsumen_nama;
            $arrPiutangSalahKonsumen[$konsumen_id]["name"] = $konsumen_nama;
            $arrPiutangSalahKonsumen[$konsumen_id]["jml"] = 1;
            $arrPiutangSalahKonsumen[$konsumen_id]["qty"] = 1;
            $arrPiutangSalahKonsumen[$konsumen_id]["reference_nomer"] = "";
            $arrPiutangSalahKonsumen[$konsumen_id]["keterangan_detail"] = "koreksi uang muka penjualan tunai (pos) pada piutang usaha dengan konsumen $konsumen_nama";
            if (!isset($arrPiutangSalahKonsumen[$konsumen_id]["hpp"])) {
                $arrPiutangSalahKonsumen[$konsumen_id]["hpp"] = 0;
            }
            $arrPiutangSalahKonsumen[$konsumen_id]["hpp"] += isset($querySpec->debet) ? $querySpec->debet : 0;

            if (!isset($arrPiutangSalahKonsumen[$konsumen_id]["harga"])) {
                $arrPiutangSalahKonsumen[$konsumen_id]["harga"] = 0;
            }
            $arrPiutangSalahKonsumen[$konsumen_id]["harga"] += isset($querySpec->debet) ? $querySpec->debet : 0;
            //----------------


        }
//        cekHere(count($arrPiutangSalah));
//        arrPrintWebs($arrPiutangSalah);

        // region view tabel 1
        $str = "<table rules='all' style='border:1px solid black;' width='100%'>";
        $str .= "<tr>";
        $str .= "<th>no</th>";
        $str .= "<th>dtime</th>";
        $str .= "<th>cus id</th>";
        $str .= "<th>cus nama</th>";
        $str .= "<th>trid</th>";
        $str .= "<th>nomer</th>";
        $str .= "<th>debet</th>";
        $str .= "<tr>";

        $no = 0;
        $total_debet = 0;
        foreach ($arrPiutangSalah as $trid => $spec) {
            $total_debet += $spec["debet"];
            $no++;
            $str .= "<tr style='background-color:$bgcolor;'>";
            $str .= "<th>$no</th>";
            $str .= "<th>" . $spec["dtime"] . "</th>";
            $str .= "<th>" . $spec["cusid"] . "</th>";
            $str .= "<th>" . $spec["cusnama"] . "</th>";
            $str .= "<th>$trid</th>";
            $str .= "<th>" . $spec["nomer"] . "</th>";
            $str .= "<th>" . $spec["debet"] . "</th>";
            $str .= "<tr>";
        }
        $str .= "<tr>";
        $str .= "<th>-</th>";
        $str .= "<th>-</th>";
        $str .= "<th>-</th>";
        $str .= "<th>-</th>";
        $str .= "<th>-</th>";
        $str .= "<th>-</th>";
        $str .= "<th>$total_debet</th>";
        $str .= "<tr>";
        $str .= "</table>";
        $str .= "<br><br><br>";
        if ($_GET["s"] == 1) {
            echo $str;
        }
        // endregion view tabel

        // region view tabel 2
        $str = "<table rules='all' style='border:1px solid black;' width='100%'>";
        $str .= "<tr>";
        $str .= "<th>no</th>";
        $str .= "<th>cus id</th>";
        $str .= "<th>cus nama</th>";
        $str .= "<th>debet</th>";
        $str .= "<tr>";

        $no = 0;
        $total_debet_cus = 0;
        foreach ($arrPiutangSalahKonsumen as $trid => $spec) {
            $total_debet_cus += $spec["harga"];
            $no++;
            $str .= "<tr style='background-color:$bgcolor;'>";
            $str .= "<th>$no</th>";
            $str .= "<th>" . $spec["id"] . "</th>";
            $str .= "<th>" . $spec["nama"] . "</th>";
            $str .= "<th>" . $spec["harga"] . "</th>";
            $str .= "<tr>";
        }
        $str .= "<tr>";
        $str .= "<th>-</th>";
        $str .= "<th>-</th>";
        $str .= "<th>-</th>";
        $str .= "<th>$total_debet_cus</th>";
        $str .= "<tr>";
        $str .= "</table>";
        $str .= "<br><br><br>";
        if ($_GET["s"] == 1) {
            echo $str;
        }
        // endregion view tabel

//        arrPrint($arrPiutangSalahKonsumen);

        return $arrPiutangSalahKonsumen;
    }

    public function salahSaldoKas()
    {
        $this->load->model("Coms/ComRekeningPembantuKas");

        $id = "1159";
        $rekening = "1010010010";
        $cabang_id = "-1";
        $debet_seharusnya = "31260359.36";
        $arrPiutangSalahKonsumen = array();

        $cc = new ComRekeningPembantuKas();
        $cc->addFilter("periode='forever'");
        $cc->addFilter("rekening='$rekening'");
        $cc->addFilter("extern_id='$id'");
        $cc->addFilter("cabang_id='$cabang_id'");
        $ccTmp = $cc->fetchBalances($rekening);
        foreach ($ccTmp as $ccStep) {
            $extern_id = $ccStep->extern_id;
            $debet = $ccStep->debet;
            // saldo aplikasi dikurangi seharusnya (aplikasi lebih bayar dari bank riil)
            $nilai_adj = $debet - $debet_seharusnya;
            $arrPiutangSalahKonsumen[$extern_id] = array(
                "id" => $extern_id,
                "nama" => $ccStep->extern_nama,
                "name" => $ccStep->extern_nama,
                "hpp" => $nilai_adj,
                "harga" => $nilai_adj,
                "jml" => 1,
                "qty" => 1,
                "reference_nomer" => "",
                "keterangan_detail" => $keterangan,
            );
        }


        return $arrPiutangSalahKonsumen;
    }

    //-------------------------------------------
    public function koreksiAdjustment()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlProduk2");


        // load transaksi opname, mengambil data yang akan dieksekusi
//        $cabangID = "-1";
//        $cabangNama = "PUSAT/DC";
//        $gudangID = "-1";
//        $gudangNama = "default warehouse at branch #-1";
        $cabangID = "1";
        $cabangNama = "CABANG 1";
        $gudangID = "-10";
        $gudangNama = "default warehouse at branch #1";

//        $cabang2ID = "-1";
//        $cabang2Nama = "pusat dc";
//        $gudang2ID = "-1";
//        $gudang2Nama = "default warehouse at branch #-1";

        $olehID = "100";
        $olehNama = "system";
//        $tokoID = "1001";
//        $tokoNama = "JODOMART";
//        $supplierID = "14";
//        $supplierNama = "PT. SAMSUNG ELECTRONIC INDONESIA";
//        $supplierID = "-1";
//        $supplierNama = "DC/PUSAT";
//        $pihakID = "14";
//        $pihakNama = "PT. SAMSUNG ELECTRONIC INDONESIA";
//        $pihakID = "473";
//        $pihakNama = "ISHWAR MANWANI";
        $customerID = "3951";
        $customerNama = "BPK. MANJID PUNJABI";
        $pihakID = "3951";
        $pihakNama = "BPK. MANJID PUNJABI";
        $jenis = "99999";
        $this->jenisTr = $jenisTr = "99999";
        $jenisTrMaster = "99999";
        $dtime = date("Y-m-d H:i:s");
        $fulldate = date("Y-m-d");
        $ppnFactor = 11;
        $divID = 18;
        $cash_account = 0;
        $cash_account_nama = "";
        $referenceID = 0;
        $referenceNomer = 0;
        $referenceJenis = "";
        $modul_transaksi = "adjustment";
        $tCodeTargetJenisTransaksi = $target_transaksi = $jenisTrMaster;
        $keterangan = "";
        $projectID = "166";
        $projectName = "Bpk Majid Punjabi";


        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            $arrDataDetail = array(
                "2010050" => array(
                    "id" => "2010050",
                    "nama" => "HUTANG KE KONSUMEN",
                    "name" => "HUTANG KE KONSUMEN",
                    "hpp" => "81081081",
                    "harga" => "81081081",
                    "jml" => "1",
                    "qty" => "1",
                    "reference_nomer" => "",
                    "keterangan_detail" => $keterangan,
                ),
                "4010" => array(
                    "id" => "4010",
                    "nama" => "PENJUALAN",
                    "name" => "PENJUALAN",
                    "hpp" => "81081081",
                    "harga" => "81081081",
                    "jml" => "1",
                    "qty" => "1",
                    "reference_nomer" => "",
                    "keterangan_detail" => $keterangan,
                ),
                "1010070030" => array(
                    "id" => "1010070030",
                    "nama" => "PIUTANG USAHA KONTIJENSI",
                    "name" => "PIUTANG USAHA KONTIJENSI",
                    "hpp" => "90000000",
                    "harga" => "90000000",
                    "jml" => "1",
                    "qty" => "1",
                    "reference_nomer" => "",
                    "keterangan_detail" => $keterangan,
                ),
                "4030" => array(
                    "id" => "4030",
                    "nama" => "PENJUALAN KONTIJENSI",
                    "name" => "PENJUALAN KONTIJENSI",
                    "hpp" => "90000000",
                    "harga" => "90000000",
                    "jml" => "1",
                    "qty" => "1",
                    "reference_nomer" => "",
                    "keterangan_detail" => $keterangan,
                ),
            );


//            $arrDataDetail = $this->salahSaldoKas();
//            arrPrintWebs($arrDataDetail);
//            unset($arrDataDetail["2523"]);
        }
        else {
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("cabang_id='$cabangID'");
//            $tr->addFilter("target_jenis='483'");
            $tr->addFilter("label='hutang dagang'");
            $tr->addFilter("sisa>'0'");
            $trPym = $tr->lookupPaymentSrcByJenis('483')->result();
            showLast_query("biru");
            cekBiru(count($trPym));
            if (sizeof($trPym) > 0) {
                foreach ($trPym as $pymSpec) {
                    $arrDataDetail[$pymSpec->transaksi_id] = array(
                        "id_tbl" => $pymSpec->id,
                        "id" => $pymSpec->transaksi_id,
                        "nama" => $pymSpec->nomer,
                        "name" => $pymSpec->nomer,
                        "hpp" => $pymSpec->ppn_sisa,
                        "harga" => $pymSpec->ppn_sisa,
                        "sisa" => $pymSpec->sisa,
                        "jml" => "1",
                        "qty" => "1",
                        "reference_id" => $pymSpec->extern3_id,
                        "reference_nomer" => $pymSpec->extern3_nama,
                        "extern_id" => $pymSpec->extern_id,
                        "extern_nama" => $pymSpec->extern_nama,
                        "pihakID" => $pymSpec->extern_id,
                        "pihakName" => $pymSpec->extern_nama,
                        "pihakNama" => $pymSpec->extern_nama,
                        "keterangan_detail" => "transaksi pembelian service/jasa project, ganti metode PPN MASUKAN di belakang saat pembayaran.",
                    );
                }
            }
        }

//        mati_disini(__LINE__);

        $this->db->trans_start();


        $mainGate = array(
            "olehID" => $olehID,
            "olehName" => $olehNama,
            "sellerID" => "",
            "sellerName" => "",
            "pihakID" => $pihakID,
            "pihakName" => $pihakNama,
            "supplierID" => $supplierID,
            "supplierNama" => $supplierNama,
            "supplier2ID" => $supplier2ID,
            "supplier2Nama" => $supplier2Nama,
            "customerID" => $customerID,
            "customerNama" => $customerNama,
            "placeID" => $cabangID,
            "placeName" => $cabangNama,
            "cabangID" => $cabangID,
            "cabangName" => $cabangNama,
            "gudangID" => $gudangID,
            "gudangName" => $gudangNama,
            "place2ID" => $cabang2ID,
            "place2Name" => $cabang2Nama,
            "cabang2ID" => $cabang2ID,
            "cabang2Name" => $cabang2Nama,
            "gudang2ID" => $gudang2ID,
            "gudang2Name" => $gudang2Nama,
            "tokoEmail" => "",
            "tokoID" => $tokoID,
            "tokoNama" => $tokoNama,
            "jenisTr" => $jenis,
            "jenisTrMaster" => $jenisTrMaster,
            "jenisTrTop" => $jenis,
            "jenisTrName" => "",
            "stepNumber" => "",
            "stepCode" => $jenis,
            "dtime" => $dtime,
            "fulldate" => $fulldate,
            "ppnFactor" => $ppnFactor,
            "dummyElement" => "yes",
            "dummyElement__label" => "yes",
            "dummyElement__name" => "yes",
            "divID" => $divID,
            "jenis" => $jenis,
            "transaksi_jenis" => $jenis,
            "next_step_code" => $jenis,
            "next_group_code" => "o_holding",
            "step_number" => 1,
            "step_current" => 1,
            "longitude" => "",
            "lattitude" => "",
            "accuracy" => "",
            "description" => $keterangan,
            "keterangan" => $keterangan,

            "cash_account" => $cash_account,
            "cash_account_nama" => $cash_account_nama,
            "cash_account__label" => $cash_account_nama,
//            "referenceID" => $referenceID,
//            "referenceNomer" => $referenceNomer,
//            "referenceJenis" => $referenceJenis,
//            "reference_id" => $referenceID,
//            "reference_nomer" => $referenceNomer,
//            "reference_jenis" => $referenceJenis,
            "projectID" => $projectID,
            "projectName" => $projectName,

        );
        $tableIn = array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",
                "customers_id" => "pihakID",
                "customers_nama" => "pihakName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "new_net2",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "toko_id" => "tokoID",
                "toko_nama" => "tokoName",
                "reference_id" => "referenceID",
                "reference_nomer" => "referenceNomer",
                "reference_jenis" => "referenceJenis",

            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
            ),
        );

        $harga_pokok = 0;
        $persediaan_produk = 0;
        $hutang_ke_pusat = 0;
        $piutang_cabang = 0;
        $laba_lain_lain = 0;
        $hutang_dagang = 0;
        $penjualan = 81081081;
        $hutang_ke_konsumen = 81081081;
        $hutang_ke_konsumen_nppn = 90000000;

        $detailGate = array();
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            $total_nilai = 0;
            $arrProdukDatas = array();
            $arrprodukIDs = $arrDataDetail;
            $arrprodukIDKey = array_keys($arrprodukIDs);
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                $pr = new MdlProduk2();
                $pr->addFilter("id in ('" . implode("','", $arrprodukIDKey) . "')");
                $prTmp = $pr->lookupAll()->result();
                showLast_query("biru");
                foreach ($prTmp as $prSpec) {
                    $arrProdukDatas[$prSpec->id] = $prSpec;
                }
                foreach ($arrProdukDatas as $pid => $specc) {
                    $pnama = $specc->nama;
                    $kode = $specc->kode;
                    $barcode = $specc->barcode;
                    $satuan = $specc->satuan;
                    $jml = $arrprodukIDs[$pid]["jml"];
                    $qty = $arrprodukIDs[$pid]["qty"];
                    $hpp = $arrprodukIDs[$pid]["hpp"];
                    $harga = $arrprodukIDs[$pid]["harga"];
                    $target_id = $arrprodukIDs[$pid]["target_id"];
//                $sub_hpp = $hpp * $jml;
//                $sub_harga = $harga * $jml;
                    $sub_hpp = $hpp * $jml;
                    $sub_harga = $harga * $jml;
                    $total_nilai += $sub_hpp;
                    $detailGate[$pid] = array(
                        "handler" => "opname/_processSelectProduct",
                        "target_id" => $target_id,
                        "id" => $pid,
                        "jml" => $jml,
                        "harga" => $harga,
                        "subtotal" => 0,
                        "satuan" => "gram",
                        "discount_persen" => 0,
                        "discount_qty" => 0,
                        "hpp" => $hpp,
                        "nama" => $pnama,
                        "kode" => $kode,
                        "barcode" => $barcode,
                        "no_part" => 0,
                        "label" => "",
                        "ppn" => 0,
                        "stok" => 0,
                        "debet" => 0,
                        "kredit" => 0,
                        "qty_selisih" => 0,
                        "qty" => $qty,
                        "name" => $pnama,
                        "sub_harga" => $sub_harga,
                        "sub_subtotal" => $sub_harga,
                        "sub_discount_persen" => 0,
                        "sub_discount_qty" => 0,
                        "sub_hpp" => $sub_hpp,
                        "sub_no_part" => 0,
                        "sub_ppn" => 0,
                        "sub_stok" => 0,
                        "sub_debet" => 0,
                        "sub_kredit" => 0,
                        "sub_qty_selisih" => 0,

                        "next_substep_code" => $jenis,
                        "next_subgroup_code" => "o_holding",
                        "sub_step_number" => 1,
                        "sub_step_current" => 1,
                    );
                }
            }
            else {
                // kalau transaksi detailnya
                foreach ($arrprodukIDs as $pid => $specc) {
                    $sub_hpp = $specc["jml"] * $specc["hpp"];
                    $sub_harga = $specc["jml"] * $specc["harga"];
                    $total_nilai += $sub_harga;
                    $detailGate[$pid] = array(
                        "handler" => "",
                        "id" => $pid,
                        "jml" => $specc["jml"],
                        "harga" => $specc["harga"],
                        "subtotal" => 0,
                        "satuan" => "",
                        "discount_persen" => 0,
                        "discount_qty" => 0,
                        "hpp" => $specc["hpp"],
                        "nama" => $specc["nama"],
                        "kode" => "",
                        "barcode" => "",
                        "no_part" => 0,
                        "label" => "",
                        "ppn" => 0,
                        "stok" => 0,
                        "debet" => 0,
                        "kredit" => 0,
                        "qty_selisih" => 0,
                        "qty" => $specc["qty"],
                        "name" => $specc["name"],
                        "sub_harga" => $sub_harga,
                        "sub_subtotal" => $sub_harga,
                        "sub_discount_persen" => 0,
                        "sub_discount_qty" => 0,
                        "sub_hpp" => $sub_hpp,
                        "sub_no_part" => 0,
                        "sub_ppn" => 0,
                        "sub_stok" => 0,
                        "sub_debet" => 0,
                        "sub_kredit" => 0,
                        "sub_qty_selisih" => 0,

                        "next_substep_code" => $jenis,
                        "next_subgroup_code" => "o_holding",
                        "sub_step_number" => 1,
                        "sub_step_current" => 1,
                    );
                    foreach ($specc as $dkey => $dval) {
                        $detailGate[$pid][$dkey] = $dval;
                    }

//                    break;
                }
            }

            //region 1 produk
            $mainHarga = 0;
            $harga_pokok = 0;
            $persediaan_produk = 0;
            $hutang_dagang = 0;
            $hutang_dagang_detail_1 = 0;
            $hutang_dagang_detail_2 = 0;
            $hutang_ke_pusat = 0;
            $piutang_cabang = 0;
            $piutang_dagang = 0;
            $laba_lain_lain = 0;
            $laba_ditahan = 0;
            $ppn_masukan = 0;
            $ppn_masukan_jasa = 0;
            $modal = $total_nilai;
            $kas = 0;
            $kas_minus = $total_nilai;
//            $hutang_ke_konsumen = 0;
//            $hutang_ke_konsumen_noppn = 0;
            $titipan_tanpa_relasi = 0;
            $pendapatan_lain_lain = 0;
            $piutang_pembelian_masuk = 0;
            $piutang_pembelian_keluar = 0;
            $biaya_usaha = 0;
            foreach ($detailGate as $pid => $spec) {
                foreach ($mainGate as $key => $val) {
                    $spec[$key] = $val;
                }

//                $spec["produk_qty_item"] = -$spec["jml"];
//                $spec["produk_nilai_item"] = $spec["harga"];
//                $spec["produk_nilai_minus_item"] = $spec["harga"];
//                $spec["persediaan_produk_item"] = -$spec["sub_harga"];
//
//                $spec["produk_qty_item_target"] = $spec["jml"];
//                $spec["produk_nilai_item_target"] = $spec["harga"];
//                $spec["produk_nilai_minus_item_target"] = $spec["harga"];
//                $spec["persediaan_produk_item_target"] = $spec["sub_harga"];

//                $spec["hutang_ke_konsumen_noppn_minus"] = $spec["sub_harga"];
//                $spec["piutang_dagang"] = $spec["sub_harga"];

                $detailGate[$pid] = $spec;

                foreach ($tableIn["detail"] as $ikey => $ival) {
                    $tableIn_detail[$pid][$ikey] = isset($spec[$ival]) ? $spec[$ival] : "";
                }
            }
            //endregion
        }
        else {
            // region multi produk
            $itemID_blacklist = array("50648");
            $selected_jenis = array(
//                "759",
                "1119",
            );

            // tabel hpp avg > jual
            $pid_avg = array();
            $p_avg = $this->db->get("fifo_avg_sementara")->result();
            foreach ($p_avg as $pSpec) {
                $pid_avg[$pSpec->produk_id] = $pSpec->produk_id;
            }


            $arrwhere = array(
                "cabang_id" => "$cabangID",
                "toko_id" => "$tokoID",
                "gudang_id" => "$gudangID",
            );
            $this->db->where($arrwhere);
            $this->db->where_in("produk_id", $pid_avg);
            $p_rek_avg_fifo = $this->db->get("fifo_avg")->result();
            $p_rek_avg_fifo_hpp = array();
            foreach ($p_rek_avg_fifo as $pSpec) {
                $p_rek_avg_fifo_hpp[$pSpec->produk_id] = $pSpec->hpp;
            }
//arrPrint($p_rek_avg_fifo_hpp);

            // tabel rek pembantu produk
            $arrwhere = array(
                "cabang_id" => "$cabangID",
                "toko_id" => "$tokoID",
                "gudang_id" => "$gudangID",
                "jenis" => "759",
//                "jenis" => "1119",
            );
            $this->db->where($arrwhere);
            $this->db->where_in("produk_id", $pid_avg);
            $p_rek = $this->db->get("__rek_pembantu_produk__1010030")->result();
            showLast_query("biru");
            $p_rek_salah = array();
            foreach ($p_rek as $pSpec) {
                $p_rek_salah[$pSpec->produk_id]["produk_id"] = $pSpec->produk_id;
                $p_rek_salah[$pSpec->produk_id]["produk_nama"] = $pSpec->produk_nama;
                $p_rek_salah[$pSpec->produk_id]["hpp_avg"] = $pSpec->harga;


                if (!isset($p_rek_salah[$pSpec->produk_id]["qty"])) {
                    $p_rek_salah[$pSpec->produk_id]["qty"] = 0;
                }
                $p_rek_salah[$pSpec->produk_id]["qty"] += $pSpec->qty_kredit;
//                $p_rek_salah[$pSpec->produk_id]["qty"] += $pSpec->qty_debet;


                if (!isset($p_rek_salah[$pSpec->produk_id]["nilai"])) {
                    $p_rek_salah[$pSpec->produk_id]["nilai"] = 0;
                }
                $p_rek_salah[$pSpec->produk_id]["nilai"] += $pSpec->kredit;
//                $p_rek_salah[$pSpec->produk_id]["nilai"] += $pSpec->debet;

            }
//            arrPrintPink($p_rek_salah);
            foreach ($p_rek_salah as $pid => $pSpec) {
                $nama = $pSpec["produk_nama"];
                if (!in_array($pSpec['produk_id'], $itemID_blacklist)) {
                    $jml = $pSpec["qty"];
//                    $nilai = $pSpec["nilai"];
//                    $hpp = $nilai/$jml;
//                    $hpp = $pSpec["hpp_avg"];
                    $hpp = $p_rek_avg_fifo_hpp[$pid];
                    $nilai = $pSpec["qty"] * $hpp;

                    $persediaan_produk += $nilai;
                    $harga_pokok += $nilai;
//                    $laba_lain_lain += $nilai;

                    $detailGate[$pid] = array(
                        //region detail/items
                        "handler" => "opname/_processSelectProduct",
                        "id" => $pid,
                        "jml" => $jml,
                        "harga" => $hpp,
                        "subtotal" => 33,
                        "satuan" => "gram",
                        "discount_persen" => 0,
                        "discount_qty" => 0,
                        "hpp" => $hpp,
                        "nama" => "$nama",
                        "kode" => "",
                        "barcode" => "",
                        "no_part" => 0,
                        "label" => "",
                        "ppn" => 3,
                        "stok" => 26750,
                        "debet" => 0,
                        "kredit" => 0,
                        "qty_selisih" => 0,
                        "qty" => $jml,
                        "name" => "$nama",
                        "sub_harga" => $nilai,
                        "sub_subtotal" => $nilai,
                        "sub_discount_persen" => 0,
                        "sub_discount_qty" => 0,
                        "sub_hpp" => $nilai,
                        "sub_no_part" => 0,
                        "sub_ppn" => 0,
                        "sub_stok" => 0,
                        "sub_debet" => 0,
                        "sub_kredit" => 0,
                        "sub_qty_selisih" => 0,

                        "next_substep_code" => $jenis,
                        "next_subgroup_code" => "o_holding",
                        "sub_step_number" => 1,
                        "sub_step_current" => 1,
                        //endregion

                        "produk_qty_item" => -$jml,
                        "produk_nilai_item" => $hpp,
                        "produk_nilai_minus_item" => $hpp,
                        "persediaan_produk_item" => -$nilai,
                    );
                    foreach ($detailGate as $pid => $spec) {
                        foreach ($mainGate as $key => $val) {
                            $spec[$key] = $val;
                        }
                        $detailGate[$pid] = $spec;
                        foreach ($tableIn["detail"] as $ikey => $ival) {
                            $tableIn_detail[$pid][$ikey] = isset($spec[$ival]) ? $spec[$ival] : "";
                        }
                    }
                }
            }
//            mati_disini(__LINE__);


            // endregion multi produk
        }


        foreach ($tableIn["master"] as $key => $val) {
            $tableIn_master[$key] = isset($mainGate[$val]) ? $mainGate[$val] : "";
        }


        $mainGate["hpp"] = -$harga_pokok;
        $mainGate["persediaan_produk"] = -$persediaan_produk;
        $mainGate["hutang_ke_pusat"] = $hutang_ke_pusat;
        $mainGate["piutang_cabang_minus_3"] = $piutang_cabang;
        $mainGate["piutang_dagang"] = -$piutang_dagang;
        $mainGate["laba_lain_lain"] = -$laba_lain_lain;
        $mainGate["hutang_dagang"] = -$hutang_dagang;
        $mainGate["hutang_dagang_detail_1"] = $hutang_dagang_detail_1;
        $mainGate["hutang_dagang_detail_2"] = -$hutang_dagang_detail_2;
        $mainGate["modal"] = -$modal;
        $mainGate["ppn_masukan"] = $ppn_masukan;
        $mainGate["ppn_masukan_jasa"] = -$ppn_masukan_jasa;
        $mainGate["kas"] = $kas;
        $mainGate["kas_minus"] = -$kas_minus;
        $mainGate["hutang_ke_konsumen"] = $hutang_ke_konsumen;
        $mainGate["hutang_ke_konsumen_noppn"] = $hutang_ke_konsumen_noppn;
        $mainGate["hutang_ke_konsumen_noppn_minus"] = -$hutang_ke_konsumen_noppn;
        $mainGate["titipan_tanpa_relasi"] = $titipan_tanpa_relasi;
        $mainGate["titipan_dengan_relasi"] = -$titipan_dengan_relasi;
        $mainGate["piutang_pembelian_masuk"] = $piutang_pembelian_masuk;
        $mainGate["piutang_pembelian_keluar"] = -$piutang_pembelian_keluar;
        $mainGate["laba_ditahan"] = $laba_ditahan;
        $mainGate["biaya_usaha"] = -$biaya_usaha;

        $um_pph23 = (15 / 100) * $pendapatan_lain_lain;
        $creditnote_supplier = $pendapatan_lain_lain - $um_pph23;

        $mainGate["pendapatan_lain_lain"] = -$pendapatan_lain_lain;
        $mainGate["klaim_ke_supplier_masuk"] = -$pendapatan_lain_lain;
        $mainGate["klaim_ke_supplier_keluar"] = $pendapatan_lain_lain;
        $mainGate["credit_note_supplier"] = -$creditnote_supplier;
        $mainGate["um_pph_23"] = -$um_pph23;
        $mainGate["penjualan"] = -$penjualan;
        $mainGate["piutang_usaha_kontijensi"] = $hutang_ke_konsumen_nppn;
        $mainGate["penjualan_kontijensi"] = $hutang_ke_konsumen_nppn;

//
//        arrPrintCyan($mainGate);
//        arrPrintHitam($detailGate);
//        mati_disini(__LINE__);


        $this->cCode = $cCode = "_TR_" . $jenis;
        $this->cCodeData[$cCode] = array(
            "main" => $mainGate,
            "items" => $detailGate,
            "tableIn_master" => $tableIn_master,
            "tableIn_detail" => $tableIn_detail,
        );
        $componentsDetailLoop = true;
        $comsPrefix = "Com";
        $comsLocation = "Coms";
        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
        $runCliComponentDetail = false;
        $jenisTrTarget = $jenis;

        //--------------------------
        $preProcessor = array(
            "master" => array(),
            "detail" => array(
                array(
                    "comName" => "FifoAverage",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "name",
                        "produk_qty" => "qty_kredit",
                        "gudang_id" => "gudangID",
                        "toko_id" => "tokoID",
                        "toko_nama" => "tokoName",
                    ),
                    "resultParams" => array(
//                        "items" => array(
//                            "kredit_rsltItems" => "hpp",
//                        ),
                    ),
                    "srcGateName" => "items",
                    "srcRawGateName" => "items",
                ),
            ),
        );
        $components = array(
            "master" => array(

                // JURNAL 1
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                        "4010" => "penjualan",// penjualan, rek pembantu penjualan project
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "Rekening",
                    "loop" => array(
                        "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                        "4010" => "penjualan",// penjualan, rek pembantu penjualan project
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuPenjualan",
                    "loop" => array(
                        "4010" => "penjualan",// penjualan, rek pembantu penjualan project
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".4010030",
                        "extern_nama" => ".penjualan project",
                        "extern2_id" => ".0",
                        "extern2_nama" => "",
                        "extern4_id" => "pihakID",
                        "extern4_nama" => "pihakName",
                        "jenis" => "jenisTr",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuPenjualanProject",
                    "loop" => array(
                        "4010" => "penjualan",// penjualan, rek pembantu penjualan project
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "projectID",//project
                        "extern_nama" => "projectName",//project
                        "extern2_id" => ".4010030",
                        "extern2_nama" => ".penjualan project",
                        "extern3_id" => ".0",//kontrak
                        "extern3_nama" => "note",//kontrak
                        "extern4_id" => "pihakID",
                        "extern4_nama" => "pihakName",
                        "jenis" => "jenisTr",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuCustomer",
                    "loop" => array(
                        "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".2010050060",
                        "extern_nama" => ".Uang Muka Konsumen",
                        "jenis" => "jenisTr",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuCustomerDetail",
                    "loop" => array(
                        "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "pihakID",
                        "extern_nama" => "pihakName",
                        "extern2_id" => ".2010050060",
                        "extern2_nama" => ".Uang Muka Konsumen",
                        "jenis" => "jenisTr",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),


                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        "1010070030" => "piutang_usaha_kontijensi", // piutang usaha kontijensi
                        "4030" => "penjualan_kontijensi", // penjualan kontijensi
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "Rekening",
                    "loop" => array(
                        "1010070030" => "piutang_usaha_kontijensi", // piutang usaha kontijensi
                        "4030" => "penjualan_kontijensi", // penjualan kontijensi
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuCustomer",
                    "loop" => array(
                        "1010070030" => "piutang_usaha_kontijensi", // piutang usaha kontijensi
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "pihakID",
                        "extern_nama" => "pihakName",
                        "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuCustomerProject",
                    "loop" => array(
                        "1010070030" => "piutang_usaha_kontijensi", // piutang usaha kontijensi
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "pihakID",
                        "extern_nama" => "pihakName",
                        "extern2_id" => "projectID",//project
                        "extern2_nama" => "projectName",//project
                        "extern3_id" => ".0",//kontrak
                        "extern3_nama" => "note",//kontrak
                        "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuPenjualan",
                    "loop" => array(
                        "4030" => "penjualan_kontijensi", // penjualan kontijensi
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => ".4030030",
                        "extern_nama" => ".penjualan kontijensi project",
                        "extern2_id" => ".0",
                        "extern2_nama" => "",
                        "extern4_id" => "pihakID",
                        "extern4_nama" => "pihakName",
                        "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuPenjualanProject",
                    "loop" => array(
                        "4030" => "penjualan_kontijensi", // penjualan kontijensi
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "projectID",//project
                        "extern_nama" => "projectName",//project
                        "extern2_id" => ".4030030",
                        "extern2_nama" => ".penjualan kontijensi project",
                        "extern3_id" => ".0",//kontrak
                        "extern3_nama" => "note",//kontrak
                        "extern4_id" => "pihakID",
                        "extern4_nama" => "pihakName",
                        "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),


            ),
            "detail" => array(),
        );
        $postProcessor = array(
            "master" => array(),
            "detail" => array(
//                array(
//                    "comName" => "FifoAverage",
//                    "loop" => array(),
//                    "static" => array(
//                        "jenis" => ".produk",
//                        "jml" => "qty_debet",
//                        "produk_id" => "id",
//                        "hpp" => "hpp",
//                        "jml_nilai" => "sub_debet",
//                        "nama" => "name",
//                        "toko_id" => "tokoID",
//                        "toko_nama" => "tokoNama",
//                        "cabang_id" => "placeID",
//                        "gudang_id" => "gudangID",
//                    ),
//                    "srcGateName" => "items",
//                    "srcRawGateName" => "items",
//                ),

//                    array(
//                        "comName" => "LockerStock",
//                        "loop" => array(),
//                        "static" => array(
//                            "toko_id" => "tokoID",
//                            "toko_nama" => "tokoNama",
//                            "cabang_id" => "placeID",
//                            "jenis" => ".produk",
//                            "state" => ".active",
//                            "jumlah" => "qty_debet",
//                            "produk_id" => "id",
//                            "nama" => "name",
//                            "satuan" => "satuan",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

//                array(
//                    "comName" => "LockerStock",
//                    "loop" => array(),
//                    "static" => array(
////                            "toko_id" => "tokoID",
////                            "toko_nama" => "tokoNama",
//                        "cabang_id" => "placeID",
//                        "jenis" => ".produk",
//                        "state" => ".active",
//                        "jumlah" => "-qty",
//                        "produk_id" => "id",
//                        "nama" => "name",
//                        "satuan" => "satuan",
//                        "transaksi_id" => ".0",
//                        "oleh_id" => ".0",
//                        "gudang_id" => "gudangID",
//                    ),
//                    "srcGateName" => "items",
//                    "srcRawGateName" => "items",
//                ),

                // payment source uang muka tanpa ppn (lebih bayar)
//                array(
//                    "comName" => "PaymentUangMukaItem",
//                    "loop" => array(),
//                    "static" => array(
//                        "cabang_id" => "placeID",
//                        "cabang_nama" => "placeName",
//                        "transaksi_id" => "uangMuka__transaksi_id",
//                        "jenis" => "uangMuka__jenis",
//                        "extern_id" => "id",
//                        "extern_nama" => "nama",
//                        "label" => ".uang muka konsumen",
//                        "tambah" => "harga",
//                        "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
//                    ),
//                    "reversable" => true,
//                    "srcGateName" => "items",
//                    "srcRawGateName" => "items",
//                ),
            ),
        );
        //--------------------------


        // MEMBUAT TRANSAKSI
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            //region dynamic counters

            $counters = array(
                "stepCode|placeID",
                "stepCode|tokoID",
                "stepCode|tokoID|placeID",
                "stepCode|tokoID|olehID",
                "stepCode|tokoID|placeID|olehID",
            );
            $formatNota = "stepCode,placeID,stepCode|tokoID|placeID,stepCode|tokoID";

            //region penomoran receipt
            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($tCodeTargetJenisTransaksi);
            $configCustomParams = $counters;
            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $this->cCodeData[$cCode]["main"][$param];
                    }
                    $cRawValues = implode("|", $cValues[$i]);
                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i], $tokoID);

                    $cContent[$cRawParams][$cRawValues] = $paramSpec["value"];
                    switch ($paramSpec["id"]) {
                        case 0: //===counter type is new
                            $addData = array(
//                                "toko_id" => $tokoID,
//                                "toko_nama" => $tokoNama,
                            );
                            $paramKeyRaw = print_r($cParams, true);
                            $paramValuesRaw = print_r($cValues[$i], true);
                            $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw, $addData);
                            break;
                        default: //===counter to be updated
                            $cn->updateCount($paramSpec["id"], $paramSpec["value"]);
                            break;
                    }
                }
            }

            $appliedCounters = base64_encode(serialize($cContent));
            $appliedCounters_inText = print_r($cContent, true);

            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($tCodeTargetJenisTransaksi);
            $counterForNumber = array($formatNota);
            foreach ($counterForNumber as $i => $c0RawParams) {
                $c0Params = explode(",", $c0RawParams);
                foreach ($c0Params as $k => $cRawParams) {
                    $dParams = explode("|", $cRawParams);
                    if (count($dParams) > 1) {
                        if (!in_array($cRawParams, $counters)) {
                            die(__LINE__ . "( $cRawParams ) Used number should be registered in counters config as well");
                        }
                    }
                }
            }

            $tmpNomorNota = "";
            $arrNomorNota = array();
            foreach ($counterForNumber as $i => $c0RawParams) {
                $c0Params = explode(",", $c0RawParams);
                $c0Values = array();
                foreach ($c0Params as $k => $cRawParams) {
                    $arrRawParams = explode("|", $cRawParams);
                    if (sizeof($arrRawParams) > 1) {
                        $cRawParamsValues = array();
                        foreach ($arrRawParams as $key) {
                            $cRawParamsValues[$key] = $this->cCodeData[$cCode]['main'][$key];
                        }
                        $cRawParamsValuesK = implode("|", array_keys($cRawParamsValues));
                        $cRawParamsValuesV = implode("|", $cRawParamsValues);
                        $arrNomorNota[] = digit_4($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                    }
                    else {
                        $cRawParamsValuesK = $arrRawParams[0];
                        $cRawParamsValuesV = $this->cCodeData[$cCode]['main'][$arrRawParams[0]];
                        if ($arrRawParams[0] == "fulldate") {
                            $arrNomorNota[] = $arrRawParams[0] . "|" . date("mY", strtotime($cRawParamsValuesV));
                        }
                        elseif ($arrRawParams[0] == "stepCode") {
                            $arrNomorNota[] = $cRawParamsValuesV; //ini harus ori tidak boleh di masking/ diformat
//                            $arrNomorNota[] = digit_4($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                        }
                        elseif ($arrRawParams[0] == "placeID") {
                            $arrNomorNota[] = digit_2($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "customerID") {
                            $arrNomorNota[] = digit_4($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "olehID") {
                            $arrNomorNota[] = digit_4($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "supplierID") {
                            $arrNomorNota[] = digit_4($cRawParamsValuesV);
                        }
                        else {
                            $arrNomorNota[] = $cRawParamsValuesV;
                        }
                    }
                }
            }

            $stepNumber = 1;
            $tmpNomorNota = implode("-", $arrNomorNota);
//            cekMerah(":: $tmpNomorNota ::");
            //endregion penomoran receipt

            //region addition on master
            $nextProp = array(
                "num" => 0,
                "code" => "",
                "label" => "",
                "groupID" => "",
            );
            $addValues = array(
                "counters" => $appliedCounters,
                'counters_intext' => $appliedCounters_inText,
                'nomer' => $tmpNomorNota,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
                "step_avail" => 1,
                "step_number" => 1,
                "step_current" => 1,
                "next_step_num" => $nextProp["num"],
                "next_step_code" => $nextProp["code"],
                "next_step_label" => $nextProp["label"],
                "next_group_code" => $nextProp["groupID"],
                "tail_number" => 1,
                "tail_code" => "",
            );
            foreach ($addValues as $key => $val) {
                $this->cCodeData[$cCode]["tableIn_master"][$key] = $val;
            }
            //endregion

            //region addition on detail
            $addSubValues = array(
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "sub_step_avail" => 1,
                "next_substep_num" => $nextProp["num"],
                "next_substep_code" => $nextProp["code"],
                "next_substep_label" => $nextProp["label"],
                "next_subgroup_code" => $nextProp["groupID"],
                "sub_tail_number" => 1,
                "sub_tail_code" => "",
            );
            foreach ($this->cCodeData[$cCode]["tableIn_detail"] as $id => $dSpec) {
                foreach ($addSubValues as $key => $val) {
                    $this->cCodeData[$cCode]["tableIn_detail"][$id][$key] = $val;
                }
            }
            //endregion

            //endregion

            //region numbering tambahan
            $this->load->library("CounterNumber");
            $ccn = new CounterNumber();
            $ccn->setCCode($this->cCode);
            $ccn->setJenisTr($this->jenisTr);
            $ccn->setTransaksiGate($this->cCodeData[$cCode]["tableIn_master"]);
            $ccn->setMainGate($this->cCodeData[$cCode]["main"]);
            $ccn->setItemsGate($this->cCodeData[$cCode]["items"]);

            if (isset($this->cCodeData[$cCode]["items2_sum"])) {
                $ccn->setItems2SumGate($this->cCodeData[$cCode]["items2_sum"]);
            }

            $new_counter = $ccn->getCounterNumber();

            cekHitam("jenistr yang disett dari create " . $this->jenisTr);

            if (isset($new_counter["main"]) && sizeof($new_counter["main"]) > 0) {
                foreach ($new_counter["main"] as $ckey => $cval) {
                    $this->cCodeData[$cCode]["tableIn_master"][$ckey] = $cval;
                    $this->cCodeData[$cCode]["main"][$ckey] = $cval;
                }
            }
            if (isset($new_counter["items"]) && sizeof($new_counter["items"]) > 0) {
                foreach ($new_counter["items"] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $this->cCodeData[$cCode]["items"][$ikey][$iikey] = $iival;
                    }
                }
            }
            if (isset($new_counter["items2_sum"]) && sizeof($new_counter["items2_sum"]) > 0) {
                foreach ($new_counter["items2_sum"] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $this->cCodeData[$cCode]["items2_sum"][$ikey][$iikey] = $iival;
                    }
                }
            }
            //endregion

            //region MENULIS TRANSAKSIONAL
            if (isset($this->cCodeData[$cCode]["tableIn_master"]) && sizeof($this->cCodeData[$cCode]["tableIn_master"]) > 0) {

                $this->cCodeData[$cCode]["tableIn_master"]['status_4'] = 11;
                $this->cCodeData[$cCode]["tableIn_master"]['trash_4'] = 0;
                if ($runCliComponentDetail == false) {
                    $this->cCodeData[$cCode]["tableIn_master"]['cli'] = 1;
                }
                else {
                    $this->cCodeData[$cCode]["tableIn_master"]['cli'] = 0;
                }

                $tr = new MdlTransaksi();
                $tr->addFilter("transaksi.cabang_id='" . $this->cCodeData[$cCode]["tableIn_master"]['cabang_id'] . "'");
                $insertID = $tr->writeMainEntries($this->cCodeData[$cCode]["tableIn_master"]);
                cekHitam($this->db->last_query());
                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $this->cCodeData[$cCode]["tableIn_master"]);
                $insertNum = $this->cCodeData[$cCode]["tableIn_master"]['nomer'];
                $this->cCodeData[$cCode]["main"]['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }

                //==transaksi_id dan nomor nota diinject kan ke gate utama
                $injectors = array(
                    "transaksi_id" => $insertID,
                    "nomer" => $tmpNomorNota,
                    "nomer2" => isset($tmpNomorNotaAlias) ? $tmpNomorNotaAlias : "",
                );
                $arrInjectorsTarget = array(
                    "items",
                    "items2_sum",
                    "rsltItems",
                );
                foreach ($injectors as $key => $val) {
                    $this->cCodeData[$cCode]["main"][$key] = $val;
                    foreach ($arrInjectorsTarget as $target) {
                        if (isset($this->cCodeData[$cCode][$target])) {
                            foreach ($this->cCodeData[$cCode][$target] as $xid => $iSpec) {
                                $id = isset($iSpec["id"]) && $iSpec["id"] > 0 ? $iSpec["id"] : $xid;
                                if (isset($this->cCodeData[$cCode][$target][$id])) {
                                    $this->cCodeData[$cCode][$target][$id][$key] = $val;
                                }
                            }
                        }
                    }
                }

                //===signature
                $dwsign = $tr->writeSignature($insertID, array(
                    "nomer" => $this->cCodeData[$cCode]["main"]['nomer'],
                    "step_number" => 1,
                    "step_code" => $this->jenisTr,
//                    "step_name" => $this->configUiModul[$this->jenisTr]["steps"][1]["label"],
//                    "group_code" => $this->configUiModul[$this->jenisTr]["steps"][1]['userGroup'],
//                    "oleh_id" => $this->cCodeData[$cCode]["main"]['olehID'],
//                    "oleh_nama" => $this->cCodeData[$cCode]["main"]['olehName'],
                    "step_name" => "",
                    "group_code" => "",
                    "oleh_id" => "",
                    "oleh_nama" => "",
                    "keterangan" => "",
                    "transaksi_id" => $insertID,
                )) or die("Failed to write signature");

                $idHis = array(
                    $stepNumber => array(
                        "olehID" => $this->cCodeData[$cCode]["main"]['olehID'],
                        "olehName" => $this->cCodeData[$cCode]["main"]['olehName'],
                        "step" => $stepNumber,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota,
                        "nomer2" => isset($tmpNomorNotaAlias) ? $tmpNomorNotaAlias : "",
                        "counters" => $appliedCounters,
                        // "counters_intext" => $appliedCounters_inText,
                    ),
                );
                $idHis_blob = blobEncode($idHis);
                $idHis_intext = print_r($idHis, true);
                $tr = new MdlTransaksi();
                $dupState = $tr->updateData(array("id" => $insertID), array(
                    "next_step_num" => $nextProp["num"],
                    "next_step_code" => $nextProp["code"],
                    "next_step_label" => $nextProp["label"],
                    "next_group_code" => $nextProp["groupID"],

                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "nomer_top" => $this->cCodeData[$cCode]["main"]['nomer'],
                    "nomers_prev" => "",
                    "jenises_prev" => "",
                    "ids_his" => $idHis_blob,

                )) or die("Failed to update tr next-state!");
                cekHijau($this->db->last_query());
                $addValues = array(
                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "nomer_top" => $this->cCodeData[$cCode]["main"]['nomer'],
                    "nomers_prev" => "",
                    "jenises_prev" => "",
                    "ids_his" => $idHis_blob,
                );
                foreach ($addValues as $key => $val) {
                    $this->cCodeData[$cCode]["tableIn_master"][$key] = $val;
                }

            }
            if (isset($this->cCodeData[$cCode]['tableIn_master_values']) && sizeof($this->cCodeData[$cCode]['tableIn_master_values']) > 0) {
                $inserMainValues = array();
                if (isset($this->configValuesModul[$this->jenisTr]["tableIn"]['mainValues'])) {
                    $inserMainValues = array();
                    foreach ($this->configValuesModul[$this->jenisTr]["tableIn"]['mainValues'] as $key => $src) {
                        if (isset($this->cCodeData[$cCode]['tableIn_master_values'][$key])) {
                            $dd = $tr->writeMainValues($insertID, array(
                                "key" => $key,
                                "value" => $this->cCodeData[$cCode]['tableIn_master_values'][$key],
                            ));
                            $inserMainValues[] = $dd;
                        }
                    }
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }
            }
            if (isset($this->cCodeData[$cCode]['main_add_values']) && sizeof($this->cCodeData[$cCode]['main_add_values']) > 0) {
                $inserMainValues = array();
                foreach ($this->cCodeData[$cCode]['main_add_values'] as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }
            }
            if (isset($this->cCodeData[$cCode]['main_inputs']) && sizeof($this->cCodeData[$cCode]['main_inputs']) > 0) {
                foreach ($this->cCodeData[$cCode]['main_inputs'] as $key => $val) {
                    $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                }
            }
            if (isset($this->cCodeData[$cCode]['main_add_fields']) && sizeof($this->cCodeData[$cCode]['main_add_fields']) > 0) {
                foreach ($this->cCodeData[$cCode]['main_add_fields'] as $key => $val) {
                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                }
            }
            if (isset($this->cCodeData[$cCode]['main_applets']) && sizeof($this->cCodeData[$cCode]['main_applets']) > 0) {
                foreach ($this->cCodeData[$cCode]['main_applets'] as $amdl => $aSpec) {
                    $tr->writeMainApplets($insertID, array(
                        "mdl_name" => $amdl,
                        "key" => $aSpec['key'],
                        "label" => $aSpec['labelValue'],
                        "description" => $aSpec['description'],
                    ));
                }
            }
            if (isset($this->cCodeData[$cCode]['main_elements']) && sizeof($this->cCodeData[$cCode]['main_elements']) > 0) {
                foreach ($this->cCodeData[$cCode]['main_elements'] as $elName => $aSpec) {
                    $tr->writeMainElements($insertID, array(
                        "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                        "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                        "value" => isset($aSpec["value"]) ? $aSpec["value"] : "",
                        "name" => $aSpec['name'],
                        "label" => $aSpec["label"],
                        "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                        "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                    ));
                    //==nebeng bikin inputLabels
                    $currentValue = "";
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $aSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $aSpec["value"];
                            break;
                    }
                    if (array_key_exists($elName, $relOptionConfigs)) {
                        if (isset($relOptionConfigs[$elName][$currentValue])) {
                            if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
                                foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
                                    $inputLabels[$oValueName] = $oValSpec["label"];
                                    if (isset($oValSpec['auth'])) {
                                        if (isset($oValSpec['auth']["groupID"])) {
                                            $inputAuthConfigs[$oValueName] = $oValSpec['auth']["groupID"];
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        }
                    }
                }
            }
            if (isset($this->cCodeData[$cCode]["tableIn_detail"]) && sizeof($this->cCodeData[$cCode]["tableIn_detail"]) > 0) {
                $insertIDs = array();
                $insertDeIDs = array();
                foreach ($this->cCodeData[$cCode]["tableIn_detail"] as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    if ($insertDetailID < 1) {
                        die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                    }
                    else {
                        $insertIDs[] = $insertDetailID;
                        $insertDeIDs[$insertID][] = $insertDetailID;
                    }
                    if ($epID != 999) {
                        $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                        if ($insertEpID < 1) {
                            die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                        }
                        else {
                            $insertIDs[] = $insertEpID;
                            $insertDeIDs[$epID][] = $insertEpID;
                        }
                    }
                    cekUngu($this->db->last_query());
                }
                if (sizeof($insertIDs) == 0) {
                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                }
                else {
                    $indexing_details = array();
                    foreach ($insertDeIDs as $key => $numb) {
                        $indexing_details[$key] = $numb;
                    }
                    foreach ($indexing_details as $k => $arrID) {
                        $arrBlob = blobEncode($arrID);
                        $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                        cekOrange($this->db->last_query());
                    }
                }
            }
            else {
                die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail2']) && sizeof($this->cCodeData[$cCode]['tableIn_detail2']) > 0) {
                $insertIDs = array();
                foreach ($this->cCodeData[$cCode]['tableIn_detail2'] as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                    }
                    cekUngu($this->db->last_query());
                }
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail2_sum']) && sizeof($this->cCodeData[$cCode]['tableIn_detail2_sum']) > 0) {
                $insertIDs = array();
                foreach ($this->cCodeData[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    if ($epID != 999) {
                        $dd = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
                    }
                }
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail_rsltItems']) && sizeof($this->cCodeData[$cCode]['tableIn_detail_rsltItems']) > 0) {
                $insertIDs = array();
                foreach ($this->cCodeData[$cCode]['tableIn_detail_rsltItems'] as $dSpec) {
                    $dd = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $dd;
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                    }
                    cekUngu($this->db->last_query());
                }
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail_values']) && sizeof($this->cCodeData[$cCode]['tableIn_detail_values']) > 0) {
                $insertIDs = array();
                foreach ($this->cCodeData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                    if (isset($this->configValuesModul[$this->jenisTr]["tableIn"]['detailValues'])) {
                        foreach ($this->configValuesModul[$this->jenisTr]["tableIn"]['detailValues'] as $key => $src) {
                            if (isset($this->cCodeData[$cCode]["tableIn_detail"][$pID])) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $this->cCodeData[$cCode]["tableIn_detail"][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : "0",
                                ));
                                $insertIDs[$pID][] = $dd;
                            }
                        }
                    }
                }
                if (sizeof($insertIDs) > 0) {
                    $arrBlob = blobEncode($insertIDs);
                    $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                }
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail_values2_sum']) && sizeof($this->cCodeData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                foreach ($this->cCodeData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                    if (isset($this->configValuesModul[$this->jenisTr]["tableIn"]['detailValues2_sum'])) {
                        $insertIDs = array();
                        foreach ($this->configValuesModul[$this->jenisTr]["tableIn"]['detailValues2_sum'] as $key => $src) {
                            $dd = $tr->writeDetailValues($insertID, array(
                                "produk_jenis" => $this->cCodeData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                "produk_id" => $pID,
                                "key" => $key,
                                "value" => $dSpec[$src],
                            ));
                            $insertIDs[] = $dd;
                        }
                    }
                }
            }
//        $steps = $this->configUiModul[$this->jenisTr]["steps"];

            //endregion
        }
        else {
            $insertID = "1111111111111";
        }

//        mati_disini(__LINE__);

        // PRE-PROCC
        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            // PRE-PROCC (karena mengeluarkan stok)
            //region pre-processors (item)
            $iterator = $preProcessor["detail"];
            if (sizeof($iterator) > 0) {
//            $itemNumLabels = isset($this->configUiModul[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUiModul[$this->jenisTr]['shoppingCartNumFields'] : array();
                $itemNumLabels = array();
                cekHere("ITEM NUM LABELS");
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];

                        cekHere("sub-preproc: $comName, initializing values <br>");
                        foreach ($this->cCodeData[$cCode][$srcGateName] as $xid => $dSpec) {
                            $tmpOutParams[$cCtr] = array();
                            $id = $xid;
                            $subParams = array();

                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = "";
                            }

                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                                cekHere("sub preproc #: $comName, sending values " . __LINE__ . "<br>");

                                $mdlName = "Pre" . ucfirst($comName);
                                $this->load->model("Preprocs/" . $mdlName);
                                $m = new $mdlName($resultParams);

                                if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                    $tobeExecuted = true;
                                }
                                else {
                                    $tobeExecuted = false;
                                }

                                if ($tobeExecuted) {
                                    $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $gotParams = $m->exec();
                                    // arrPrintWebs($gotParams);
                                    // matiHEre(__LINE__);
                                    // cekmerah("gotparams dari pre-proc $comName");
                                    // arrPrint($gotParams);
                                    // matiHEre();
                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                        foreach ($gotParams as $gateName => $paramSpec) {
                                            // arrPrint($paramSpec);
                                            // cekHitam($gateName);
                                            // cekBiru(":: getParams inject ke $gateName ::");
                                            if (!isset($this->cCodeData[$cCode][$gateName])) {
                                                $this->cCodeData[$cCode][$gateName] = array();
                                            }
                                            else {
                                                //                                    cekhijau("NOT building the session: $gateName");
                                            }
                                            // matiHEre($cCode);
                                            foreach ($paramSpec as $id => $gSpec) {
                                                if (!isset($this->cCodeData[$cCode][$gateName][$id])) {
                                                    $this->cCodeData[$cCode][$gateName][$id] = array();
                                                }
                                                if (isset($this->cCodeData[$cCode][$gateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        // matiHEre("ada");
                                                        foreach ($gSpec as $key => $val) {
                                                            cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val " . __LINE__);
                                                            $this->cCodeData[$cCode][$gateName][$id][$key] = $val;
                                                            cekMerah($cCode . "[" . $gateName . "][" . $id . "][" . $key . "]=" . $val);
                                                        }
                                                    }
                                                    else {
                                                        cekMerah("bukan array");
                                                        matiHere();
                                                    }
                                                }
                                                //==inject gotParams to child gate
                                                if (isset($this->cCodeData[$cCode][$srcGateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {

                                                        foreach ($gSpec as $key => $val) {
                                                            $this->cCodeData[$cCode][$srcGateName][$id][$key] = $val;

                                                        }
                                                    }
                                                    else {
                                                        cekMerah("bukan array");
                                                        matiHere();
                                                    }
                                                }
                                                if (sizeof($itemNumLabels) > 0) {
                                                    foreach ($itemNumLabels as $key => $label) {
                                                        if (isset($this->cCodeData[$cCode][$gateName][$id][$key])) {
                                                            $this->cCodeData[$cCode][$gateName][$id]['sub_' . $key] = ($this->cCodeData[$cCode][$gateName][$id]['jml'] * $this->cCodeData[$cCode][$gateName][$id][$key]);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else {
                                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                }
                                // matiHEre(__LINE__);
                            }
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }
                // arrprintWebs($this->cCodeData[$cCode]);

                $this->load->helper("he_value_builder");
//                fillValues_he_value_builder($this->jenisTr, $this->stepNum, $this->stepNum, $this->configCoreModul[$this->jenisTr], $this->configUiModul[$this->jenisTr], $this->configValuesModul[$this->jenisTr]);
                $this->cCodeData[$cCode] = fillValuesSessionData_he_value_builder($this->jenisTr, $this->stepNum, $this->stepNum, $this->configCoreModul[$this->jenisTr], $this->configUiModul[$this->jenisTr], $this->configValuesModul[$this->jenisTr], $this->cCodeData[$cCode]["main"]["ppnFactor"], $this->cCodeData[$cCode]);
                //region injector gerbang value untuk pembatalan ppv dan selisih
                if (isset($this->cCodeData[$cCode]["revert"]["preProc"]["replacer"])) {
                    $replace = $this->cCodeData[$cCode]["revert"]["preProc"]["replacer"];
                    $tempCalculate = array(
                        "selisih" => ($this->cCodeData[$cCode]["main"]["hpp"] + $this->cCodeData[$cCode]["main"]["ppn"]) - ($this->cCodeData[$cCode]["main"]["nett"] + $this->cCodeData[$cCode]["main"]["ppv"]),
                        "hpp_nppv" => $this->cCodeData[$cCode]["main"]["hpp"],
                        "hpp_nppn" => $this->cCodeData[$cCode]["main"]["hpp"] + $this->cCodeData[$cCode]["main"]["ppn"],
                    );
                    foreach ($replace['recalculate'] as $iKey => $gate) {
                        $this->cCodeData[$cCode]["main"][$gate] = $tempCalculate[$gate];
                    }
                }
                //endregion
            }
            else {
                cekHitam("no sub-pre-processor defined. skipping preprocessor..<br>");
            }
            //endregion

            //region pre-processors (master)
            $iterator = $preProcessor["master"];
            if (sizeof($iterator) > 0) {
//            $itemNumLabels = isset($this->configUiModul[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUiModul[$this->jenisTr]['shoppingCartNumFields'] : array();
                $itemNumLabels = array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        $subParams = array();

                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $this->cCodeData[$cCode]["main"], $this->cCodeData[$cCode]["main"], 0);
                                $subParams['static'][$key] = $realValue;
                            }
                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = "";
                        }
                        $tmpOutParams[$cCtr] = $subParams;

                        $mdlName = "Pre" . ucfirst($comName);
                        $this->load->model("Preprocs/" . $mdlName);
                        $m = new $mdlName($resultParams);

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();

                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                foreach ($gotParams as $gateName => $gSpec) {
                                    if (isset($this->cCodeData[$cCode]["main"])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $this->cCodeData[$cCode]["main"][$key] = $val;
                                            }
                                        }
                                    }

                                    //==inject gotParams to child gate
                                    if (isset($this->cCodeData[$cCode]["main"])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $this->cCodeData[$cCode]["main"][$key] = $val;
                                            }
                                        }
                                    }

                                    //cekMerah("REBUILDING VALUES..");
                                    if (sizeof($itemNumLabels) > 0) {
                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                        foreach ($itemNumLabels as $key => $label) {
                                            //cekHere("$id === $key => $label");
                                            if (isset($this->cCodeData[$cCode]["main"][$key])) {
                                                $this->cCodeData[$cCode]["main"]['sub_' . $key] = ($this->cCodeData[$cCode]["main"]['jml'] * $this->cCodeData[$cCode]["main"][$key]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }
                $this->load->helper("he_value_builder");
//                fillValues_he_value_builder($this->jenisTr, $this->stepNum, $this->stepNum, $this->configCoreModul[$this->jenisTr], $this->configUiModul[$this->jenisTr], $this->configValuesModul[$this->jenisTr]);
                $this->cCodeData[$cCode] = fillValuesSessionData_he_value_builder($this->jenisTr, $this->stepNum, $this->stepNum, $this->configCoreModul[$this->jenisTr], $this->configUiModul[$this->jenisTr], $this->configValuesModul[$this->jenisTr], $this->cCodeData[$cCode]["main"]["ppnFactor"], $this->cCodeData[$cCode]);
            }
            else {
                cekHitam("no main-pre-processor defined. skipping preprocessor..<br>");
            }
            //endregion
        }

        // COMPONENT
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            // COMPONENT-----
            //region processing sub-components, if in single step geser ke CLI
            $componentGate['detail'] = array();
            $componentConfig['detail'] = array();
            $iterator = $components["detail"];
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $tmpOutParams[$cCtr] = array();
                    $gg = 0;
                    $srcGateName = $tComSpec['srcGateName'];
                    if ($componentsDetailLoop == true) {
                        foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            cekHere("sub-component: [$srcGateName] [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $this->cCodeData[$cCode][$srcGateName][$id][$key], $key);
                                    }

                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;

                                    if ($filterNeeded) {
                                        if ($subParams['loop'][$key] == 0) {
                                            unset($subParams['loop'][$key]);
                                        }
                                    }
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->cCodeData[$cCode]["main"]["keterangan"];
                                if (isset($revertedTarget) && (strlen($revertedTarget) > 1)) {
                                    $subParams['static']['reverted_target'] = $revertedTarget;
                                }
                            }
//arrPrintKuning($subParams);
                            if (sizeof($subParams) > 0) {
//                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }
                    }
                    else {
                        foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                            if ($cCtr == $id) {
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $comName = $tComSpec['comName'];
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");

                                    $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                                }

                                $mdlName = "$comsPrefix" . ucfirst($comName);
                                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                    $filterNeeded = true;
                                }
                                else {
                                    $filterNeeded = false;
                                }
                                cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                                $subParams = array();

                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {

                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");

                                            $key = str_replace($key, $this->cCodeData[$cCode][$srcGateName][$id][$key], $key);
                                        }

                                        $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                        $subParams['loop'][$key] = $realValue;

                                        if ($filterNeeded) {
                                            if ($subParams['loop'][$key] == 0) {
                                                unset($subParams['loop'][$key]);
                                            }
                                        }
                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;

                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = $insertNum;
                                    }

                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = "";
                                    if (isset($revertedTarget) && (strlen($revertedTarget) > 1)) {
                                        $subParams['static']['reverted_target'] = $revertedTarget;
                                    }
                                }

                                if (sizeof($subParams) > 0) {

                                    if ($filterNeeded) {
                                        if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    cekhitam("subparam TIDAK ada isinya");
                                }
                            }
                        }
                    }

                    $componentGate['detail'][$cCtr] = $subParams;
                }
//arrPrintKuning($tmpOutParams);
                foreach ($iterator as $cCtr => $tComSpec) {
                    $srcGateName = $tComSpec['srcGateName'];
                    foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                        }
                    }
                    cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                    $mdlName = "$comsPrefix" . ucfirst($comName);
                    $this->load->model("$comsLocation/" . $mdlName);
                    $m = new $mdlName();
                    //===filter value nol, jika harus difilter

                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                        $tobeExecuted = true;
                    }
                    else {
                        $tobeExecuted = false;
                    }

                    // matiHEre($tobeExecuted);
                    if ($tobeExecuted) {
                        //----- kiriman gerbang
                        if (method_exists($m, "setTableInMaster")) {
                            $m->setTableInMaster($this->cCodeData[$cCode]["tableIn_master"]);
                        }
                        if (method_exists($m, "setDetail")) {
                            $m->setDetail($this->cCodeData[$cCode][$srcGateName]);
                        }
                        if (method_exists($m, "setJenisTr")) {
                            $m->setJenisTr($this->jenisTr);
                        }
                        //----- kiriman gerbang
                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekBiru($this->db->last_query());
                    }
                    else {
                        cekMerah("$comName tidak eksekusi");
                    }

                }
            }
            else {
                cekKuning("subcomponents is not set");
            }
            //endregion

            //region processing main components, if in single step
            $componentGate['master'] = array();
            $componentConfig['master'] = array();
            $iterator = $components["master"];
            if (sizeof($iterator) > 0) {
                $componentConfig['master'] = $iterator;
                $cCtr = 0;
                foreach ($iterator as $cCtr => $tComSpec) {
                    $cCtr++;
                    $comName = $tComSpec['comName'];
                    if (substr($comName, 0, 1) == "{") {
                        $comName = trim($comName, "{");
                        $comName = trim($comName, "}");
                        $comName = str_replace($comName, $this->cCodeData[$cCode]["main"][$comName], $comName);
                    }
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    cekHere("component # $cCtr: $comName<br>");


                    // arrPrint($this->cCodeData[$cCode][$srcGateName]);
                    // matiHEre(__LINE__);
                    $dSpec = $this->cCodeData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            if (substr($key, 0, 1) == "{") {
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $this->cCodeData[$cCode]["main"][$key], $key);
                            }
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static']["urut"] = $cCtr;
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $this->cCodeData[$cCode]["main"]["keterangan"];
                    }

                    if (isset($tComSpec['static2'])) {
                        foreach ($tComSpec['static2'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$cCtr], $this->cCodeData[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                            $tmpOutParams['static2']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                            $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $this->cCodeData[$cCode]["main"]["keterangan"];
                    }

                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    //===filter value nol, jika harus difilter
                    $tobeExecuted = true;
                    if (in_array($mdlName, $compValidators)) {
                        $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                        if (sizeof($loopParams) > 0) {
                            foreach ($loopParams as $key => $val) {
                                cekmerah("$comName : $key = $val ");
                                if ($val == 0) {
                                    unset($tmpOutParams['loop'][$key]);
                                }
                            }
                        }
                        if (sizeof($tmpOutParams['loop']) < 1) {
                            $tobeExecuted = false;
                        }
                    }
                    if ($tobeExecuted) {
                        //----- kiriman gerbang untuk counter mutasi rekening
                        if (method_exists($m, "setTableInMaster")) {
                            $m->setTableInMaster($this->cCodeData[$cCode]["tableIn_master"]);
                        }
                        if (method_exists($m, "setMain")) {
                            $m->setMain($this->cCodeData[$cCode]["main"]);
                        }
                        if (method_exists($m, "setJenisTr")) {
                            $m->setJenisTr($this->jenisTr);
                        }
                        //----- kiriman gerbang untuk counter mutasi rekening
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    }
                    $componentGate['master'][$cCtr] = $tmpOutParams;
                }
            }
            else {
                cekKuning("components is not set");
            }
            //endregion
        }


        // POST-PROCC
        $pakai_ini = 0;
        if ($pakai_ini == 1) {

            //region processing sub-post-processors, always
            $iterator = $postProcessor["detail"];
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    cekHere("[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>");
                    $tmpOutParams[$cCtr] = array();
                    if (isset($this->cCodeData[$cCode][$srcGateName]) && (sizeof($this->cCodeData[$cCode][$srcGateName]) > 0)) {
                        foreach ($this->cCodeData[$cCode][$srcGateName] as $xid => $dSpec) {
                            $id = $xid;
                            $subParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }
                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                if (isset($this->cCodeData[$cCode]['revert']['postProc']['detail'])) {
                                    $subParams['static']["reverted_target"] = $this->cCodeData[$cCode]["main"]['pihakExternID'];
                                }
                                $subParams['static']["keterangan"] = "";
                            }
                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                            }
                        }
                    }
                }
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    if (isset($this->cCodeData[$cCode][$srcGateName])) {
                        cekHere("[$cCtr] sub-postProcessor: $comName, sending values " . __LINE__ . "<br>");
                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekHitam($this->db->last_query());
                    }
                }
            }
            else {
                cekHitam("TIDAK ADA SETUP SUB-POSTPROC");
            }
            //endregion

            //region processing main-post-processors, always
            $iterator = $postProcessor["master"];
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    cekHere("post-processor: $comName<br>LINE: " . __LINE__);

                    $dSpec = $this->cCodeData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = "";
                    }
                    if (isset($tComSpec['static2'])) {
                        foreach ($tComSpec['static2'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$cCtr], $this->cCodeData[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                            $tmpOutParams['static2']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                            $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                        }

                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = "";
                    }

                    //lgShowError("Ada kesalahan",);
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    cekBiru("kiriman komponem $comName");
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                }
            }
            else {
                cekHitam("TIDAK ADA SETUP MAIN-POSTPROC");
            }
            //endregion
        }


        //region MENULIS KE REGISTRY
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            if (isset($core['components']) && sizeof($core['components'])) {
                $jurnalIndex = $core['components'];
            }
            else {
                if (isset($this->cCodeData[$cCode]["revert"]["jurnal"]) && sizeof($this->cCodeData[$cCode]["revert"]["jurnal"]) > 0) {
                    $jurnalIndex = $this->cCodeData[$cCode]["revert"]["jurnal"];
                }
                else {
                    $jurnalIndex = array();
                }
            }
            //------------
            if (isset($this->configValuesModul[$this->jenisTr]['postProcessor'][$jenisTrTarget]) && sizeof($this->configValuesModul[$this->jenisTr]['postProcessor'][$jenisTrTarget])) {
                $jurnalPostProc = $this->configValuesModul[$this->jenisTr]['postProcessor'][$jenisTrTarget];
            }
            else {
                if (isset($this->cCodeData[$cCode]["revert"]["postProc"]) && sizeof($this->cCodeData[$cCode]["revert"]["postProc"]) > 0) {
                    $jurnalPostProc = $this->cCodeData[$cCode]["revert"]["postProc"];
                }
                else {
                    $jurnalPostProc = array();
                }
            }
            //------------
            if (isset($core['preProcessor'][$jenisTrTarget]) && sizeof($core['preProcessor'][$jenisTrTarget])) {
                $jurnalPreProc = $core['preProcessor'][$jenisTrTarget];
            }
            else {
                if (isset($this->cCodeData[$cCode]["revert"]["preProc"]) && sizeof($this->cCodeData[$cCode]["revert"]["preProc"]) > 0) {
                    $jurnalPreProc = $this->cCodeData[$cCode]["revert"]["preProc"];
                }
                else {
                    $jurnalPreProc = array();
                }
            }
            //------------
            if (isset($this->configValuesModul[$this->jenisTr]['coreBuilder'][$jenisTrTarget]) && sizeof($this->configValuesModul[$this->jenisTr]['coreBuilder'][$jenisTrTarget])) {
                $coreBuilder = $this->configValuesModul[$this->jenisTr]['coreBuilder'][$jenisTrTarget];
            }
            else {
                $coreBuilder = array();
            }
            //------------
            $baseRegistries = array(
                "main" => isset($this->cCodeData[$cCode]["main"]) ? $this->cCodeData[$cCode]["main"] : array(),
                "items" => isset($this->cCodeData[$cCode]["items"]) ? $this->cCodeData[$cCode]["items"] : array(),
                "items2" => isset($this->cCodeData[$cCode]["items2"]) ? $this->cCodeData[$cCode]["items2"] : array(),
                "items2_sum" => isset($this->cCodeData[$cCode]["items2_sum"]) ? $this->cCodeData[$cCode]["items2_sum"] : array(),
                "itemSrc" => isset($this->cCodeData[$cCode]["itemSrc"]) ? $this->cCodeData[$cCode]["itemSrc"] : array(),
                "itemSrc_sum" => isset($this->cCodeData[$cCode]["itemSrc_sum"]) ? $this->cCodeData[$cCode]["itemSrc_sum"] : array(),
                "items3" => isset($this->cCodeData[$cCode]["items3"]) ? $this->cCodeData[$cCode]["items3"] : array(),
                "items3_sum" => isset($this->cCodeData[$cCode]["items3_sum"]) ? $this->cCodeData[$cCode]["items3_sum"] : array(),
                "items4" => isset($this->cCodeData[$cCode]["items4"]) ? $this->cCodeData[$cCode]["items4"] : array(),
                "items4_sum" => isset($this->cCodeData[$cCode]["items4_sum"]) ? $this->cCodeData[$cCode]["items4_sum"] : array(),
                "items5_sum" => isset($this->cCodeData[$cCode]["items5_sum"]) ? $this->cCodeData[$cCode]["items5_sum"] : array(),
                'items6_sum' => isset($this->cCodeData[$cCode]['items6_sum']) ? $this->cCodeData[$cCode]['items6_sum'] : array(),
                'items7_sum' => isset($this->cCodeData[$cCode]['items7_sum']) ? $this->cCodeData[$cCode]['items7_sum'] : array(),
                'items8_sum' => isset($this->cCodeData[$cCode]['items8_sum']) ? $this->cCodeData[$cCode]['items8_sum'] : array(),
                'items9_sum' => isset($this->cCodeData[$cCode]['items9_sum']) ? $this->cCodeData[$cCode]['items9_sum'] : array(),
                'items10_sum' => isset($this->cCodeData[$cCode]['items10_sum']) ? $this->cCodeData[$cCode]['items10_sum'] : array(),
                'rsltItems' => isset($this->cCodeData[$cCode]['rsltItems']) ? $this->cCodeData[$cCode]['rsltItems'] : array(),
                'rsltItems2' => isset($this->cCodeData[$cCode]['rsltItems2']) ? $this->cCodeData[$cCode]['rsltItems2'] : array(),
                'rsltItems3' => isset($this->cCodeData[$cCode]['rsltItems3']) ? $this->cCodeData[$cCode]['rsltItems3'] : array(),
                "tableIn_master" => isset($this->cCodeData[$cCode]["tableIn_master"]) ? $this->cCodeData[$cCode]["tableIn_master"] : array(),
                "tableIn_detail" => isset($this->cCodeData[$cCode]["tableIn_detail"]) ? $this->cCodeData[$cCode]["tableIn_detail"] : array(),
                'tableIn_detail2_sum' => isset($this->cCodeData[$cCode]['tableIn_detail2_sum']) ? $this->cCodeData[$cCode]['tableIn_detail2_sum'] : array(),
                'tableIn_detail_rsltItems' => isset($this->cCodeData[$cCode]['tableIn_detail_rsltItems']) ? $this->cCodeData[$cCode]['tableIn_detail_rsltItems'] : array(),
                'tableIn_detail_rsltItems2' => isset($this->cCodeData[$cCode]['tableIn_detail_rsltItems2']) ? $this->cCodeData[$cCode]['tableIn_detail_rsltItems2'] : array(),
                'tableIn_master_values' => isset($this->cCodeData[$cCode]['tableIn_master_values']) ? $this->cCodeData[$cCode]['tableIn_master_values'] : array(),
                'tableIn_detail_values' => isset($this->cCodeData[$cCode]['tableIn_detail_values']) ? $this->cCodeData[$cCode]['tableIn_detail_values'] : array(),
                'tableIn_detail_values_rsltItems' => isset($this->cCodeData[$cCode]['tableIn_detail_values_rsltItems']) ? $this->cCodeData[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                'tableIn_detail_values_rsltItems2' => isset($this->cCodeData[$cCode]['tableIn_detail_values_rsltItems2']) ? $this->cCodeData[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                'tableIn_detail_values2_sum' => isset($this->cCodeData[$cCode]['tableIn_detail_values2_sum']) ? $this->cCodeData[$cCode]['tableIn_detail_values2_sum'] : array(),
                'main_add_values' => isset($this->cCodeData[$cCode]['main_add_values']) ? $this->cCodeData[$cCode]['main_add_values'] : array(),
                'main_add_fields' => isset($this->cCodeData[$cCode]['main_add_fields']) ? $this->cCodeData[$cCode]['main_add_fields'] : array(),
                'main_elements' => isset($this->cCodeData[$cCode]['main_elements']) ? $this->cCodeData[$cCode]['main_elements'] : array(),
//                'items_elements' => isset($this->cCodeData[$cCode]['items_elements']) ? $this->cCodeData[$cCode]['items_elements'] : array(),
                'main_inputs' => isset($this->cCodeData[$cCode]['main_inputs']) ? $this->cCodeData[$cCode]['main_inputs'] : array(),
                'main_inputs_orig' => isset($this->cCodeData[$cCode]['main_inputs']) ? $this->cCodeData[$cCode]['main_inputs'] : array(),
                "receiptDetailFields" => isset($this->configLayoutModul[$this->jenisTr]['receiptDetailFields'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptDetailFields'][1] : array(),
                "receiptSumFields" => isset($this->configLayoutModul[$this->jenisTr]['receiptSumFields'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptSumFields'][1] : array(),
                "receiptDetailFields2" => isset($this->configLayoutModul[$this->jenisTr]['receiptDetailFields2'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptDetailFields2'][1] : array(),
                "receiptDetailSrcFields" => isset($this->configLayoutModul[$this->jenisTr]['receiptDetailSrcFields'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptDetailSrcFields'][1] : array(),
                "receiptSumFields2" => isset($this->configLayoutModul[$this->jenisTr]['receiptSumFields2'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptSumFields2'][1] : array(),
                "jurnal_index" => $jurnalIndex,
                "postProcessor" => $jurnalPostProc,
                "preProcessor" => $jurnalPreProc,
                "revert" => isset($this->cCodeData[$cCode]['revert']) ? $this->cCodeData[$cCode]['revert'] : array(),
                "items_komposisi" => isset($this->cCodeData[$cCode]['items_komposisi']) ? $this->cCodeData[$cCode]['items_komposisi'] : array(),
                "items_noapprove" => isset($this->cCodeData[$cCode]['items_noapprove']) ? $this->cCodeData[$cCode]['items_noapprove'] : array(),
                "jurnalItems" => isset($this->cCodeData[$cCode]['jurnalItems']) ? $this->cCodeData[$cCode]['jurnalItems'] : array(),
                "componentsBuilder" => isset($this->cCodeData[$cCode]['componentsBuilder']) ? $this->cCodeData[$cCode]['componentsBuilder'] : array(),
//                "itemPrice" => isset($this->cCodeData[$cCode]['itemPrice']) ? $this->cCodeData[$cCode]['itemPrice'] : array(),
//                "itemPrice_sum" => isset($this->cCodeData[$cCode]['itemPrice_sum']) ? $this->cCodeData[$cCode]['itemPrice_sum'] : array(),
//                "requiredParam" => (isset($coreRequiredParam[$this->jenisTr]) && sizeof($coreRequiredParam[$this->jenisTr]) > 0) ? $coreRequiredParam[$this->jenisTr] : array(),
                //-----
//                "coreBuilder" => $coreBuilder,
//                'diskon_event' => isset($this->cCodeData[$cCode]['diskon_event']) ? $this->cCodeData[$cCode]['diskon_event'] : array(),
//                'cashback_event' => isset($this->cCodeData[$cCode]['cashback_event']) ? $this->cCodeData[$cCode]['cashback_event'] : array(),
                //-----
            );
            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
//            showLast_query("biru");

        }
        //endregion


        $pakai_ini = 0;
        if ($pakai_ini == 1) {

            $this->load->model("Coms/ComRekeningPembantuProduk");
            $this->load->model("Coms/ComProdukSerialNumber");
            foreach ($arrprodukIDs as $pid => $pSpec) {
                $itemID = $pid;
                $crd = new ComRekeningPembantuProduk();
                $crd->addFilter("gudang_id='$gudangID'");
                $crd->addFilter("cabang_id='$cabangID'");
                $crd->addFilter("extern_id='$itemID'");
                $crd->addFilter("periode='forever'");
                $crdTmp = $crd->lookupAll()->result();
                showLast_query("biru");
                cekBiru(count($crdTmp));
                if (sizeof($crdTmp) > 0) {
                    $qty = $crdTmp[0]->qty_debet;
                    $debet = $crdTmp[0]->debet;
                    $avg = ($qty > 0) ? $debet / $qty : 0;

                    $this->load->model("Mdls/MdlFifoAverage");
                    $ff = new MdlFifoAverage();
                    $ff->addFilter("jenis='produk'");
                    $ff->addFilter("produk_id='$itemID'");
                    $ff->addFilter("cabang_id='$cabangID'");
                    $ff->addFilter("gudang_id='$gudangID'");
                    $ffTmp = $ff->lookupAll()->result();
                    showLast_query("biru");
                    if (sizeof($ffTmp) > 0) {
                        $id_tbl = $ffTmp[0]->id;
                        $where = array(
                            "id" => $id_tbl
                        );
                        $data = array(
                            "jml" => $qty,
                            "hpp" => $avg,
                            "jml_nilai" => $debet,
                        );
                        $ff->updateData($where, $data);
                        showLast_query("orange");
                    }

                }

                if (isset($detailGate[$pid])) {
                    $jml = $detailGate[$pid]["jml"];
                    cekHere("[jml: $jml]");
//                arrPrintKuning($detailGate[$pid]);
                    for ($ii = 1; $ii <= $jml; $ii++) {
                        $anu[0] = array(
                            "loop" => array(),
                            "static" => array(
                                "jenis" => "99999",
                                "cabang_id" => "$cabangID",
                                "jumlah" => "1",
                                "produk_id" => $detailGate[$pid]["target_id"],
                                "produk_nama" => $detailGate[$pid]["name"],
                                "produk_serial_number" => "",//serial_number
                                "produk_sku" => $detailGate[$pid]["kode"],
                                "produk_sku_serial" => "",//produk_sku_serial
                                "produk_sku_part_id" => "",//produk_sku_part_id
                                "produk_sku_part_nama" => $detailGate[$pid]["kode"],//produk_sku_part_nama
                                "produk_sku_part_serial" => "",//produk_sku_part_serial
                                "oleh_id" => "$olehID",
                                "oleh_nama" => "$olehNama",
                                "supplier_id" => "$supplierID",
                                "supplier_nama" => "$supplierName",
                                "gudang_id" => "$gudangID",
                                //---------------
                                "transaksi_reference_id" => "$insertID",
                                "transaksi_reference_no" => "$insertNum",
                                "transaksi_reference_dtime" => date("Y-m-d H:i:s"),
                                "transaksi_reference_fulldate" => date("Y-m-d H:i:s"),
                                "transaksi_reference_count" => "1",
                                "transaksi_count" => "1",
                                "transaksi_jenis_count" => "1",
                                "part_keterangan" => "",
                                "transaksi_id" => "$insertID",
                                "transaksi_no" => "$insertNum",
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d H:i:s"),
                            ),
                        );
                        arrPrintPink($anu);
                        $ss = new ComProdukSerialNumber();
                        $ss->pair($anu);
                        $ss->exec();
                    }


                }
            }

        }


        $pakai_ini = 0;// update tabel payment source
        if ($pakai_ini == 1) {
            foreach ($arrDataDetail as $spec) {
                $sisa = $spec["sisa"];
                $harga = $spec["harga"];
                $new_sisa = $sisa - $harga;
                $data = array(
                    "tagihan" => $new_sisa,
                    "sisa" => $new_sisa,
                    "dihapus" => $harga,
                );
                $where = array(
                    "id" => $spec["id_tbl"],
                );
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->updatePaymentSrc($where, $data);
                showLast_query("orange");

            }
        }


        cekMerah(":: cek validate lajur di " . __FUNCTION__ . ", " . __FILE__);
        validateAllBalances($cabangID);


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");
    }

    public function tulisTabelJurnalDetail()
    {

        $tbl_jurnal = "jurnal_detail";
        $tbl_mutasi = "__rek_pembantu_customer__1010020010";
        $tbl_mutasi2 = "__rek_pembantu_subcustomer__2010050";
        $tbl_id = "16870";
        $tbl_id2 = "14206";


        $this->db->trans_start();


        $arrWhere = array(
            "id>=" => $tbl_id,
            "jenis" => "99999",
        );
        $this->db->where($arrWhere);
        $query = $this->db->get($tbl_mutasi)->result();
        showLast_query("biru");
        foreach ($query as $querySpec) {
//            $kredit = $querySpec->kredit;
            $data = array(
                "jenis" => $querySpec->jenis,
                "rekening" => $querySpec->rekening,
                "debet" => $querySpec->debet,
                "kredit" => $querySpec->kredit,
                "transaksi_id" => $querySpec->transaksi_id,
                "transaksi_no" => $querySpec->transaksi_no,
                "cabang_id" => $querySpec->cabang_id,
                "dtime" => $querySpec->dtime,
                "fulldate" => $querySpec->fulldate,
                "keterangan" => $querySpec->keterangan,
                "extern_id" => $querySpec->extern_id,
                "extern_nama" => $querySpec->extern_nama,
            );
            $this->db->insert($tbl_jurnal, $data);
            showLast_query("hijau");
        }


        $arrWhere = array(
            "id>=" => $tbl_id2,
            "jenis" => "99999",
        );
        $this->db->where($arrWhere);
        $query = $this->db->get($tbl_mutasi2)->result();
        showLast_query("biru");
        foreach ($query as $querySpec) {
//            $kredit = $querySpec->kredit;
            $data = array(
                "jenis" => $querySpec->jenis,
                "rekening" => $querySpec->rekening,
                "debet" => $querySpec->debet,
                "kredit" => $querySpec->kredit,
                "transaksi_id" => $querySpec->transaksi_id,
                "transaksi_no" => $querySpec->transaksi_no,
                "cabang_id" => $querySpec->cabang_id,
                "dtime" => $querySpec->dtime,
                "fulldate" => $querySpec->fulldate,
                "keterangan" => $querySpec->keterangan,
                "extern_id" => $querySpec->extern_id,
                "extern_nama" => $querySpec->extern_nama,
            );
            $this->db->insert($tbl_jurnal, $data);
            showLast_query("orange");
        }


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");

    }

    //-------------------------------------------
    public function patchDiskonSupplierLawas()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlLockerStockPreDiskonVendor");
        $this->load->model("Mdls/MdlSupplierDiskon");
        $this->load->model("Coms/ComLockerPreDiskonValue");

        $po_id = $trid = "348072";

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id='$trid'");
        $trReg = $tr->lookupDataRegistries()->result();
        showLast_query("biru");
        arrPrint($trReg);
        if (sizeof($trReg) > 0) {
            foreach ($trReg as $paramss) {
                foreach ($paramss as $key => $val) {
                    if ($key != "transaksi_id") {
                        if ($val == NULL) {
                            $val = blobEncode(array());
                        }
                        $$key = blobDecode($val);
                    }
                }
            }
        }
        else {
            mati_disini("data transaksi kosong/tidak ada...");
        }


        $m = new MdlLockerStockPreDiskonVendor();
        $m->addFilter("transaksi_id='$po_id'");
        $m->addFilter("reference_id='$referenceID'");
        $tmp = $m->lookUpAll()->result();
        $result = array();
        if (count($tmp) > 0) {
            $result = array(
                "skip" => 1
            );
        }
        else {
            $result = array(
                "skip" => 0
            );
        }
        if ($cekPreValue["skip"] == 1) {
            $allow_preproc = false;
        }
        else {
            $allow_preproc = true;
        }
        if ($allow_preproc) {
            $arrDataDiskon = array();
            $sd = new MdlSupplierDiskon();
            $sd->addFilter("jenis='reguler'");
            $sdTmp = $sd->lookupAll()->result();
            foreach ($sdTmp as $sdSpec) {
                $arrDataDiskon[$sdSpec->id] = array(
                    "id" => $sdSpec->id,
                    "nama" => $sdSpec->nama,
                    "coa_code" => $sdSpec->coa_code,
                );
            }

//            $cCode = "_TR_" . $inParams["static"]["jenisTrMaster"];
//            $_SESSION[$cCode][$inParams["static"]["target"]] = array();
            $items4_sum = array();
//            $src_key = isset($inParams["static"]["source"]) ? $inParams["static"]["source"] : "items";
//            $items = $_SESSION[$cCode]["items"];
//            $items = $_SESSION[$cCode][$src_key];
            foreach ($items as $pID => $iSpec) {
                foreach ($arrDataDiskon as $iii => $iiiSpec) {
//                    arrPrintKuning($iiiSpec);
                    $key_nama = $iiiSpec["nama"];
                    $key_nama_cek = $iiiSpec["nama"] . "_id";
                    if (array_key_exists($key_nama_cek, $iSpec)) {
                        cekmerah("ada $key_nama_cek dibuatkan items4_sum");
                        $data4_sum = array(
                            "id" => $iSpec["id"],
                            "nama" => $iSpec["nama"],
                            "name" => $iSpec["name"],
                            "jml" => $iSpec["jml"],
                            "qty" => $iSpec["qty"],
                            "diskon_id" => $iSpec[$key_nama . "_id"],
                            "diskon_nama" => $iSpec[$key_nama . "_nama"],
                            "diskon_name" => $iSpec[$key_nama . "_nama"],
                            "diskon_persen" => $iSpec[$key_nama . "_persen"],
                            "diskon_nilai" => $iSpec[$key_nama . "_nilai"],

                            "sub_diskon_nilai" => $iSpec[$key_nama . "_nilai"] * $iSpec["jml"],
                        );
//                        arrPrintHijau($data4_sum);
                        foreach ($main as $kk => $vv) {
                            if (!array_key_exists($kk, $data4_sum)) {
                                $data4_sum[$kk] = $vv;
                            }
                        }
                        $items4_sum[] = $data4_sum;
                    }
                    else {
//                        cekhitam("tidak ada $key_nama_cek dibuatkan items4_sum");
                    }
                }
            }
        }


        $this->db->trans_start();


        if (isset($items4_sum) && sizeof($items4_sum) > 0) {
            foreach ($items4_sum as $spec) {
                if ($spec["sub_diskon_nilai"] > 0) {

                    $data[0] = array(
//                    "comName" => "LockerPreDiskonValue",
                        "loop" => array(
                            "exec_locker" => $spec["sub_diskon_nilai"],//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => $spec["placeID"],
                            "jenis" => "diskon",
                            "jenis2" => "diskon",
                            "jenis_locker" => "stock",
                            "state" => "active",
                            "jumlah" => "1",
                            "nilai" => $spec["sub_diskon_nilai"],
                            "nilai2" => $spec["sub_diskon_nilai"],
                            "nilai_unit" => $spec["sub_diskon_nilai"],
                            "produk_id" => $spec["diskon_id"],//id diskon
                            "nama" => $spec["diskon_nama"],

                            "extern_id" => $spec["diskon_id"],//id produk hadiah/jika berupa diskon reguler diisi id diskon
                            "extern_nama" => $spec["diskon_nama"],
                            "extern2_id" => $spec["id"],//produk yang dibeli
                            "extern2_nama" => $spec["nama"],
                            "satuan" => $spec["satuan"],
                            "transaksi_id" => "$po_id",
                            "transaksi_no" => $spec["nomer"],
                            "nomer" => $spec["nomer"],
                            "oleh_id" => "0",
                            "gudang_id" => $spec["gudangID"],
                            "supplier_id" => $spec["pihakID"],
                            "supplier_nama" => $spec["pihakName"],
                            "reference_id" => $spec["referenceID"],
                            "reference_nomer" => $spec["referenceNomer"],
                        ),

                    );
                    $cld = new ComLockerPreDiskonValue();
                    $cld->pair($data);
                    $cld->exec();

                }
            }
        }


//        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");

    }

    //-------------------------------------------
    public function patchKomisi()
    {
        $this->load->model("MdlTransaksi");

        $tr_id = "371168";

        $tr = new MdlTransaksi();
        $tr->setFilters(array());

        $tr->addFilter("transaksi_id='$tr_id'");
        $trTmp = $tr->lookupDataRegistries()->result();
        showLast_query("biru");
//        arrPrint($trTmp);

        $this->db->trans_start();

        $main = blobDecode($trTmp[0]->main);
        $items = blobDecode($trTmp[0]->items);
//        arrprintCyan($main);

        // region main
        $pph_tarif = $main["pph__tarif"];
//        $pph_tarif = 2.50;
        $harga = $main["harga"];
        $nilai_untung = $main["nilai_untung"];
        $nilai_final_rugilaba = $main["nilai_final_rugilaba"];
        $subtotal = $main["subtotal"];
        $biaya_cashback = $main["biaya_cashback"];

        $nilai_pph21 = $main["nilai_pph21"];
        $nilai_pph_original = $main["nilai_pph_original"];
        $hutang_pph21 = $main["hutang_pph21"];

        $nilai_kas_cn = $main["nilai_kas_cn"];
        $kas_cabang = $main["kas_cabang"];
        $kas_pusat = $main["kas_pusat"];
        $hutang_ke_pusat = $main["hutang_ke_pusat"];
        $piutang_cabang = $main["piutang_cabang"];

        $nilai_kas_cn_new = $harga;
        $harga_new = (100 / (100 - $pph_tarif)) * $nilai_kas_cn_new;
        $nilai_pph21_new = ($pph_tarif / 100) * $harga_new;
        cekHijau("[$harga_new] [$nilai_pph21_new] [$nilai_kas_cn_new]");

        $dataUpdate = array(
            "harga" => $harga_new,
            "nilai_untung" => $harga_new,
            "nilai_final_rugilaba" => $harga_new,
            "subtotal" => $harga_new,
            "biaya_cashback" => $harga_new,

            "nilai_pph21" => $nilai_pph21_new,
            "nilai_pph_original" => $nilai_pph21_new,
            "hutang_pph21" => $nilai_pph21_new,

            "nilai_kas_cn" => $nilai_kas_cn_new,
            "kas_cabang" => $nilai_kas_cn_new,
            "kas_pusat" => $nilai_kas_cn_new,
            "hutang_ke_pusat" => $nilai_kas_cn_new,
            "piutang_cabang" => $nilai_kas_cn_new,

            "pph__tarif" => $pph__tarif,
            "pph21Methode__tarif" => $pph__tarif,
        );
//        arrPrintPink($dataUpdate);
        foreach ($dataUpdate as $key => $val) {
            $main[$key] = $val;
        }
//        arrprintCyan($main);


        // endregion main


        // region items
        foreach ($items as $iid => $spec) {
            $ipph__tarif = $spec["pph__tarif"];
//            $ipph__tarif = 2.50;
            $iharga = $spec["harga"];

            $inilai_kas_cn_new = $iharga;
            $iharga_new = (100 / (100 - $ipph__tarif)) * $inilai_kas_cn_new;
            $inilai_pph21_new = ($ipph__tarif / 100) * $iharga_new;
            cekLime("[$iharga_new] [$inilai_pph21_new] [$inilai_kas_cn_new]");

            $idata[$iid] = array(
                "harga" => $iharga_new,
                "nilai_untung" => $iharga_new,
                "nilai_final_rugilaba" => $iharga_new,
                "subtotal" => $iharga_new,
                "sub_harga" => $iharga_new,
                "sub_nilai_untung" => $iharga_new,
                "sub_nilai_final_rugilaba" => $iharga_new,
                "sub_subtotal" => $iharga_new,

                "nilai_pph21" => $inilai_pph21_new,
                "nilai_pph_original" => $inilai_pph21_new,
                "sub_nilai_pph21" => $inilai_pph21_new,
                "sub_nilai_pph_original" => $inilai_pph21_new,

                "nilai_kas_cn" => $inilai_kas_cn_new,
                "sub_nilai_kas_cn" => $inilai_kas_cn_new,

                "pph__tarif" => $pph__tarif,
                "pph21Methode__tarif" => $pph__tarif,
            );
        }
        foreach ($idata as $iid => $iiSpec) {
            foreach ($iiSpec as $ikey => $ival) {
                $items[$iid][$ikey] = $ival;
            }
        }
//        arrPrintHitam($items);
        // endregion items

        $dataUpdateReg = array(
//            "main" => blobEncode($main),
//            "items" => blobEncode($items),
            "main" => $main,
            "items" => $items,
        );
//        arrPrintKuning($dataUpdateReg);
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $where = array(
            "transaksi_id" => $tr_id,
        );
        $tr->updateDataRegistry($where, $dataUpdateReg);
        showLast_query("orange");


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    public function patchSaldoAwalMasterPeriode()
    {
        $this->load->model("Mdls/MdlNeraca");
        $last_tahun = previousYear();
        $tahun = date("Y");
        $periode = "tahunan";
        $cabang_id = "-1";
//        $cabang_id = "1";
//        $cabang_id = "33";
        $arrRekening = array();

        $n = new MdlNeraca();
        $n->addFilter("periode='$periode'");
        $n->addFilter("thn='$last_tahun'");
        $n->addFilter("cabang_id='$cabang_id'");
        $n->addFilter("status=1");
        $n->addFilter("trash=0");
        $nTmp = $n->lookupAll()->result();
        showLast_query("biru");
        if (sizeof($nTmp) > 0) {
            foreach ($nTmp as $nSpec) {
                $rekening = $nSpec->rekening;
                $debet = $nSpec->debet;
                $kredit = $nSpec->kredit;
                $cab_id = $nSpec->cabang_id;
                if (!isset($arrRekening[$rekening]["saldo_awal_debet"])) {
                    $arrRekening[$rekening]["saldo_awal_debet"] = 0;
                }
                if (!isset($arrRekening[$rekening]["saldo_awal_kredit"])) {
                    $arrRekening[$rekening]["saldo_awal_kredit"] = 0;
                }
                $arrRekening[$rekening]["saldo_awal_debet"] += $debet;
                $arrRekening[$rekening]["saldo_awal_kredit"] += $kredit;
                $arrRekening[$rekening]["rekening"] = $rekening;
                $arrRekening[$rekening]["cabang_id"] = $cabang_id;
                $arrRekening[$rekening]["dtime"] = "2025-01-01 00:00:01";
                $arrRekening[$rekening]["fulldate"] = "2025-01-01";
                $arrRekening[$rekening]["thn"] = "2025";
                $arrRekening[$rekening]["bln"] = "01";
                $arrRekening[$rekening]["tgl"] = "01";
            }
            foreach ($arrRekening as $rek => $rSpec) {
                if (($rSpec["saldo_awal_debet"] > 0) && ($rSpec["saldo_awal_kredit"] > 0)) {
                    $def_position = detectRekDefaultPosition($rek);
                    switch ($def_position) {
                        case "debet":
                            $netto = $rSpec["saldo_awal_debet"] - $rSpec["saldo_awal_kredit"];
                            if ($netto > 0) {
                                $arrRekening[$rek]["saldo_awal_debet"] = $netto;
                                $arrRekening[$rek]["saldo_awal_kredit"] = 0;
                            }
                            else {
                                $arrRekening[$rek]["saldo_awal_debet"] = 0;
                                $arrRekening[$rek]["saldo_awal_kredit"] = $netto * -1;
                            }
                            break;
                        case "kredit":
                            $netto = $rSpec["saldo_awal_kredit"] - $rSpec["debet"];
                            if ($netto > 0) {
                                $arrRekening[$rek]["saldo_awal_debet"] = 0;
                                $arrRekening[$rek]["saldo_awal_kredit"] = $netto;
                            }
                            else {
                                $arrRekening[$rek]["saldo_awal_debet"] = $netto * -1;
                                $arrRekening[$rek]["saldo_awal_kredit"] = 0;
                            }
                            break;
                    }
                }
            }
        }
        arrPrintHitam($arrRekening);
        cekHitam(count($arrRekening));

        $this->db->trans_start();

        foreach ($arrRekening as $rek => $rSpec) {
            $debet = $rSpec["saldo_awal_debet"];
            $kredit = $rSpec["saldo_awal_kredit"];
            $tabel = "__rek_master__" . $rek;
            $where = array(
                "cabang_id" => $cabang_id,
                "rekening" => $rek,
                "thn" => $tahun,
            );
            $this->db->where($where);
            $this->db->order_by("id", "ASC");
            $this->db->limit(1);
            $hasil = $this->db->get($tabel)->result();
//            showLast_query("biru");
            if (sizeof($hasil) > 0) {
                $id_tbl = $hasil[0]->id;
                $where_update = array(
                    "id" => $id_tbl,
                );
                $data_update = array(
                    "saldo_awal_debet" => $debet,
                    "saldo_awal_kredit" => $kredit,
                );
                $this->db->where($where_update);
                $this->db->update($tabel, $data_update);
                showLast_query("orange");
            }
            else {
                $rSpec["debet_akhir"] = $rSpec["saldo_awal_debet"];
                $rSpec["kredit_akhir"] = $rSpec["saldo_awal_kredit"];
                $this->db->insert($tabel, $rSpec);
                showLast_query("hijau");
            }

//            break;
        }


//        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    public function patchSaldoAwalDetailPeriode()
    {
        //--------------
//        $comModel = "ComRekeningPembantuProduk";//ok
//        $rekening = "1010030030";
//        $comModel = "ComRekeningPembantuSupplies";//ok
//        $rekening = "1010030010";
//        $comModel = "ComRekeningPembantuKas";//ok
//        $rekening = "1010010010";
//        $comModel = "ComRekeningPembantuCustomer";//ok
//        $rekening = "1010020010";
//        $rekening = "1010070030";
//        $rekening = "1010020080";
        //--------------
//        $comModel = "ComRekeningPembantuCustomer";//ok
//        $rekening = "2010050";
//        $comModel = "ComRekeningPembantuCustomerDetail";
//        $rekening = "2010050";
        //--------------
        $comModel = "ComRekeningPembantuSupplier";//ok
        $rekening = "2010010";
//        $rekening = "2010040";
//        $comModel = "ComRekeningPembantuUangMukaMain";//ok
//        $rekening = "1010050010";
//        $comModel = "ComRekeningPembantuUangMukaMain";//ok
//        $rekening = "1010050030";
//        $comModel = "ComRekeningPembantuUangMukaMain";//ok
//        $rekening = "1010050040";
        //--------------
        $this->load->model("Coms/$comModel");
        $last_tahun = previousYear();
        $tahun = date("Y");
        $periode = "tahunan";
        $cabang_id = "-1";
//        $gudang_id = "0";
//        $gudang_id = "9";
//        $cabang_id = "1";
        $arrGudang_id = array(
            "0",
//            "-1",
//            "-10",
//            96534007,
//            133566005,
//            155533044,
//            155533045,
//            155533046,
//            812540008,
//            812540009,
//            812540010,
//            812540011,
//            812540012,
//            812540013,
//            812540014,
//            812540015,
//            812540016,
//            1110538004,
//            1938571052,
//            1938571054,
//            2346579034,
//            2346579035,
//            2452585041,
//            2550583047,
//            2550583048,
//            3161590049,
//            3564593055,
//            3665594057,
//            3766595058,
//            3766595059,
//            3968597062,
//            4574603073,
//            4978607083
        );
        $gudang_id = $arrGudang_id[0];
//---------------------------
//        $cabang_id = "33";
//        $gudang_id = "0";
        $arrRekening = array();


        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            $c = new $comModel();
            $c->addFilter("periode='$periode'");
            $c->addFilter("thn='$last_tahun'");
            $c->addFilter("cabang_id='$cabang_id'");
            $c->addFilter("gudang_id='$gudang_id'");
            $c->addFilter("rekening='$rekening'");
            $cTmp = $c->lookupAll()->result();
            $tabel_mutasi = $c->getTableNameMaster()["mutasi"];
        }
        else {
            $c = new $comModel();
            $tabel_mutasi = $c->getTableNameMaster()["mutasi"];
            $tabel_mutasi_rek = "_" . $tabel_mutasi . "__" . $rekening;
            $where = array(
                "cabang_id" => $cabang_id,
                "gudang_id" => $gudang_id,
                "year(dtime)" => $last_tahun,
            );
            $this->db->where($where);
            $this->db->order_by("id", "ASC");
            $hasil = $this->db->get($tabel_mutasi_rek)->result();
            showLast_query("biru");
            if (sizeof($hasil) > 0) {
                foreach ($hasil as $hSpec) {
                    $hSpec->debet = $hSpec->debet_akhir;
                    $hSpec->kredit = $hSpec->kredit_akhir;
                    $cTmp[$hSpec->extern_id] = $hSpec;
                }
            }
        }
        showLast_query("biru");
        cekBiru(count($cTmp));
//        arrPrintCyan($cTmp);
//        mati_disini(__LINE__);

        if (sizeof($cTmp) > 0) {
            foreach ($cTmp as $nSpec) {
                $rekening = $nSpec->rekening;
                $extern_id = $nSpec->extern_id;
                $extern_nama = $nSpec->extern_nama;
                $extern2_id = $nSpec->extern2_id;
                $extern2_nama = $nSpec->extern2_nama;
                $extern3_id = $nSpec->extern3_id;
                $extern3_nama = $nSpec->extern3_nama;
                $extern4_id = $nSpec->extern4_id;
                $extern4_nama = $nSpec->extern4_nama;
                $debet = $nSpec->debet;
                $kredit = $nSpec->kredit;
                $qty_debet = $nSpec->qty_debet;
                $qty_kredit = $nSpec->qty_kredit;
                $rek_id = $nSpec->rek_id;
                if (!isset($arrRekening[$rekening][$extern_id]["saldo_awal_debet"])) {
                    $arrRekening[$rekening][$extern_id]["saldo_awal_debet"] = 0;
                }
                if (!isset($arrRekening[$rekening][$extern_id]["saldo_awal_kredit"])) {
                    $arrRekening[$rekening][$extern_id]["saldo_awal_kredit"] = 0;
                }
                if (!isset($arrRekening[$rekening][$extern_id]["saldo_awal_qty_debet"])) {
                    $arrRekening[$rekening][$extern_id]["saldo_awal_qty_debet"] = 0;
                }
                if (!isset($arrRekening[$rekening][$extern_id]["saldo_awal_qty_kredit"])) {
                    $arrRekening[$rekening][$extern_id]["saldo_awal_qty_kredit"] = 0;
                }
                $arrRekening[$rekening][$extern_id]["saldo_awal_debet"] += $debet;
                $arrRekening[$rekening][$extern_id]["saldo_awal_kredit"] += $kredit;
                $arrRekening[$rekening][$extern_id]["saldo_awal_qty_debet"] += $qty_debet;
                $arrRekening[$rekening][$extern_id]["saldo_awal_qty_kredit"] += $qty_kredit;
                $arrRekening[$rekening][$extern_id]["rekening"] = $rekening;
                $arrRekening[$rekening][$extern_id]["extern_id"] = $extern_id;
                $arrRekening[$rekening][$extern_id]["extern_nama"] = $extern_nama;
                $arrRekening[$rekening][$extern_id]["extern2_id"] = $extern2_id;
                $arrRekening[$rekening][$extern_id]["extern2_nama"] = $extern2_nama;
                $arrRekening[$rekening][$extern_id]["extern3_id"] = $extern3_id;
                $arrRekening[$rekening][$extern_id]["extern3_nama"] = $extern3_nama;
                $arrRekening[$rekening][$extern_id]["extern4_id"] = $extern4_id;
                $arrRekening[$rekening][$extern_id]["extern4_nama"] = $extern4_nama;
                $arrRekening[$rekening][$extern_id]["cabang_id"] = $cabang_id;
                $arrRekening[$rekening][$extern_id]["gudang_id"] = $gudang_id;
                $arrRekening[$rekening][$extern_id]["dtime"] = "2025-01-01 00:00:01";
                $arrRekening[$rekening][$extern_id]["fulldate"] = "2025-01-01";
                $arrRekening[$rekening][$extern_id]["rek_id"] = $rek_id;
            }
        }
//        arrPrint($arrRekening);


        $this->db->trans_start();


        if (sizeof($arrRekening) > 0) {
            foreach ($arrRekening as $rek => $rSpec) {
                $tabel_mutasi_rek = "_" . $tabel_mutasi . "__" . $rek;
                foreach ($rSpec as $pid => $pSpec) {
                    $debet = $pSpec["saldo_awal_debet"];
                    $kredit = $pSpec["saldo_awal_kredit"];
                    $qty_debet = $pSpec["saldo_awal_qty_debet"];
                    $qty_kredit = $pSpec["saldo_awal_qty_kredit"];
                    $extern2_id = $pSpec["extern2_id"];
                    $extern3_id = $pSpec["extern3_id"];
                    $extern4_id = $pSpec["extern4_id"];

                    $where = array(
                        "cabang_id" => $cabang_id,
                        "gudang_id" => $gudang_id,
                        "year(dtime)" => $tahun,
                        "extern_id" => $pid,
//                        "extern2_id" => $extern2_id,
//                        "extern3_id" => $extern3_id,
//                        "extern4_id" => $extern4_id,
                    );
                    $this->db->where($where);
                    $this->db->order_by("id", "ASC");
                    $this->db->limit(1);
                    $hasil = $this->db->get($tabel_mutasi_rek)->result();
                    showLast_query("biru");
                    if (sizeof($hasil) > 0) {
                        // update kolom saldo awal
                        $id_tbl = $hasil[0]->id;
                        $where_update = array(
                            "id" => $id_tbl,
                        );
                        $data_update = array(
                            "saldo_awal_debet" => $debet,
                            "saldo_awal_kredit" => $kredit,
                            "saldo_awal_qty_debet" => $qty_debet,
                            "saldo_awal_qty_kredit" => $qty_kredit,
                        );
                        $this->db->where($where_update);
                        $this->db->update($tabel_mutasi_rek, $data_update);
                        showLast_query("orange");
                    }
                    else {
                        $pSpec["debet_akhir"] = $pSpec["saldo_awal_debet"];
                        $pSpec["kredit_akhir"] = $pSpec["saldo_awal_kredit"];
                        $pSpec["qty_debet_akhir"] = $pSpec["saldo_awal_qty_debet"];
                        $pSpec["qty_kredit_akhir"] = $pSpec["saldo_awal_qty_kredit"];
                        $this->db->insert($tabel_mutasi_rek, $pSpec);
                        showLast_query("hijau");
                    }

//                    break;
                }

            }
        }


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");

    }

    public function patchReferensiPembatalan()
    {
        $comModel = "ComRekeningPembantuProdukPerSerialIntransit";//ok
        $rekening = "1010030030";
        $jenis = "9912";
        $jenis = array(
            "9911",
            "9912",
        );
        $this->load->model("Coms/$comModel");
        $this->load->model("MdlTransaksi");
//        $pakai_ini = 0;
//        if($pakai_ini == 1){
//            $c = New $comModel();
//            $tabel_mutasi = $c->getTableNameMaster()["mutasi"];
//            $tabel_mutasi_rek = "_" . $tabel_mutasi . "__" . $rekening;
//        }
//        else{
//            $tabel_mutasi_rek = "__rek_pembantu_pph__2030030";
//        }
//        if (is_array($jenis)) {
//            $this->db->where_in("jenis", $jenis);
//        }
//        else {
//            $where = array(
//                "jenis" => $jenis,
//            );
//            $this->db->where($where);
//        }
//        $this->db->group_by("transaksi_id");
//        $hasil = $this->db->get($tabel_mutasi_rek)->result();

//        showLast_query("biru");
//        cekBiru(count($hasil));
//        if (sizeof($hasil) > 0) {
//            $arrTrIDs = array();
//            $arrTrData = array();
//            foreach ($hasil as $spec) {
//                $arrTrIDs[$spec->transaksi_id] = $spec->transaksi_id;
//            }
//        }

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("jenis in ('" . implode("','", $jenis) . "')");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $arrTrData[$trSpec->reference_id] = array(
                    "cancel_dtime" => $trSpec->dtime,
                    "cancel_id" => $trSpec->oleh_id,
                    "cancel_name" => $trSpec->oleh_nama,
                    "cancel_transaksi_id" => $trSpec->id,
                    "cancel_transaksi_nomer" => $trSpec->nomer,
                    "cancel_transaksi_jenis" => $trSpec->jenis,
                    "deskripsi" => "pembatalan transaksi",
                );
            }
        }

//        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_start();


        if (isset($arrTrData) && (sizeof($arrTrData) > 0)) {
            foreach ($arrTrData as $trid => $data) {
                $where_update = array(
                    "id" => $trid,
                );
                $tr->setFilters(array());
                $tr->updateData($where_update, $data);
                showLast_query("orange");
//                break;
            }
        }


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    public function adjustmentDadakan()
    {
        $transaksi_id = "456633";
        $transaksi_nomer = "999.1.4";
        $transaksi_jenis = "999";
        $placeID = "-1";
        $placeName = "DC/Pusat";
        $hutang_pph = "3827691";
        $dtime = "2025-05-21 12:23:02";
        $fulldate = "2025-05-21 12:23:02";

        $this->db->trans_start();

        $this->load->model("Coms/ComRekeningPembantuPph");
        $this->load->model("Coms/ComRekeningPembantuEfisiensiBiaya");

        $arrHutangPPh[0] = array(
            "comName" => "RekeningPembantuPph",
            "loop" => array(
                "2030010" => $hutang_pph,// hutang pph21
            ),
            "static" => array(
                "cabang_id" => "$placeID",
                "extern_id" => "$placeID",// diisi id bank
                "extern_nama" => "$placeName",// diisi nama bank
                "jenis" => $transaksi_jenis,
                "transaksi_no" => $transaksi_nomer,
                "harga" => $hutang_pph,
                "extern2_id" => "3",// diisi id bank
                "extern2_nama" => "cabang",// diisi nama bank
                "transaksi_id" => $transaksi_id,
                "transaksi_no" => $transaksi_nomer,
                "dtime" => $dtime,
                "fulldate" => $fulldate,
            ),
        );
//        $cp = New ComRekeningPembantuPph();
//        $cp->pair($arrHutangPPh);
//        $cp->exec();


        $arrEfisiensi[0] = array(
            "comName" => "RekeningPembantuEfisiensiBiaya",
            "loop" => array(
                "3020010" => -$hutang_pph,//efisiensi
            ),
            "static" => array(
                "cabang_id" => 1,
                "extern_id" => "4",
                "extern_nama" => "quality",
                "extern2_id" => "4",
                "extern2_nama" => "quality",
                "produk_qty" => -1,
                "produk_nilai" => $hutang_pph,
                "jenis" => $transaksi_jenis,
                "transaksi_id" => $transaksi_id,
                "transaksi_no" => $transaksi_nomer,
                "dtime" => $dtime,
                "fulldate" => $fulldate,
            ),
        );
        $cp = new ComRekeningPembantuEfisiensiBiaya();
        $cp->pair($arrEfisiensi);
        $cp->exec();


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");

    }

    public function patchuangmukareference()
    {
        $this->load->model("MdlTransaksi");
        $rekening = "1010050010";
//        $rekening = "1010050030";
        $jenisTr = "4643";
//        $jenisTr = "464";
        $tbl = "_rek_pembantu_uang_muka_reference_cache";
        $arrWhere = array(
            "periode" => "forever",
            "rekening" => "1010050010",
        );
//        $this->db->where($arrWhere);
//        $query = $this->db->get($tbl)->result();
//        arrPrint($query);

        $this->db->trans_start();


        $tr = new MdlTransaksi();
        $tr->addFilter("jenis='$jenisTr'");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $trid = $trSpec->id;
                $trr = new MdlTransaksi();
                $trr->setFilters(array());
                $trr->setJointSelectFields("transaksi_id, main");
                $trr->addFilter("transaksi_id='$trid'");
                $tmpReg = $trr->lookupDataRegistries()->result();
                $main = blobDecode($tmpReg[0]->main);
                arrPrint($main);
                switch ($jenisTr) {
                    case "4643":
                        $referensi_so_id = $main["referensi_so"];
                        $referensi_so_nomer = $main["referensi_so__nomer"];
                        $referensi_so_fulldate = $main["referensi_so__fulldate"];
                        break;
                    case "464":
                        $referensi_so_id = isset($main["elementReference__extern2_id"]) ? $main["elementReference__extern2_id"] : $main["referensi_so"];
                        $referensi_so_nomer = isset($main["elementReference__extern2_nama"]) ? $main["elementReference__extern2_nama"] : $main["referensi_so__nomer"];
                        $referensi_so_fulldate = isset($main["referensi_so__fulldate"]) ? $main["referensi_so__fulldate"] : $main["referensi_so__fulldate"];
                        break;
                }

                $update_data = array(
                    "reference_id" => $referensi_so_id,
                    "reference_nomer" => $referensi_so_nomer,
                );
                $update_where = array(
                    "id" => $trid,
                );
                $trr = new MdlTransaksi();
                $trr->setFilters(array());
                $trr->updateData($update_where, $update_data);
                showLast_query("orange");

                $update_data_0 = array(
                    "extern5_id" => $trSpec->id,
                    "extern5_nama" => $trSpec->nomer,
                );
                $update_where_0 = array(
                    "periode" => "forever",
                    "rekening" => "$rekening",
                    "extern2_id" => $referensi_so_id,
                );
                $this->db->where($update_where_0);
                $this->db->update($tbl, $update_data_0);
                showLast_query("ungu");

//                break;
            }
        }


//        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }


    public function patchUangmukaRelasiMati()
    {
        $this->load->model("MdlTransaksi");
        //-----------------------------------------
        $arrAdjustBalikKonsumen = array();
        $tbl = "transaksi_payment_source";
        $label = "uang muka";
        $where = array(
            "label" => $label,
            "sisa>" => 10,
        );
        $this->db->where($where);
        $query = $this->db->get($tbl)->result();
        showLast_query("biru");
        cekBiru(count($query));
        if (sizeof($query) > 0) {
            foreach ($query as $spec) {
                $tblid = $spec->id;
                $trid = $spec->transaksi_id;
                $nomer = $spec->nomer;
                $extern_id = $spec->extern_id;
                $extern_nama = $spec->extern_nama;
                $cabang_id = $spec->cabang_id;
                $sisa = $spec->sisa;
                $dtime = $spec->dtime;
                $fulldate = $spec->fulldate;

                $tr = new MdlTransaksi();
                $tr->addFilter("id='$trid");
                $trTmp = $tr->lookupAll()->result();
                $trash_4 = $trTmp[0]->trash_4;
                if ($trash_4 == 1) {
                    cekHere("[$trid] [$nomer] [$sisa] [$trash_4] [$extern_nama] [$dtime]");
                    $arrAdjustBalikKonsumen[$tblid] = array(
                        "trid" => $trid,
                        "nomer" => $nomer,
                        "extern_id" => $extern_id,
                        "extern_nama" => $extern_nama,
                        "cabang_id" => $cabang_id,
                        "sisa" => $sisa,
                        "dtime" => $dtime,
                        "fulldate" => $fulldate,
                        "trash_4" => $trash_4,
                    );
                }
            }
            arrPrintCyan($arrAdjustBalikKonsumen);
        }
        //-----------------------------------------
        $arrAdjustBalikSupplier = array();
        $tbl = "transaksi_uang_muka_source";
        $label = "uang muka";
        $where = array(
            "label" => $label,
            "sisa>" => 10,
            "extern2_id>" => 0,
            "extern_label2" => "vendor",
        );
        $this->db->where($where);
        $query = $this->db->get($tbl)->result();
        showLast_query("kuning");
        cekKuning(count($query));
        if (sizeof($query) > 0) {
            foreach ($query as $spec) {
                $tblid = $spec->id;
                $trid = $spec->transaksi_id;
                $nomer = $spec->nomer;
                $extern_id = $spec->extern_id;
                $extern_nama = $spec->extern_nama;
                $extern2_id = $spec->extern2_id;
                $extern2_nama = $spec->extern2_nama;
                $cabang_id = $spec->cabang_id;
                $sisa = $spec->sisa;
                $dtime = $spec->dtime;
                $fulldate = $spec->fulldate;

                $tr = new MdlTransaksi();
                $tr->addFilter("id='$extern2_id");
                $trTmp = $tr->lookupAll()->result();
                $trash_4 = $trTmp[0]->trash_4;
                if ($trash_4 == 1) {
                    cekHere("[$trid] [$extern2_id] [$nomer] [$sisa] [$trash_4] [$extern_nama] [$dtime]");
                    $arrAdjustBalikSupplier[$tblid] = array(
                        "trid" => $trid,
                        "nomer" => $nomer,
                        "extern_id" => $extern_id,
                        "extern_nama" => $extern_nama,
                        "extern2_id" => $extern2_id,
                        "extern2_nama" => $extern2_nama,
                        "cabang_id" => $cabang_id,
                        "sisa" => $sisa,
                        "dtime" => $dtime,
                        "fulldate" => $fulldate,
                        "trash_4" => $trash_4,
                    );
                }
            }
            arrPrintPink($arrAdjustBalikSupplier);
        }


    }


    public function patchUangMukaProject()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComRekeningPembantuCustomerDetail");
        $this->load->model("Mdls/MdlPaymentSource");

        $tr = new MdlTransaksi();
        $crc = new ComRekeningPembantuCustomerDetail();
        $psr = new MdlPaymentSource();
        $subTotalProjectPym = array();
        $subTotalKonsumenPym = array();
        $subTotalKonsumenRek = array();
        $projectStarts = array();


        $psr->addFilter("sisa>0");
        $psr->addFilter("project_id>0");
        $psr->addFilter("jenis='4467'");
        $psr->addFilter("label='uang muka konsumen'");
        $psrTmp = $psr->lookupAll()->result();
        if (sizeof($psrTmp) > 0) {
            foreach ($psrTmp as $psrSpec) {
                $psrid = $psrSpec->id;
                $trid = $psrSpec->transaksi_id;
                $projectid = $psrSpec->project_id;
                $externid = $psrSpec->extern_id;
                $sisa = $psrSpec->sisa;
                if (!isset($subTotalKonsumenPym[$externid])) {
                    $subTotalKonsumenPym[$externid] = 0;
                }
                $subTotalKonsumenPym[$externid] += $sisa;
                if (($projectid > 0) && ($sisa > 0)) {
                    $subTotalProjectPym[$projectid] = array(
                        "idtbl" => $psrid,
                        "sisa" => $sisa,
                    );
                }

            }
        }

        $crc->setFilters(array());
        $crc->addFilter("rekening='2010050'");
        $crc->addFilter("extern2_id='2010050060'");
//        $crc->addFilter("project_id>0");
        $crc->addFilter("periode='forever");
        $crcTmp = $crc->lookupAll()->result();
        if (sizeof($crcTmp) > 0) {
            foreach ($crcTmp as $psrSpec) {
                $psrid = $psrSpec->id;
//                $trid = $psrSpec->transaksi_id;
//                $projectid = $psrSpec->project_id;
                $externid = $psrSpec->extern_id;
                $kredit = $psrSpec->kredit;
                if (!isset($subTotalKonsumenRek[$externid])) {
                    $subTotalKonsumenRek[$externid] = 0;
                }
                $subTotalKonsumenRek[$externid] += $kredit;
            }
        }


        $tr->addFilter("jenis='588st'");
        $tr->addFilter("trash_4=0");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $trid = $trSpec->id;
                $nomer = $trSpec->nomer;
                $jenis = $trSpec->jenis;
                $dtime = $trSpec->dtime;
                $fulldate = $trSpec->fulldate;
                $trash_4 = $trSpec->trash_4;
                $customers_id = $trSpec->customers_id;
                $project_id = $trSpec->project_id;
                $project_nama = $trSpec->project_nama;
                if ($project_id > 0) {
                    $projectStarts[$project_id] = $project_nama;
                }
            }
        }


        $this->db->trans_start();


        arrPrintWebs($projectStarts);
        foreach ($projectStarts as $project_id => $project_nama) {
            $pym_tbl_id = isset($subTotalProjectPym[$project_id]["idtbl"]) ? $subTotalProjectPym[$project_id]["idtbl"] : 0;
            $pym_tbl_sisa = isset($subTotalProjectPym[$project_id]["sisa"]) ? $subTotalProjectPym[$project_id]["sisa"] : 0;
            if ($pym_tbl_id > 0) {
                $where_update = array(
                    "id" => $pym_tbl_id,
                );
                $update_data = array(
                    "sisa" => 0,
                    "terbayar" => $pym_tbl_sisa,
                );
                $psr->setFilters(array());
                $psr->updateData($where_update, $update_data);
                showLast_query("orange");
            }
        }


//        arrPrintPink($subTotalProjectPym);


//        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    public function patchUangMukaKonsumenSO()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComRekeningPembantuCustomerDetail");
        $this->load->model("Mdls/MdlPaymentSource");

        $tr = new MdlTransaksi();
        $crc = new ComRekeningPembantuCustomerDetail();
        $psr = new MdlPaymentSource();
        $subTotalProjectPym = array();
        $subTotalKonsumenPym = array();
        $subTotalKonsumenRek = array();
        $projectStarts = array();


        $psr->addFilter("sisa>0");
        $psr->addFilter("jenis='4467'");
        $psr->addFilter("extern2_nama!=NULL");
        $psr->addFilter("label='uang muka konsumen'");
        $psrTmp = $psr->lookupAll()->result();
        showLast_query("biru");
        if (sizeof($psrTmp) > 0) {
            foreach ($psrTmp as $psrSpec) {
                $psrid = $psrSpec->id;
                $trid = $psrSpec->transaksi_id;
                $projectid = $psrSpec->project_id;
                $externid = $psrSpec->extern_id;
                $extern2nama = $psrSpec->extern2_nama;
                $extern2id = $psrSpec->extern2_id;

                $arrNomer[$psrid] = $extern2nama;
                $arrNomerPymRef[$extern2nama] = $extern2id;
            }
        }

        if (sizeof($arrNomer) > 0) {
            $tr->addFilter("nomer in ('" . implode("','", $arrNomer) . "')");
            $trTmp = $tr->lookupAll()->result();
            foreach ($trTmp as $spec) {
                $nomerTrid[$spec->nomer] = $spec->id;
            }
        }


        $this->db->trans_start();

//        cekBiru(count($nomerTrid));
        arrPrint($nomerTrid);

        if (sizeof($arrNomer) > 0) {
            foreach ($arrNomer as $tblid => $pymRefNomer) {
                $pymRefIdTbl = $arrNomerPymRef[$pymRefNomer];
                $pymRefId = isset($nomerTrid[$pymRefNomer]) ? $nomerTrid[$pymRefNomer] : 0;
                if (($pymRefId > 0) && ($pymRefIdTbl != $pymRefId)) {
                    $data = array(
                        "extern2_id" => $pymRefId,
                    );
                    $where = array(
                        "id" => $tblid,
                    );
                    $psr = new MdlPaymentSource();
                    $psr->setFilters(array());
                    $psr->updateData($where, $data);
                    showLast_query("orange");
                }
            }
        }


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    public function patch4892()
    {
        $this->load->model("MdlTransaksi");
        $jenis = "4892";
        $trIDs = array();
        $defaultItems2_sum = blobEncode(array());
        cekHere($defaultItems2_sum);


        $tr = New MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("jenis='$jenis'");
        $tmp = $tr->lookUpAllPaymentSrc()->result();
        showLast_query("biru");
        cekBiru(count($tmp));
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $spec) {
                $trIDs[$spec->id] = $spec->transaksi_id;
            }
        }

        $this->db->trans_start();

        if (sizeof($trIDs) > 0) {
            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $tr->setJointSelectFields("transaksi_id, items2_sum");
            $tr->addFilter("transaksi_id in ('" . implode("','", $trIDs) . "')");
            $reg = $tr->lookupDataRegistries()->result();
            arrPrintWebs($reg);
            foreach ($reg as $regSpec) {
                $items2_sum_trid = $regSpec->transaksi_id;
                $items2_sum_reg = $regSpec->items2_sum;
                if ($defaultItems2_sum !== $items2_sum_reg) {
                    arrPrintCyan($regSpec);
//                    arrPrintHitam(blobDecode($items2_sum_reg));

                    //update tabel registry, kolom items2_sum
                    $where = array(
                        "transaksi_id" => $items2_sum_trid,
                    );
                    $data = array(
                        "items2_sum" => array(),
                    );
                    $trreg = New MdlTransaksi();
                    $trreg->setFilters(array());
                    $trreg->updateDataRegistry($where, $data);
                    showLast_query("orange");

                }


            }
        }

//        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");

    }
}


