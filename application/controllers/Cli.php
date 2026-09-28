<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 03/04/2019
 * Time: 13.50
 */

class Cli extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
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
            "hpp" => array(
                "582spd",
                "982",
                "382spd",
                // "982",
            ),
            "biaya" => array(
                "9982",
                "771",
                "762",
                "2677",

                "9983",
                "7762",
                "2675",
//                "771", // kelompokan


                "476",
//                "771", // kelompokan
                "9984",
                "2676",
//                "762", // kelompokan


//                "999",
//                "9912",
//                "9911",
//                "888_1",
//                "8787",
//                "743",
//                "462",
//                "4449",
//                "2674",
//                "1677",
//                "1675",
//                "1674",
//                "1463",
//                "1462",
            ),
            "pembelian_supplies" => array(
                "461",
                "961",
            ),
            "pembelian_produk" => array(
                "467",
                "967",
            ),
        );

//        $this->reportJenis = array(
//            "pre_penjualan"          => array(
//                "582spo",
//                "382spo",
//                // "582so",
//            ),
//            "pre_penjualan_canceled" => array(
//                "582spo",
//                "382spo",
//                // "582so",
//            ),
//            "penjualan"              => array(
//                "582spd",
//                "982",
//                "382spd",
//                // "982",
//            ),
//            "hpp"                    => array(
//                "582spd",
//                "982",
//                "382spd",
//                // "982",
//            ),
//            "biaya"                  => array(
//                "999",
//                "9984",
//                "9983",
//                "9982",
//                "9912",
//                "9911",
//                "888_1",
//                "8787",
//                "7762",
//                "771",
//                "762",
//                "743",
//                "476",
//                "462",
//                "4449",
//                "2677",
//                "2676",
//                "2675",
//                "2674",
//                "1677",
//                "1675",
//                "1674",
//                "1463",
//                "1462",
//            ),
//            "pembelian_supplies"     => array(
//                "461",
//                "961",
//            ),
//            "pembelian_produk"       => array(
//                "467",
//                "961",
//            ),
//        );
    }

    public function show_notifikasi()
    {
        // arrPrint($_SESSION['webs']);
        // arrPrint($_SESSION['webs']['cart']);
        //region shopingcart
        $carts = cart_webs();
        $jml_item = sizeof($carts);
        $qty_item = 0;
        $arrOpen = array();
        if (sizeof($carts) > 0) {

            foreach ($carts as $pid => $items) {
                $jml = $items['jml'];
                $qty_item += $jml;
            }
        }

        $arrOpen['cart_item']['nilai'] = $qty_item;
        //endregion

        echo json_encode($arrOpen);

        // arrPrint($arrOpen);
        // cekMerah("$jml_item / $qty_item");
    }

    public function reloadSessionWebs()
    {
        unset($_SESSION['webs']);
        mati_disini();
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function testEmail()
    {
        // $this->load->model("MdlTransaksi");


        $ml = new SmtpMailer();
        cekHere(dtimeNow());

        $ml->setSubject("testing " . dtimeNow());
        $ml->setAddressFrom(array("maspengirim" => "namakamoe@gmail.com"));
        $ml->setAddressTo(array(
            "thomas" => "thomas.jogja@gmail.com",
            // "thomase" => "yulaikha.21@gmail.com",
            // "thomase" => "namakamoe@gmail.com",
        ));
        $cek = $ml->kirim_email("percobaan beroo <h1>wedew</h1>" . $_SERVER['HTTP_HOST'] . "<br>" . $_SERVER['REMOTE_ADDR']);
        echo $cek == 1 ? "DONE" : "FAIL";
        arrPrint($cek);
    }

    public function genPenjualan0()
    {

        // header("Refresh:2");
        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        $jenis = "penjualan";
        // $jenis = key($reportJenis);
        // cekMerah("$jenis");
        $jenis_list = implode("','", $reportJenis[$jenis]);
        // cekHere($jenis." ".$jenis_list);
        //         matiHere(__METHOD__ ." @". __LINE__);
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlReport");
        $tr = new MdlTransaksi();

        // region membaca 1 data
        // $condite = "status = '1'";
        $tr->setFilters(array());
        $this->db->order_by("id");
        $this->db->limit(1);
        $tr->addFilter("id_master>'0'");
        // $tr->addFilter("jenis in('".$jenis_list."')");
        $tr->addFilter("r_jenis='0'");
        $this->db->where("jenis in('" . $jenis_list . "')");
        // $scr_0 = $tr->lookupByCondition($condite)->result();
        $scr = $tr->lookupAll()->result();
        cekHijau($this->db->last_query());
        sizeof($scr) < 1 ? matiHere(__METHOD__ . " source data sudah habis") : "";
        $scr_0 = $scr[0];
        $transaksi_id = $scr_0->id;
        $transaksi_jenis = $scr_0->jenis;
        $id_master = $scr_0->id_master;
        // $oleh_nama = $scr_0->oleh_nama;
        $dtime = $transaksi_dtime = $scr_0->dtime;

        $tanggal = formatTanggal($dtime, 'Y-m-d');
        $tg = formatTanggal($dtime, 'd') * 1;
        $mg = formatTanggal($dtime, 't') * 1;
        $bl = formatTanggal($dtime, 'm') * 1;
        $th = formatTanggal($dtime, 'Y') * 1;


        // region transaksi awal SO
        $scr_1Koloms = array(
            "oleh_nama",
            "oleh_id",
        );
        $tr->setFilters(array());
        $this->db->select($scr_1Koloms);
        $scr_1 = $tr->lookupByCondition("id = '$id_master'")->result()[0];
        cekMerah($this->db->last_query());
        foreach ($scr_1Koloms as $kolom) {
            $$kolom = $scr_1->$kolom;
        }
        // endregion transaksi awal SO

        // endregion membaca 1 data


        // arrPrint($scr);
        // arrPrint($scr_1);
        // mati_disini(__LINE__);

        // region membaca data registry
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "jml",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
        );
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
        $tr->addFilter("param ='items'");
        $regs = $tr->lookupRegistries()->result();
        cekLime($this->db->last_query());
        // arrPrint($regs);
        $regDatas = array();
        $sumNett1 = 0;
        foreach ($regs as $reg) {
            $trId = $reg->transaksi_id;
            // $refId = $reg->referenceID;
            $regValues = blobDecode($reg->values);
            // cekBiru("trId:: $trId");
            // arrPrint($regValues);
            foreach ($regValues as $regValue) {
                foreach ($itemKoloms as $itemKolom) {
                    $$itemKolom = $regValue[$itemKolom];

                    $specs[$itemKolom] = $regValue[$itemKolom];
                }
                // isset($sumJml) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                isset($sumNett1) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                $datas[] = $specs;

            }
            // $regDatas[$trId]['nett1'] = $regValues['nett1'];
            // $regDatas[$trId]['referenceID'] = isset($regValues['referenceID']) ? $regValues['referenceID'] : 0;
            // arrPrint($regValues);
        }
        // endregion membaca 1 data

        // arrPrint($datas);
        // cekLime("nett1:: $sumNett1");
        // mati_disini(__LINE__);

        // region marking data transaksi yg sudah dibaca
        $tr->setFilters(array());
        $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));
        cekHere($this->db->last_query());
        // endregion marking data transaksi yg sudah dibaca

        $cek = 0;
        foreach ($datas as $data) {
            $dbReport = new MdlReport();
            $cek++;
            // $dbReport->trans_start();

            foreach ($itemKoloms as $itemKolom) {
                $$itemKolom = $data[$itemKolom];
            }
            switch ($transaksi_jenis) {
                case "582spd":
                    $newDatas = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,

                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,

                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => $sub_nett1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    break;
                case "982":
                    $newDatas = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    break;
                default:
                    matiHere(__METHOD__ . " @" . __LINE__);
                    break;
            }


            $dbReport->setDebug(true);
            $dbReport->setJenis($jenis);
            $dbReport->setOrder("id DESC");
            $dbReport->setLimit("1");

            //region harian
            $periode = "harian";
            $dbReport->setPeriode($periode);
            $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            // $dbReport->setTahun($th);
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTanggal = $tCounter->tanggal;

                if ($tanggal != $lastTanggal) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTanggal = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$cek *** $periode:: $tanggal != $lastTanggal :: new::$counter last::$lastCounter");

            $counter_hr["counter"] = $counter;
            $newDatas_hr = $newDatas + $counter_hr;
            $dbReport->setDatas($newDatas_hr);
            $dbReport->writePenjualanSellerProduk($olehID, $id);
            // endregion

            // region mingguan
            $periode = "mingguan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            $dbReport->setTahun($th);
            // $dbReport->setOrder("id DESC");
            // $dbReport->setLimit("1");
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastMg = $tCounter->mg;
                $lastTh = $tCounter->th;

                if ($th . $mg != $lastTh . $lastMg) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastMg = "";
                $lastTh = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$mg != $lastTh.$lastMg :: new::$counter last::$lastCounter");

            $counter_mg["counter"] = $counter;
            $newDatas_mg = $newDatas + $counter_mg;
            $dbReport->setDatas($newDatas_mg);
            $dbReport->writePenjualanSellerProduk($olehID, $id);
            // endregion mingguan

            // region bulanan
            $periode = "bulanan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            $dbReport->setMinggu($bl);
            // $dbReport->setBulan($bl);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastBl = $tCounter->bl;
                $lastTh = $tCounter->th;

                if ($th . $bl != $lastTh . $lastBl) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastBl = "";
                $lastTh = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$bl != $lastTh.$lastBl :: new::$counter last::$lastCounter");

            $counter_bl["counter"] = $counter;
            $newDatas_bl = $newDatas + $counter_bl;
            $dbReport->setDatas($newDatas_bl);
            $dbReport->writePenjualanSellerProduk($olehID, $id);
            // endregion bulanan

            // region tahunan
            $periode = "tahunan";
            $dbReport->setPeriode($periode);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTh = $tCounter->th;

                if ($th != $lastTh) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTh = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th != $lastTh :: new::$counter last::$lastCounter");

            $counter_th["counter"] = $counter;
            $newDatas_th = $newDatas + $counter_th;
            $dbReport->setDatas($newDatas_th);
            $dbReport->writePenjualanSellerProduk($olehID, $id);
            // endregion tahunan

            // $dbReport->trans_complete() or matiHere("gagal ocmit");
        }


    }

    public function genBiayaOld()
    {

        $arrRekeningBiaya = array(
            'biaya gaji',
            'biaya umum',
            'biaya usaha',
            'biaya jasa',
            'biaya supplies',
            'biaya produksi',
            'biaya operasional',
            'kerugian',
            'return penjualan',
            'diskon',
            'kerugian kurs',
            'beban lain lain',
            'biaya kirim',
            'tenaga kerja',
            'penyusutan kendaraan',
            'penyusutan peralatan kantor',
            'penyusutan peralatan produksi',
            'penyusutan mesin produksi',
            'penyusutan tanah dan bangunan',
            'biaya sewa'
        );

//        cekHere( "'" . implode("','", $arrRekeningBiaya) . "'" );

        // header("Refresh:2");
        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        $jenis = "biaya";
        // $jenis = key($reportJenis);
        // cekMerah("$jenis");
        $jenis_list = implode("','", $reportJenis[$jenis]);
        // cekHere($jenis." ".$jenis_list);
        //         matiHere(__METHOD__ ." @". __LINE__);
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComJurnal");
        $this->load->model("Mdls/MdlReport");
        $tr = new MdlTransaksi();
        $ju = new ComJurnal();

        $this->db->limit(1);
//        $this->db->where("jenis in('" . $jenis_list . "')");
        $this->db->where("rekening in(" . "'" . implode("','", $arrRekeningBiaya) . "'" . ")");
        // $ju->addFilter("rekening in('penjualan','return penjualan')");
        $ju->addFilter("r_jenis='0'");
        $ju->addFilter("jenis!=''");
//        $ju->addFilter("limit 1");

        $ju->setSortBy(array("kolom" => "id", "mode" => "asc"));
        $scrJurnal = $ju->lookupAll()->result();

//        arrPrint($scrJurnal);

        cekMerah($this->db->last_query());
        sizeof($scrJurnal) < 1 ? matiHere(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // sizeof($scrJurnal) < 1 ? cekHitam(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // arrPrint($scrJurnal);
        $jTransaksi_id = $scrJurnal[0]->transaksi_id;


        $this->db->limit(1);
        // $ju->addFilter("rekening='penjualan'");
        $this->db->where("rekening in(" . "'" . implode("','", $arrRekeningBiaya) . "'" . ")");
        $ju->addFilter("transaksi_id='" . $jTransaksi_id . "'");

        $jurnalBiayas = $ju->lookupAll()->result();

        cekLime($this->db->last_query());
        arrPrint($jurnalBiayas);
        if (sizeof($jurnalBiayas) > 1) {
            $cekDatas = "trId: $jTransaksi_id";
            $cekDatas .= "<br>trId: $jTransaksi_id";
            matiHere(__METHOD__ . " ada kemungkinan doble jurnal, harap diperikasa $cekDatas");
        };
        // arrPrint($jurnalPenjualans);
        $jurnalIds[] = $jurnalBiayas[0]->id;

        matiHere();

        matiHere(__LINE__ . __METHOD__);

        // region membaca 1 data
        // $condite = "status = '1'";

//        $tr->setFilters(array());
//        $this->db->order_by("id");
//        $this->db->limit(1);
//        $tr->addFilter("id='" . $jTransaksi_id . "'");
//        $tr->addFilter("id_master>'0'");
//        // $tr->addFilter("jenis in('".$jenis_list."')");
//        // $tr->addFilter("r_jenis='0'");
//        $this->db->where("jenis in('" . $jenis_list . "')");
//        // $scr_0 = $tr->lookupByCondition($condite)->result();
//        $scr = $tr->lookupAll()->result();
//        cekHijau($this->db->last_query());
//
//        sizeof($scr) == 0 ? matiHere(__LINE__ . __METHOD__ . " source data sudah habis") : "";
//        $scr_0 = $scr[0];
//
//        $transaksi_id = $scr_0->id;
        $transaksi_jenis = $jurnalBiayas[0]->jenis;
//        $id_master = $scr_0->id_master;

        // $oleh_nama = $scr_0->oleh_nama;
        $dtime = $transaksi_dtime = $jurnalBiayas[0]->dtime;

        $tanggal = formatTanggal($dtime, 'Y-m-d');
        $tg = formatTanggal($dtime, 'd') * 1;
        $mg = formatTanggal($dtime, 't') * 1;
        $bl = formatTanggal($dtime, 'm') * 1;
        $th = formatTanggal($dtime, 'Y') * 1;


        // region transaksi awal SO
//        $scr_1Koloms = array(
//            "oleh_nama",
//            "oleh_id",
//        );
//        $tr->setFilters(array());
//        $this->db->select($scr_1Koloms);
//        $scr_1 = $tr->lookupByCondition("id = '$id_master'")->result()[0];
//        cekMerah($this->db->last_query());
//        foreach ($scr_1Koloms as $kolom) {
//            $$kolom = $scr_1->$kolom;
//        }
        // endregion transaksi awal SO

        // endregion membaca 1 data


        // arrPrint($scr);
        // arrPrint($scr_1);
        // mati_disini(__LINE__ . __METHOD__);

        // region membaca data registry
        // main
//        $itemKoloms = array(
//            "id",
//            "nama",
//            "produk_kode",
//            "jml",
//            "harga",
//            "sub_nett1",
//            "sub_ppn",
//            "sub_nett2",
//            "pihakID",
//            "pihakName",
//            "placeID",
//            "placeName",
//            "gudangID",
//            "gudangName",
//            "olehID",
//            "olehName",
//        );
//        $tr->setFilters(array());
//        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
//        $tr->addFilter("param ='main'");
//        $regMains = $tr->lookupRegistries()->result();
//        cekLime($this->db->last_query() . " ::@" . __LINE__);
//        $mainDatas = blobDecode($regMains[0]->values);
//        // arrPrintWebs($mainDatas);
//        if ((isset($mainDatas['shippingService'])) && ($mainDatas['shippingService'] == "ongkir_ppn_by_cust")) {
//            $specMains['ongkir_net'] = $mainDatas['ongkir_net'];
//        }
//        else {
//            $specMains['ongkir_net'] = 0;
//        }

        // $datas[] = $specMains;
        // item
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "jml",
            "harga",
            "debet",
            "kredit",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
            "ongkir_net",
        );
//        $tr->setFilters(array());
//        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
//        $tr->addFilter("param ='items'");
//        $regs = $tr->lookupRegistries()->result();
        cekLime($this->db->last_query());
        // arrPrint($regs);
        $regDatas = array();
        $sumNett1 = 0;
        $sumHarga = 0;
//        foreach ($regs as $reg) {
//            $trId = $reg->transaksi_id;
//            // $refId = $reg->referenceID;
//            $regValues = blobDecode($reg->values);
//            // cekBiru("trId:: $trId");
//             arrPrint($regValues);

//            foreach ($regValues as $regValue) {
//                foreach ($itemKoloms as $itemKolom) {
//                    $$itemKolom = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;
//
//                    // $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;
//                    $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : (isset($specMains[$itemKolom]) ? $specMains[$itemKolom] : "" );
//                }
        // isset($sumJml) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
//                isset($sumNett1) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
//                isset($sumHarga) ? $sumHarga += $harga : $sumHarga = 0;
//                $datas[] = $specs;

//            }
        // $regDatas[$trId]['nett1'] = $regValues['nett1'];
        // $regDatas[$trId]['referenceID'] = isset($regValues['referenceID']) ? $regValues['referenceID'] : 0;
        // arrPrint($regValues);
//        }
        $debet = 0;
        $kredit = 0;
        if (sizeof($jurnalBiayas) > 0) {
            foreach ($jurnalBiayas as $jDatas) {
                foreach ($itemKoloms as $itemKolom) {
                    $specs[$itemKolom] = isset($jDatas->$itemKolom) ? $jDatas->$itemKolom : 0;
                }
                $datas[] = $specs;
            }
        }

//arrPrint($datas);
//        matiHere();
        // endregion membaca 1 data

        // arrPrint($datas);


        // region marking data transaksi yg sudah dibaca
//        $tr->setFilters(array());
//        $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));        //dimatikan dulu untuk debug
//        cekHere($this->db->last_query());

        foreach ($jurnalIds as $jurnalId) {
            $ju->updateData("id = '$jurnalId'", array("r_jenis" => 1)); //dimatikan dulu untuk debug
            cekBiru($this->db->last_query());
        }
        // endregion marking data transaksi yg sudah dibaca

//        cekLime("nett1:: $sumHarga");
//        mati_disini(__LINE__ . __METHOD__);

//        cekOrange("ini datas");
//        arrPrint($datas);
        $cek = 0;
        foreach ($datas as $data) {
            $dbReport = new MdlReport();
            $cek++;
            // $dbReport->trans_start();

            foreach ($itemKoloms as $itemKolom) {
                $$itemKolom = $data[$itemKolom];
//                cekOrange($itemKolom);
//                arrPrint($data[$itemKolom]);
            }


            switch ($transaksi_jenis) {
                case "999":
                case "9984":
                case "9983":
                case "9982":
                case "9912":
                case "9911":
                case "888_1":
                case "8787":
                case "7762":
                case "771":
                case "762":
                case "743":
                case "476":
                case "462":
                case "488":
                case "4449":
                case "2677":
                case "2676":
                case "2675":
                case "2674":
                case "1677":
                case "1119": //kerugian
                case "1675":
                case "1674":
                case "1463":
                case "1462":
                    $newDatas_00 = array(
                        "subject_id" => isset($oleh_id) ? $oleh_id : "",
                        "subject_nama" => isset($oleh_nama) ? $oleh_nama : "",
                        // "object_id"    => $id,
                        // "object_nama"  => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => ($harga + $ongkir_net),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($harga + $ongkir_net),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_01 = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => ($harga + $ongkir_net),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($harga + $ongkir_net),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_02 = array(
                        // "subject_id"   => $id,
                        // "subject_nama" => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => 1,
                        "unit_in" => 0,
                        "unit_af" => 1,
                        // ------------------
                        "nilai_ot" => $debet,
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => $kredit,
                        "nilai_af" => ($debet - $kredit),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas = array(
                        "subject_id" => isset($oleh_id) ? $oleh_id : "",
                        "subject_nama" => isset($oleh_nama) ? $oleh_nama : "",
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $debet,
                        "nilai_in" => $kredit,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => ($debet - $kredit),
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    break;
                default:
                    cekOrange('$transaksi_jenis: ' . $transaksi_jenis);
                    matiHere(__METHOD__ . " @" . __LINE__);
                    break;
            }

            // $newDatas['test'] = 9999;

            $dbReport->setJenis($jenis);
            $dbReport->setOrder("id DESC");
            $dbReport->setLimit("1");

            $dbReport->setDebug(true);
            //region harian
            $periode = "harian";
            $dbReport->setPeriode($periode);
            $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            // $dbReport->setTahun($th);
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
            //region counter
            $tmp_c00 = $dbReport->lastCounterBiayaPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastTanggal_00 = $tCounter_00->tanggal;

                if ($tanggal != $lastTanggal_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastTanggal_00 = "";
            }
            // -------------------------------------
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTanggal = $tCounter->tanggal;

                if ($tanggal != $lastTanggal) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTanggal = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            // cekHitam("$cek *** $periode:: $tanggal != $lastTanggal :: new::$counter last::$lastCounter");

            $counter_hr["counter"] = $counter;
            $counter_hr_00["counter"] = $counter_00;
            // -------------------------------------
            $newDatas_hr = $newDatas + $counter_hr;
            $newDatas_00hr = $newDatas_00 + $counter_hr_00;
            // -------------------------------------
//             arrPrintWebs($newDatas_hr);
//             matiHere(__LINE__);
//            $dbReport->setDataGlundungs($newDatas_00hr);
//            $dbReport->setDataPp($newDatas_01);
            $dbReport->setDataP($newDatas_02);
            $dbReport->setDatas($newDatas_hr);
//            arrPrint($newDatas_hr);
//            matiHere();
//            // -------------------------------------
//            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
//            $dbReport->writePenjualanSeller($oleh_id);
//            $dbReport->writePenjualanProdukCabang($id, $placeID);
//            $dbReport->writePenjualanProduk($id);
            $dbReport->writeBiaya();
//            $dbReport->writePenjualanCabang($placeID);
            // endregion

            // region mingguan
            $periode = "mingguan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            $dbReport->setTahun($th);
            // $dbReport->setOrder("id DESC");
            // $dbReport->setLimit("1");
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];

            //region counter
            $tmp_c00 = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastMg_00 = $tCounter_00->mg;
                $lastTh_00 = $tCounter_00->th;

                if ($th . $mg != $lastTh_00 . $lastMg_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastMg_00 = "";
                $lastTh_00 = "";
            }
            // ----------------------
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastMg = $tCounter->mg;
                $lastTh = $tCounter->th;

                if ($th . $mg != $lastTh . $lastMg) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastMg = "";
                $lastTh = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$mg != $lastTh.$lastMg :: new::$counter last::$lastCounter");

            $counter_mg["counter"] = $counter;
            $counter_mg00["counter"] = $counter_00;
            // ----------------------------------
            $newDatas_mg = $newDatas + $counter_mg;
            $newDatas_mg00 = $newDatas_00 + $counter_mg00;
            //--------------------------------
            $dbReport->setDatas($newDatas_mg);
            $dbReport->setDataGlundungs($newDatas_mg00);

//            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
//            $dbReport->writePenjualanSeller($oleh_id);
//            $dbReport->writePenjualanProdukCabang($id, $placeID);
//            $dbReport->writePenjualanProduk($id);
            $dbReport->writeBiaya();
//            $dbReport->writePenjualanCabang($placeID);
            // endregion mingguan

            // region bulanan
            $periode = "bulanan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            $dbReport->setBulan($bl);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastBl = $tCounter->bl;
                $lastTh = $tCounter->th;

                if ($th . $bl != $lastTh . $lastBl) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastBl = "";
                $lastTh = "";
            }

            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastBl_00 = $tCounter_00->bl;
                $lastTh_00 = $tCounter_00->th;

                if ($th . $bl != $lastTh_00 . $lastBl_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastBl_00 = "";
                $lastTh_00 = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$bl != $lastTh.$lastBl :: new::$counter last::$lastCounter");

            $counter_bl["counter"] = $counter;
            $counter_bl00["counter"] = $counter_00;
            // --------------------------------
            $newDatas_bl = $newDatas + $counter_bl;
            $newDatas_bl00 = $newDatas_00 + $counter_bl00;
            // --------------------------------
            $dbReport->setDatas($newDatas_bl);
            $dbReport->setDataGlundungs($newDatas_bl00);
            // --------------------------------
//            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
//            $dbReport->writePenjualanSeller($oleh_id);
//            $dbReport->writePenjualanProdukCabang($id, $placeID);
//            $dbReport->writePenjualanProduk($id);
            $dbReport->writeBiaya();
//            $dbReport->writePenjualanCabang($placeID);
            // endregion bulanan

            // region tahunan
            $periode = "tahunan";
            $dbReport->setPeriode($periode);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTh = $tCounter->th;

                if ($th != $lastTh) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTh = "";
            }

            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastTh_00 = $tCounter_00->th;

                if ($th != $lastTh_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastTh_00 = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th != $lastTh :: new::$counter last::$lastCounter");

            $counter_th["counter"] = $counter;
            $counter_th00["counter"] = $counter_00;
            $newDatas_th = $newDatas + $counter_th;
            $newDatas_th00 = $newDatas_00 + $counter_th00;
            $dbReport->setDatas($newDatas_th);
            $dbReport->setDataGlundungs($newDatas_th00);

            //=====================================
//            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
//            $dbReport->writePenjualanSeller($oleh_id);
//            $dbReport->writePenjualanProdukCabang($id, $placeID);
//            $dbReport->writePenjualanProduk($id);
            $dbReport->writeBiaya();
//            $dbReport->writePenjualanCabang($placeID);
            // endregion tahunan

            // $dbReport->trans_complete() or matiHere("gagal ocmit");
        }


    }

    public function genBiaya()
    {

        $arrRekeningBiaya = array(
            'biaya usaha',
            'biaya umum',
            'biaya produksi',

//            'biaya gaji',
//            'biaya jasa',
//            'biaya supplies',
//            'biaya operasional',
//            'delivery cost',
//            'direct labor',
//            'quality',
//            'kerugian',
//            'return penjualan',
//            'diskon',
//            'kerugian kurs',
//            'beban lain lain',
//            'biaya kirim',
//            'biaya bunga',
//            'tenaga kerja',
//            'biaya sewa'
        );

        // header("Refresh:2");
//        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        $jenis = "biaya";
        // $jenis = key($reportJenis);
        // cekMerah("$jenis");
        $jenis_list = implode("','", $reportJenis[$jenis]);
        // cekHere($jenis." ".$jenis_list);
        //         matiHere(__METHOD__ ." @". __LINE__);
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComJurnal");
        $this->load->model("Mdls/MdlReport");
        $tr = new MdlTransaksi();
        $ju = new ComJurnal();

        $this->db->limit(1);
//        $this->db->where("jenis in('" . $jenis_list . "')");
        $this->db->where("rekening in(" . "'" . implode("','", $arrRekeningBiaya) . "'" . ")");
        // $ju->addFilter("rekening in('penjualan','return penjualan')");
        $ju->addFilter("transaksi_id!=''");
        $ju->addFilter("r_jenis='0'");
        $ju->setSortBy(array("kolom" => "id", "mode" => "asc"));
        $scrJurnal = $ju->lookupAll()->result();

//        cekHitam('$scrJurnal');
//        cekHitam( sizeof($scrJurnal) );
//        arrPrint($scrJurnal);

        cekMerah($this->db->last_query());
        sizeof($scrJurnal) < 1 ? matiHere(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // sizeof($scrJurnal) < 1 ? cekHitam(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // arrPrint($scrJurnal);
        $jTransaksi_id = $scrJurnal[0]->transaksi_id;


        // $ju->addFilter("rekening='penjualan'");
//        $this->db->limit(1);
//        $this->db->where("rekening in(" . "'" . implode("','", $arrRekeningBiaya) . "'" . ")");
//        $ju->addFilter("transaksi_id='" . $jTransaksi_id . "'");
//        $jurnalPenjualans = $ju->lookupAll()->result();
//        cekHere($this->db->last_query());
//        arrPrint($jurnalPenjualans);
//        if (sizeof($jurnalPenjualans) > 1) {
//            $rekening=array();
//            $debet=0;
//            $kredit=0;
//            foreach($jurnalPenjualans as $ky=>$dataT){
//                $debet+=$dataT->debet;
//                $kredit+=$dataT->kredit;
//            }
//            arrPrint($debet-$kredit);

//            $cekDatas = "trId: $jTransaksi_id";
//            $cekDatas .= "<br>trId: $jTransaksi_id";
//            arrPrint($jurnalPenjualans);
//            matiHere(__METHOD__ . " ada kemungkinan doble jurnal, harap diperikasa $cekDatas");
//        };
        // arrPrint($jurnalPenjualans);

        $jurnalIds[] = $scrJurnal[0]->id;

//arrPrint($jurnalIds);
//         matiHere(__LINE__ . __METHOD__);

        // region membaca 1 data
        // $condite = "status = '1'";
        $tr->setFilters(array());
        $this->db->order_by("id");
        $this->db->limit(1);
        $tr->addFilter("id='" . $jTransaksi_id . "'");
        $tr->addFilter("id_master>'0'");
        // $tr->addFilter("jenis in('".$jenis_list."')");
        // $tr->addFilter("r_jenis='0'");
//        $this->db->where("jenis in('" . $jenis_list . "')");
        // $scr_0 = $tr->lookupByCondition($condite)->result();
        $scr = $tr->lookupAll()->result();
        cekHijau($this->db->last_query());
//arrPrint($scr);
//        matiHere();
        sizeof($scr) == 0 ? matiHere(__LINE__ . __METHOD__ . " source data sudah habis") : "";
        $scr_0 = $scr[0];

        $transaksi_id = $scr_0->id;
        $transaksi_jenis = $scr_0->jenis;
        $id_master = $scr_0->id_master;
        // $oleh_nama = $scr_0->oleh_nama;
        $dtime = $transaksi_dtime = $scr_0->dtime;

        $tanggal = formatTanggal($dtime, 'Y-m-d');
        $tg = formatTanggal($dtime, 'd') * 1;
        $mg = formatTanggal($dtime, 't') * 1;
        $bl = formatTanggal($dtime, 'm') * 1;
        $th = formatTanggal($dtime, 'Y') * 1;


        // region transaksi awal SO
        $scr_1Koloms = array(
            "oleh_nama",
            "oleh_id",
        );

        $tr->setFilters(array());
        $this->db->select($scr_1Koloms);
        $scr_1 = $tr->lookupByCondition("id = '$id_master'")->result()[0];
        cekMerah($this->db->last_query());
        foreach ($scr_1Koloms as $kolom) {
            $$kolom = $scr_1->$kolom;
        }
        // endregion transaksi awal SO

        // endregion membaca 1 data

        // arrPrint($scr);
        // arrPrint($scr_1);
        // mati_disini(__LINE__ . __METHOD__);

        // region membaca data registry
        // main
//        $itemKoloms = array(
//            "id",
//            "nama",
//            "produk_kode",
//            "jml",
//            "harga",
//            "sub_nett1",
//            "sub_ppn",
//            "sub_nett2",
//            "pihakID",
//            "pihakName",
//            "placeID",
//            "placeName",
//            "gudangID",
//            "gudangName",
//            "olehID",
//            "olehName",
//        );
//        $tr->setFilters(array());
//        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
//        $tr->addFilter("param ='main'");
//        $regMains = $tr->lookupRegistries()->result();
//        cekLime($this->db->last_query() . " ::@" . __LINE__);
//        $mainDatas = blobDecode($regMains[0]->values);
        // arrPrintWebs($mainDatas);
//        if ((isset($mainDatas['shippingService'])) && ($mainDatas['shippingService'] == "ongkir_ppn_by_cust")) {
//            $specMains['ongkir_net'] = $mainDatas['ongkir_net'];
//        }
//        else {
//            $specMains['ongkir_net'] = 0;
//        }

        // $datas[] = $specMains;
        // item
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "jml",
            "harga",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
            "ongkir_net",
        );
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
        $tr->addFilter("param ='items'");
        $regs = $tr->lookupRegistries()->result();

//        cekLime($this->db->last_query());
//         arrPrint($regs);
//matiHere();
        $regDatas = array();
        $sumNett1 = 0;
        foreach ($regs as $reg) {
            $trId = $reg->transaksi_id;
            // $refId = $reg->referenceID;
            $regValues = blobDecode($reg->values);
            // cekBiru("trId:: $trId");
            // arrPrint($regValues);
            foreach ($regValues as $regValue) {
                foreach ($itemKoloms as $itemKolom) {
                    $$itemKolom = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;
                    // $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;
                    $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : $specMains[$itemKolom];
                }
                // isset($sumJml) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                isset($sumNett1) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                $datas[] = $specs;
            }
            // $regDatas[$trId]['nett1'] = $regValues['nett1'];
            // $regDatas[$trId]['referenceID'] = isset($regValues['referenceID']) ? $regValues['referenceID'] : 0;
            // arrPrint($regValues);
        }
        // endregion membaca 1 data

        // arrPrint($datas);
        cekLime("nett1:: $sumNett1");
        // mati_disini(__LINE__ . __METHOD__);

//        region marking data transaksi yg sudah dibaca
//        $tr->setFilters(array());
//        $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));        //dimatikan dulu untuk debug
//        cekHere($this->db->last_query());

        foreach ($jurnalIds as $jurnalId) {
            $ju->updateData("id = '$jurnalId'", array("r_jenis" => 1)); //dimatikan dulu untuk debug
            cekBiru($this->db->last_query());
        }
        // endregion marking data transaksi yg sudah dibaca

//        cekHitam($transaksi_jenis);
        arrPrint('$jurnalId');
        arrPrint($jurnalId);
        cekMerah('$scrJurnal');
        arrPrint($scrJurnal);
        cekMerah('$datas LINE:' . __LINE__);
        arrPrint($datas);
//        matiHere();
        $cek = 0;
        foreach ($scrJurnal as $data) {
            $data = (array)$data;
            cekHitam('masuk sini gak sih');
            cekHitam($transaksi_jenis);
            arrPrint($data);

            $dbReport = new MdlReport();
            $cek++;
            // $dbReport->trans_start();

            foreach ($itemKoloms as $itemKolom) {
                $$itemKolom = isset($data[$itemKolom]) ? $data[$itemKolom] : "";
            }
            switch ($transaksi_jenis) {
                case "9912":
                    //Pembatalan transaksi (cabang)
                    //kredit -> quality
                case "9911":
                    //Pembatalan transaksi
                    //kredit -> biaya umum
                case "476":
                    //expense payment
                    //kredit -> biaya produksi
                case "1462":
                    //account payable payment
                    //kredit -> biaya import
//                    $newDatas_00 = array(
//                        "subject_id"   => $oleh_id,
//                        "subject_nama" => $oleh_nama,
//                        // ------------------
//                        "unit_ot"      => 0,
//                        "unit_in"      => $jml,
//                        "unit_af"      => $jml * -1,
//                        // ------------------
//                        "nilai_ot"     => 0,
//                        "nilai_in"     => ($harga + $ongkir_net),
//                        "nilai_af"     => ($harga + $ongkir_net) * -1,
//                        // ------------------
//                        "cabang_id"    => $placeID,
//                        "cabang_nama"  => $placeName,
//                        "dtime"        => $dtime,
//                        "tanggal"      => $tanggal,
//                        "tg"           => $tg,
//                        "mg"           => $mg,
//                        "bl"           => $bl,
//                        "th"           => $th,
//                        // "counter"   => $counter,
//                    );
//                    $newDatas_01 = array(
//                        "subject_id"   => $id,
//                        "subject_nama" => $nama,
//                        "object_kode"  => $produk_kode,
//                        // ------------------
//                        "unit_ot"      => 0,
//                        "unit_in"      => $jml,
//                        "unit_af"      => $jml * -1,
//                        // ------------------
//                        "nilai_ot"     => 0,
//                        "nilai_in"     => ($harga + $ongkir_net),
//                        "nilai_af"     => ($harga + $ongkir_net) * -1,
//                        // ------------------
//                        // "cabang_id"    => $placeID,
//                        // "cabang_nama"  => $placeName,
//                        "dtime"        => $dtime,
//                        "tanggal"      => $tanggal,
//                        "tg"           => $tg,
//                        "mg"           => $mg,
//                        "bl"           => $bl,
//                        "th"           => $th,
//                        // "counter"   => $counter,
//                    );
//                    $newDatas_02 = array(
//                        // "subject_id"   => $id,
//                        // "subject_nama" => $nama,
//                        // "object_kode"  => $produk_kode,
//                        // ------------------
//                        "unit_ot"  => 0,
//                        "unit_in"  => 1,
//                        "unit_af"  => 1 * -1,
//                        // ------------------
//                        "nilai_ot" => 0,
//                        "nilai_in" => ($harga + $ongkir_net),
//                        "nilai_af" => ($harga + $ongkir_net) * -1,
//                        // ------------------
//                        // "cabang_id"    => $placeID,
//                        // "cabang_nama"  => $placeName,
//                        "dtime"    => $dtime,
//                        "tanggal"  => $tanggal,
//                        "tg"       => $tg,
//                        "mg"       => $mg,
//                        "bl"       => $bl,
//                        "th"       => $th,
//                        // "counter"   => $counter,
//                    );
//                    $newDatas = array(
//                        "subject_id"   => $oleh_id,
//                        "subject_nama" => $oleh_nama,
//                        "object_id"    => $id,
//                        "object_nama"  => $nama,
//                        "object_kode"  => $produk_kode,
//
//                        "unit_ot" => 0,
//                        "unit_in" => $jml,
//                        "unit_af" => $jml * -1,
//
//                        "nilai_ot" => 0,
//                        "nilai_in" => $harga,
//                        "nilai_af" => $harga * -1,
//
//                        "cabang_id"   => $placeID,
//                        "cabang_nama" => $placeName,
//                        "dtime"       => $dtime,
//                        "tanggal"     => $tanggal,
//                        "tg"          => $tg,
//                        "mg"          => $mg,
//                        "bl"          => $bl,
//                        "th"          => $th,
//                        // "counter"   => $counter,
//                    );
//                    break;
                case "888_1":
                    //penyesuaian non produk (tambah)
                case "9984":
                    //transfer expense to production expense
                    //debet -> biaya produksi
                case "9983":
                    //transfer expense to general expense
                    //debet -> biaya umum
                case "9982":
                    //transfer expense to marketing expense
                    //debet -> biaya usaha
                case "8787":
                    //authorization depresiasi
                    //debet -> biaya umum
                    //debet -> biaya usaha
                    //debet -> quality
                case "7762":
                    //approval pembiayaan supplies
                    //debet -> biaya umum
                case "771":
                    //refill pettycash
                    //debet -> biaya usaha
                case "762":
                    //approval pembiayaan supplies
                    //debet -> biaya usaha
                case "743":
                    //biaya lain-lain
                    //debet -> beban lain lain
                case "462":
                    //account payable payment
                    //debet -> biaya usaha
                case "488":
                    //prepaid payment
                    //debet -> kerugian kurs
                case "489":
                    //prepaid payment
                    //debet -> kerugian kurs
                case "4410":
                    //Biaya Bunga A/P Payment
                    //debet -> biaya bunga
                case "2677":
                    //authorization biaya usaha
                    //debet -> biaya usaha
                case "2676":
                    //authorization biaya produksi
                    //debet -> biaya produksi
                case "2675":
                    //authorization biaya umum
                    //debet -> biaya umum
                case "2674":
                    //salary expense authorization (PUSAT)
                    //debet -> biaya gaji
                case "1677":
                    //authorization biaya usaha
                    //debet -> biaya usaha
                case "1119":
                    //STOCK OPNAME AUTHORIZATION 2
                    //debet -> kerugian
                case "1675":
                    //authorization biaya umum
                    //debet -> biaya umum
                case "1674":
                    //salary expense authorization (CABANG)
                    //debet -> biaya gaji
                case "1463":
                    //SERVICE RECEIVED NOTE
                    //debet -> biaya import
                    $kredit = isset($scrJurnal[0]->kredit) ? $scrJurnal[0]->kredit : 0;
                    $debet = isset($scrJurnal[0]->debet) ? $scrJurnal[0]->debet : 0;
                    $newDatas_00 = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        // "object_id"    => $id,
                        // "object_nama"  => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => $debet,
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => $kredit,
                        "nilai_af" => ($debet - $kredit),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_01 = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => $debet,
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => $kredit,
                        "nilai_af" => ($debet - $kredit),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_02 = array(
                        // "subject_id"   => $id,
                        // "subject_nama" => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => 1,
                        "unit_in" => 0,
                        "unit_af" => 1,
                        // ------------------
                        "nilai_ot" => $debet,
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => $kredit,
                        "nilai_af" => ($debet - $kredit),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => $debet,
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => $kredit,
                        "nilai_af" => ($debet - $kredit),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    break;
                default:
                    cekOrange('$transaksi_jenis: ' . $transaksi_jenis);
                    matiHere(__METHOD__ . " @" . __LINE__);
                    break;
                case "999": //adjustment journaling
                    matiHere();
                    break;
            }

            cekMerah('$newDatas');
            arrPrint($newDatas);

//            matiHere();
            // $newDatas['test'] = 9999;

            $dbReport->setJenis($jenis);
            $dbReport->setOrder("id DESC");
            $dbReport->setLimit("1");

            $dbReport->setDebug(true);
            //region harian
            $periode = "harian";
            $dbReport->setPeriode($periode);
            $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            // $dbReport->setTahun($th);
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
            //region counter
//            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
//            if (sizeof($tmp_c00) > 0) {
//                $tCounter_00 = $tmp_c00[0];
//                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
//                $lastTanggal_00 = $tCounter_00->tanggal;
//
//                if ($tanggal != $lastTanggal_00) {
//                    $counter_00 = $lastCounter_00 + 1;
//                }
//                else {
//                    $counter_00 = $lastCounter_00;
//                }
//            }
//            else {
//                $counter_00 = 1;
//                $lastCounter_00 = 0;
//                $lastTanggal_00 = "";
//            }
            // -------------------------------------
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTanggal = $tCounter->tanggal;

                if ($tanggal != $lastTanggal) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTanggal = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            // cekHitam("$cek *** $periode:: $tanggal != $lastTanggal :: new::$counter last::$lastCounter");

            $counter_hr["counter"] = $counter;
//            $counter_hr_00["counter"] = $counter_00;
            // -------------------------------------
            $newDatas_hr = $newDatas + $counter_hr;
//            $newDatas_00hr = $newDatas_00 + $counter_hr_00;
            // -------------------------------------
            cekLime($periode);
//             arrPrintWebs($newDatas_01);
//             arrPrintWebs($newDatas_02);
//             arrPrintWebs($newDatas_hr);
//             matiHere(__LINE__);
//            $dbReport->setDataGlundungs($newDatas_00hr);
            $dbReport->setDataPp($newDatas_01);
            $dbReport->setDataP($newDatas_02);
            $dbReport->setDatas($newDatas_hr);
            // -------------------------------------
//            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
//            $dbReport->writePenjualanSeller($oleh_id);
//            $dbReport->writePenjualanProdukCabang($id, $placeID);
//            $dbReport->writePenjualanProduk($id);
            $dbReport->writeBiaya();
//            $dbReport->writePenjualanCabang($placeID);
            // endregion
//             matiHere("done " . __LINE__);
            // region mingguan
            $periode = "mingguan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            $dbReport->setTahun($th);
            // $dbReport->setOrder("id DESC");
            // $dbReport->setLimit("1");
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];

            //region counter
            $tmp_c00 = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastMg_00 = $tCounter_00->mg;
                $lastTh_00 = $tCounter_00->th;

                if ($th . $mg != $lastTh_00 . $lastMg_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastMg_00 = "";
                $lastTh_00 = "";
            }
            // ----------------------
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastMg = $tCounter->mg;
                $lastTh = $tCounter->th;

                if ($th . $mg != $lastTh . $lastMg) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastMg = "";
                $lastTh = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$mg != $lastTh.$lastMg :: new::$counter last::$lastCounter");

            $counter_mg["counter"] = $counter;
            $counter_mg00["counter"] = $counter_00;
            // ----------------------------------
            $newDatas_mg = $newDatas + $counter_mg;
            $newDatas_mg00 = $newDatas_00 + $counter_mg00;
            //--------------------------------
            $dbReport->setDatas($newDatas_mg);
            $dbReport->setDataGlundungs($newDatas_mg00);

//            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
//            $dbReport->writePenjualanSeller($oleh_id);
//            $dbReport->writePenjualanProdukCabang($id, $placeID);
//            $dbReport->writePenjualanProduk($id);
            $dbReport->writeBiaya();
//            $dbReport->writePenjualanCabang($placeID);
            // endregion mingguan

            // region bulanan
            $periode = "bulanan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            $dbReport->setBulan($bl);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
//            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastBl = $tCounter->bl;
                $lastTh = $tCounter->th;

                if ($th . $bl != $lastTh . $lastBl) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastBl = "";
                $lastTh = "";
            }

//            if (sizeof($tmp_c00) > 0) {
//                $tCounter_00 = $tmp_c00[0];
//                // matiHere(__LINE__);
//                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
//                $lastBl_00 = $tCounter_00->bl;
//                $lastTh_00 = $tCounter_00->th;
//
//                if ($th . $bl != $lastTh_00 . $lastBl_00) {
//                    $counter_00 = $lastCounter_00 + 1;
//                }
//                else {
//                    $counter_00 = $lastCounter_00;
//                }
//            }
//            else {
//                $counter_00 = 1;
//                $lastCounter_00 = 0;
//                $lastBl_00 = "";
//                $lastTh_00 = "";
//            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$bl != $lastTh.$lastBl :: new::$counter last::$lastCounter");

            $counter_bl["counter"] = $counter;
//            $counter_bl00["counter"] = $counter_00;
            // --------------------------------
            $newDatas_bl = $newDatas + $counter_bl;
//            $newDatas_bl00 = $newDatas_00 + $counter_bl00;
            // --------------------------------
            $dbReport->setDatas($newDatas_bl);
//            $dbReport->setDataGlundungs($newDatas_bl00);
            // --------------------------------
//            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
//            $dbReport->writePenjualanSeller($oleh_id);
//            $dbReport->writePenjualanProdukCabang($id, $placeID);
//            $dbReport->writePenjualanProduk($id);
            $dbReport->writeBiaya();
//            $dbReport->writePenjualanCabang($placeID);
            // endregion bulanan

            // region tahunan
            $periode = "tahunan";
            $dbReport->setPeriode($periode);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTh = $tCounter->th;

                if ($th != $lastTh) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTh = "";
            }

//            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
//            if (sizeof($tmp_c00) > 0) {
//                $tCounter_00 = $tmp_c00[0];
//                // matiHere(__LINE__);
//                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
//                $lastTh_00 = $tCounter_00->th;
//
//                if ($th != $lastTh_00) {
//                    $counter_00 = $lastCounter_00 + 1;
//                }
//                else {
//                    $counter_00 = $lastCounter_00;
//                }
//            }
//            else {
//                $counter_00 = 1;
//                $lastCounter_00 = 0;
//                $lastTh_00 = "";
//            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th != $lastTh :: new::$counter last::$lastCounter");

            $counter_th["counter"] = $counter;
//            $counter_th00["counter"] = $counter_00;
            $newDatas_th = $newDatas + $counter_th;
//            $newDatas_th00 = $newDatas_00 + $counter_th00;
            $dbReport->setDatas($newDatas_th);
//            $dbReport->setDataGlundungs($newDatas_th00);

            //=====================================
//            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
//            $dbReport->writePenjualanSeller($oleh_id);
//            $dbReport->writePenjualanProdukCabang($id, $placeID);
//            $dbReport->writePenjualanProduk($id);
            $dbReport->writeBiaya();
//            $dbReport->writePenjualanCabang($placeID);
            // endregion tahunan

            // $dbReport->trans_complete() or matiHere("gagal ocmit");
        }

    }

    public function genPrePenjualan()
    {

        // header("Refresh:2");
        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        // $jenis = "582spd";
        // $jenis = key($reportJenis);
        $jenieses = array(
            "pre_penjualan",
            // "pembelian_produk",
        );
        // $jenis = "pembelian_produk";
        foreach ($jenieses as $jenis) {

            cekMerah("$jenis");

            $jenis_list = implode("','", $reportJenis[$jenis]);
            // cekHere($jenis." ".$jenis_list);
            //         matiHere(__METHOD__ ." @". __LINE__);
            $this->load->model("MdlTransaksi");
            $this->load->model("Mdls/MdlReport");
            $tr = new MdlTransaksi();

            // region membaca 1 data
            // $condite = "status = '1'";
            $tr->setFilters(array());
            $this->db->order_by("id");
            $this->db->limit(1);
            $tr->addFilter("id_master>'0'");
            // $tr->addFilter("jenis in('".$jenis_list."')");
            $tr->addFilter("r_jenis='0'");
            $this->db->where("jenis in('" . $jenis_list . "')");
            // $scr_0 = $tr->lookupByCondition($condite)->result();
            $scr = $tr->lookupAll()->result();
            cekHijau($this->db->last_query());
            sizeof($scr) < 1 ? matiHere(__METHOD__ . " source data sudah habis") : "";
            $scr_0 = $scr[0];
            $transaksi_id = $scr_0->id;
            $transaksi_jenis = $scr_0->jenis;
            $id_master = $scr_0->id_master;
            // $oleh_nama = $scr_0->oleh_nama;
            $dtime = $transaksi_dtime = $scr_0->dtime;

            $tanggal = formatTanggal($dtime, 'Y-m-d');
            $tg = formatTanggal($dtime, 'd') * 1;
            $mg = formatTanggal($dtime, 't') * 1;
            $bl = formatTanggal($dtime, 'm') * 1;
            $th = formatTanggal($dtime, 'Y') * 1;


            // region transaksi awal SO
            $scr_1Koloms = array(
                "oleh_nama",
                "oleh_id",
            );
            $tr->setFilters(array());
            $this->db->select($scr_1Koloms);
            $scr_1 = $tr->lookupByCondition("id = '$id_master'")->result()[0];
            cekMerah($this->db->last_query());
            foreach ($scr_1Koloms as $kolom) {
                $$kolom = $scr_1->$kolom;
            }
            // endregion transaksi awal SO

            // endregion membaca 1 data


            // arrPrint($scr);
            // arrPrint($scr_1);
            // mati_disini(__LINE__);

            // region membaca data registry
            $itemKoloms = array(
                "id",
                "nama",

                "sub_harga",

                "id",
                "nama",
                "produk_kode",
                "jml",
                "sub_nett1",
                "sub_ppn",
                "sub_nett2",
                "pihakID",
                "pihakName",
                "placeID",
                "placeName",
                "gudangID",
                "gudangName",
                "olehID",
                "olehName",
            );
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
            $tr->addFilter("param ='items'");
            $regs = $tr->lookupRegistries()->result();
            cekLime($this->db->last_query());
            // arrPrint($regs);
            $regDatas = array();
            $sumNett1 = 0;
            foreach ($regs as $reg) {
                $trId = $reg->transaksi_id;
                // $refId = $reg->referenceID;
                $regValues = blobDecode($reg->values);
                // cekBiru("trId:: $trId");
                // arrPrint($regValues);
                foreach ($regValues as $regValue) {
                    foreach ($itemKoloms as $itemKolom) {
                        $$itemKolom = $regValue[$itemKolom];

                        $specs[$itemKolom] = $regValue[$itemKolom];
                    }
                    // isset($sumJml) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                    isset($sumNett1) ? $sumNett1 += $sub_harga : $sumNett1 = 0;
                    $datas[] = $specs;

                }
                // $regDatas[$trId]['nett1'] = $regValues['nett1'];
                // $regDatas[$trId]['referenceID'] = isset($regValues['referenceID']) ? $regValues['referenceID'] : 0;
                // arrPrint($regValues);
            }
            // endregion membaca 1 data

            // arrPrint($datas);
            cekLime("nett1:: $sumNett1");
            // mati_disini(__LINE__);

            // region marking data transaksi yg sudah dibaca
            $tr->setFilters(array());
            $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));
            cekHere($this->db->last_query());
            // endregion marking data transaksi yg sudah dibaca

            $cek = 0;
            foreach ($datas as $data) {
                $dbReport = new MdlReport();
                $cek++;
                // $dbReport->trans_start();

                foreach ($itemKoloms as $itemKolom) {
                    $$itemKolom = $data[$itemKolom];
                }
                switch ($transaksi_jenis) {

                    case "582spo":
                    case "582so":
                    case "582spd":
                        $newDatas = array(
                            "subject_id" => $oleh_id,
                            "subject_nama" => $oleh_nama,
                            "object_id" => $id,
                            "object_nama" => $nama,
                            "object_kode" => isset($produk_kode) ? $produk_kode : "-",

                            "unit_ot" => $jml,
                            "unit_in" => 0,
                            "unit_af" => $jml,

                            "nilai_ot" => $sub_nett1,
                            "nilai_in" => 0,
                            "nilai_af" => $sub_nett1,

                            "cabang_id" => $placeID,
                            "cabang_nama" => $placeName,
                            "dtime" => $dtime,
                            "tanggal" => $tanggal,
                            "tg" => $tg,
                            "mg" => $mg,
                            "bl" => $bl,
                            "th" => $th,
                            // "counter"   => $counter,
                        );
                        break;
                    case "982":
                        $newDatas = array(
                            "subject_id" => $oleh_id,
                            "subject_nama" => $oleh_nama,
                            "object_id" => $id,
                            "object_nama" => $nama,
                            "object_kode" => $produk_kode,

                            "unit_ot" => 0,
                            "unit_in" => $jml,
                            "unit_af" => $jml * -1,

                            "nilai_ot" => 0,
                            "nilai_in" => $sub_nett1,
                            "nilai_af" => $sub_nett1 * -1,

                            "cabang_id" => $placeID,
                            "cabang_nama" => $placeName,
                            "dtime" => $dtime,
                            "tanggal" => $tanggal,
                            "tg" => $tg,
                            "mg" => $mg,
                            "bl" => $bl,
                            "th" => $th,
                            // "counter"   => $counter,
                        );
                        break;
                    case "461":
                        $newDatas = array(
                            "subject_id" => $pihakID,
                            "subject_nama" => $pihakName,
                            "object_id" => $id,
                            "object_nama" => $nama,
                            // "object_kode"  => $produk_kode,

                            "unit_ot" => $jml,
                            "unit_in" => 0,
                            "unit_af" => $jml,

                            "nilai_ot" => $sub_harga,
                            "nilai_in" => 0,
                            "nilai_af" => $sub_harga,

                            "cabang_id" => $placeID,
                            "cabang_nama" => $placeName,
                            "dtime" => $dtime,
                            "tanggal" => $tanggal,
                            "tg" => $tg,
                            "mg" => $mg,
                            "bl" => $bl,
                            "th" => $th,
                            // "counter"   => $counter,
                        );
                        break;
                    case "961":
                        $newDatas = array(
                            "subject_id" => $pihakID,
                            "subject_nama" => $pihakName,
                            "object_id" => $id,
                            "object_nama" => $nama,
                            // "object_kode"  => $produk_kode,

                            "unit_ot" => 0,
                            "unit_in" => $jml,
                            "unit_af" => $jml * -1,

                            "nilai_ot" => 0,
                            "nilai_in" => $sub_harga,
                            "nilai_af" => $sub_harga * -1,

                            "cabang_id" => $placeID,
                            "cabang_nama" => $placeName,
                            "dtime" => $dtime,
                            "tanggal" => $tanggal,
                            "tg" => $tg,
                            "mg" => $mg,
                            "bl" => $bl,
                            "th" => $th,
                            // "counter"   => $counter,
                        );
                        break;
                    case "467":
                        $newDatas = array(
                            "subject_id" => $pihakID,
                            "subject_nama" => $pihakName,
                            "object_id" => $id,
                            "object_nama" => $nama,
                            // "object_kode"  => $produk_kode,

                            "unit_ot" => $jml,
                            "unit_in" => 0,
                            "unit_af" => $jml,

                            "nilai_ot" => $sub_harga,
                            "nilai_in" => 0,
                            "nilai_af" => $sub_harga,

                            "cabang_id" => $placeID,
                            "cabang_nama" => $placeName,
                            "dtime" => $dtime,
                            "tanggal" => $tanggal,
                            "tg" => $tg,
                            "mg" => $mg,
                            "bl" => $bl,
                            "th" => $th,
                            // "counter"   => $counter,
                        );
                        break;
                    case "967":
                        $newDatas = array(
                            "subject_id" => $pihakID,
                            "subject_nama" => $pihakName,
                            "object_id" => $id,
                            "object_nama" => $nama,
                            // "object_kode"  => $produk_kode,

                            "unit_ot" => 0,
                            "unit_in" => $jml,
                            "unit_af" => $jml * -1,

                            "nilai_ot" => 0,
                            "nilai_in" => $sub_harga,
                            "nilai_af" => $sub_harga * -1,

                            "cabang_id" => $placeID,
                            "cabang_nama" => $placeName,
                            "dtime" => $dtime,
                            "tanggal" => $tanggal,
                            "tg" => $tg,
                            "mg" => $mg,
                            "bl" => $bl,
                            "th" => $th,
                            // "counter"   => $counter,
                        );
                        break;
                    default:
                        matiHere($transaksi_jenis . " " . __METHOD__ . " @" . __LINE__);
                        break;
                }

                // arrPrint($newDatas);
                $dbReport->setDebug(true);
                $dbReport->setJenis($jenis);
                $dbReport->setOrder("id DESC");
                $dbReport->setLimit("1");

                //region harian
                $periode = "harian";
                $dbReport->setPeriode($periode);
                $dbReport->setTanggal($tanggal);
                // $dbReport->setMinggu($mg);
                // $dbReport->setBulan($bl);
                // $dbReport->setTahun($th);
                // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
                //region counter
                $tmp_c = $dbReport->lastCounterPeriode()->result();
                if (sizeof($tmp_c) > 0) {
                    $tCounter = $tmp_c[0];
                    $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                    $lastTanggal = $tCounter->tanggal;

                    if ($tanggal != $lastTanggal) {
                        $counter = $lastCounter + 1;
                    }
                    else {
                        $counter = $lastCounter;
                    }
                }
                else {
                    $counter = 1;
                    $lastCounter = 0;
                    $lastTanggal = "";
                }
                //endregion
                // arrPrintWebs($tmp_c);
                cekHitam("$cek *** $periode:: $tanggal != $lastTanggal :: new::$counter last::$lastCounter");

                $counter_hr["counter"] = $counter;
                $newDatas_hr = $newDatas + $counter_hr;
                $dbReport->setDatas($newDatas_hr);
                $dbReport->writePenjualanSellerProduk($olehID, $id);
                // endregion
                // mati_disini(__METHOD__);
                // region mingguan
                $periode = "mingguan";
                // $dbReport->setDebug(true);
                // $dbReport->setJenis($jenis);
                $dbReport->setPeriode($periode);
                // $dbReport->setTanggal($tanggal);
                $dbReport->setMinggu($mg);
                // $dbReport->setBulan($bl);
                $dbReport->setTahun($th);
                // $dbReport->setOrder("id DESC");
                // $dbReport->setLimit("1");
                // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
                //region counter
                $tmp_c = $dbReport->lastCounterPeriode()->result();
                if (sizeof($tmp_c) > 0) {
                    $tCounter = $tmp_c[0];
                    // matiHere(__LINE__);
                    $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                    $lastMg = $tCounter->mg;
                    $lastTh = $tCounter->th;

                    if ($th . $mg != $lastTh . $lastMg) {
                        $counter = $lastCounter + 1;
                    }
                    else {
                        $counter = $lastCounter;
                    }
                }
                else {
                    $counter = 1;
                    $lastCounter = 0;
                    $lastMg = "";
                    $lastTh = "";
                }
                //endregion
                // arrPrintWebs($tmp_c);
                cekHitam("$periode:: $th.$mg != $lastTh.$lastMg :: new::$counter last::$lastCounter");

                $counter_mg["counter"] = $counter;
                $newDatas_mg = $newDatas + $counter_mg;
                $dbReport->setDatas($newDatas_mg);
                $dbReport->writePenjualanSellerProduk($olehID, $id);
                // endregion mingguan

                // region bulanan
                $periode = "bulanan";
                // $dbReport->setDebug(true);
                // $dbReport->setJenis($jenis);
                $dbReport->setPeriode($periode);
                // $dbReport->setTanggal($tanggal);
                $dbReport->setMinggu($bl);
                // $dbReport->setBulan($bl);
                $dbReport->setTahun($th);

                //region counter
                $tmp_c = $dbReport->lastCounterPeriode()->result();
                if (sizeof($tmp_c) > 0) {
                    $tCounter = $tmp_c[0];
                    // matiHere(__LINE__);
                    $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                    $lastBl = $tCounter->bl;
                    $lastTh = $tCounter->th;

                    if ($th . $bl != $lastTh . $lastBl) {
                        $counter = $lastCounter + 1;
                    }
                    else {
                        $counter = $lastCounter;
                    }
                }
                else {
                    $counter = 1;
                    $lastCounter = 0;
                    $lastBl = "";
                    $lastTh = "";
                }
                //endregion
                // arrPrintWebs($tmp_c);
                cekHitam("$periode:: $th.$bl != $lastTh.$lastBl :: new::$counter last::$lastCounter");

                $counter_bl["counter"] = $counter;
                $newDatas_bl = $newDatas + $counter_bl;
                $dbReport->setDatas($newDatas_bl);
                $dbReport->writePenjualanSellerProduk($olehID, $id);
                // endregion bulanan

                // region tahunan
                $periode = "tahunan";
                $dbReport->setPeriode($periode);
                $dbReport->setTahun($th);

                //region counter
                $tmp_c = $dbReport->lastCounterPeriode()->result();
                if (sizeof($tmp_c) > 0) {
                    $tCounter = $tmp_c[0];
                    // matiHere(__LINE__);
                    $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                    $lastTh = $tCounter->th;

                    if ($th != $lastTh) {
                        $counter = $lastCounter + 1;
                    }
                    else {
                        $counter = $lastCounter;
                    }
                }
                else {
                    $counter = 1;
                    $lastCounter = 0;
                    $lastTh = "";
                }
                //endregion
                // arrPrintWebs($tmp_c);
                cekHitam("$periode:: $th != $lastTh :: new::$counter last::$lastCounter");

                $counter_th["counter"] = $counter;
                $newDatas_th = $newDatas + $counter_th;
                $dbReport->setDatas($newDatas_th);
                $dbReport->writePenjualanSellerProduk($olehID, $id);
                // endregion tahunan

                // $dbReport->trans_complete() or matiHere("gagal ocmit");
            }

        }
    }

    public function genPrePenjualanCanceled()
    {

        // header("Refresh:2");
        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        // $jenis = "582spd";
        // $jenis = key($reportJenis);
        $jenieses = array(
            "pre_penjualan_canceled",
            // "pembelian_produk",
        );
        // $jenis = "pembelian_produk";
        foreach ($jenieses as $jenis) {

            cekMerah("$jenis");

            $jenis_list = implode("','", $reportJenis[$jenis]);
            // cekHere($jenis." ".$jenis_list);
            //         matiHere(__METHOD__ ." @". __LINE__);
            $this->load->model("MdlTransaksi");
            $this->load->model("Mdls/MdlReport");
            $tr = new MdlTransaksi();

            // region membaca 1 data dari transaksi sign
            // $condite = "status = '1'";
            $tr->setFilters(array());
            $this->db->order_by("id");
            $this->db->limit(1);
            // $tr->addFilter("step_number<'0'");
            // $tr->addFilter("jenis in('".$jenis_list."')");
            $this->db->where("r_jenis='0'");
            $this->db->where("step_code in('" . $jenis_list . "')");
            // $scr_0 = $tr->lookupByCondition($condite)->result();
            $scr = $tr->lookupCanceledSo()->result();
            cekHijau($this->db->last_query());
            sizeof($scr) < 1 ? matiHere(__METHOD__ . " source data sudah habis") : "";
            $scr_0 = $scr[0];
            $sign_id = $scr_0->id;
            // $transaksi_jenis = $scr_0->jenis;
            $transaksi_id = $scr_0->transaksi_id;
            // $oleh_nama = $scr_0->oleh_nama;
            $dtime = $transaksi_dtime = $scr_0->dtime;


            $tanggal = formatTanggal($dtime, 'Y-m-d');
            $tg = formatTanggal($dtime, 'd') * 1;
            $mg = formatTanggal($dtime, 't') * 1;
            $bl = formatTanggal($dtime, 'm') * 1;
            $th = formatTanggal($dtime, 'Y') * 1;

            // mati_disini($tanggal . " $transaksi_id");
            // ==================================

            // $condite = "status = '1'";
            $tr->setFilters(array());
            $this->db->order_by("id");
            $this->db->limit(1);
            $tr->addFilter("id='" . $transaksi_id . "'");
            // $tr->addFilter("jenis in('".$jenis_list."')");
            // $tr->addFilter("r_jenis='0'");
            // $this->db->where("jenis in('" . $jenis_list . "')");
            // $scr_0 = $tr->lookupByCondition($condite)->result();
            $scr = $tr->lookupAll()->result();
            cekLime($this->db->last_query());
            sizeof($scr) < 1 ? matiHere(__METHOD__ . " source data sudah habis") : "";
            $scr_0 = $scr[0];
            // $transaksi_id = $scr_0->id;
            $transaksi_jenis = $scr_0->jenis;
            $id_master = $scr_0->id_master;
            // $oleh_nama = $scr_0->oleh_nama;

            //======================================
            // region transaksi awal SO
            $scr_1Koloms = array(
                "oleh_nama",
                "oleh_id",
            );
            $tr->setFilters(array());
            $this->db->select($scr_1Koloms);
            $scr_1 = $tr->lookupByCondition("id = '$id_master'")->result()[0];
            cekMerah($this->db->last_query());
            foreach ($scr_1Koloms as $kolom) {
                $$kolom = $scr_1->$kolom;
            }
            // endregion transaksi awal SO

            // endregion membaca 1 data


            // arrPrint($scr);
            // arrPrint($scr_1);
            // mati_disini(__LINE__);

            // region membaca data registry
            $itemKoloms = array(
                "id",
                "nama",

                "sub_harga",

                "id",
                "nama",
                "produk_kode",
                "jml",
                "sub_nett1",
                "sub_ppn",
                "sub_nett2",
                "pihakID",
                "pihakName",
                "placeID",
                "placeName",
                "gudangID",
                "gudangName",
                "olehID",
                "olehName",
            );
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
            $tr->addFilter("param ='items'");
            $regs = $tr->lookupRegistries()->result();
            cekLime($this->db->last_query());
            // arrPrint($regs);
            $regDatas = array();
            $sumNett1 = 0;
            foreach ($regs as $reg) {
                $trId = $reg->transaksi_id;
                // $refId = $reg->referenceID;
                $regValues = blobDecode($reg->values);
                // cekBiru("trId:: $trId");
                // arrPrint($regValues);
                foreach ($regValues as $regValue) {
                    foreach ($itemKoloms as $itemKolom) {
                        $$itemKolom = $regValue[$itemKolom];

                        $specs[$itemKolom] = $regValue[$itemKolom];
                    }
                    // isset($sumJml) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                    isset($sumNett1) ? $sumNett1 += $sub_harga : $sumNett1 = 0;
                    $datas[] = $specs;

                }
                // $regDatas[$trId]['nett1'] = $regValues['nett1'];
                // $regDatas[$trId]['referenceID'] = isset($regValues['referenceID']) ? $regValues['referenceID'] : 0;
                // arrPrint($regValues);
            }
            // endregion membaca 1 data

            // arrPrint($datas);
            cekLime("nett1:: $sumNett1");
            // mati_disini(__LINE__);

            // region marking data transaksi yg sudah dibaca
            $upd_datas = array(
                "r_jenis" => "1",
            );
            $tr->markingCanceledSo($sign_id, $upd_datas);
            cekHere($this->db->last_query());
            // endregion marking data transaksi yg sudah dibaca
            // mati_disini();
            $cek = 0;
            foreach ($datas as $data) {
                $dbReport = new MdlReport();
                $cek++;
                // $dbReport->trans_start();

                foreach ($itemKoloms as $itemKolom) {
                    $$itemKolom = $data[$itemKolom];
                }
                switch ($transaksi_jenis) {

                    case "582spo":
                    case "582so":
                    case "582spd":
                        $newDatas = array(
                            "subject_id" => $oleh_id,
                            "subject_nama" => $oleh_nama,
                            "object_id" => $id,
                            "object_nama" => $nama,
                            "object_kode" => isset($produk_kode) ? $produk_kode : "-",

                            "unit_ot" => $jml,
                            "unit_in" => 0,
                            "unit_af" => $jml,

                            "nilai_ot" => $sub_nett1,
                            "nilai_in" => 0,
                            "nilai_af" => $sub_nett1,

                            "cabang_id" => $placeID,
                            "cabang_nama" => $placeName,
                            "dtime" => $dtime,
                            "tanggal" => $tanggal,
                            "tg" => $tg,
                            "mg" => $mg,
                            "bl" => $bl,
                            "th" => $th,
                            // "counter"   => $counter,
                        );
                        break;
                    case "982":
                        $newDatas = array(
                            "subject_id" => $oleh_id,
                            "subject_nama" => $oleh_nama,
                            "object_id" => $id,
                            "object_nama" => $nama,
                            "object_kode" => $produk_kode,

                            "unit_ot" => 0,
                            "unit_in" => $jml,
                            "unit_af" => $jml * -1,

                            "nilai_ot" => 0,
                            "nilai_in" => $sub_nett1,
                            "nilai_af" => $sub_nett1 * -1,

                            "cabang_id" => $placeID,
                            "cabang_nama" => $placeName,
                            "dtime" => $dtime,
                            "tanggal" => $tanggal,
                            "tg" => $tg,
                            "mg" => $mg,
                            "bl" => $bl,
                            "th" => $th,
                            // "counter"   => $counter,
                        );
                        break;
                    case "461":
                        $newDatas = array(
                            "subject_id" => $pihakID,
                            "subject_nama" => $pihakName,
                            "object_id" => $id,
                            "object_nama" => $nama,
                            // "object_kode"  => $produk_kode,

                            "unit_ot" => $jml,
                            "unit_in" => 0,
                            "unit_af" => $jml,

                            "nilai_ot" => $sub_harga,
                            "nilai_in" => 0,
                            "nilai_af" => $sub_harga,

                            "cabang_id" => $placeID,
                            "cabang_nama" => $placeName,
                            "dtime" => $dtime,
                            "tanggal" => $tanggal,
                            "tg" => $tg,
                            "mg" => $mg,
                            "bl" => $bl,
                            "th" => $th,
                            // "counter"   => $counter,
                        );
                        break;
                    case "961":
                        $newDatas = array(
                            "subject_id" => $pihakID,
                            "subject_nama" => $pihakName,
                            "object_id" => $id,
                            "object_nama" => $nama,
                            // "object_kode"  => $produk_kode,

                            "unit_ot" => 0,
                            "unit_in" => $jml,
                            "unit_af" => $jml * -1,

                            "nilai_ot" => 0,
                            "nilai_in" => $sub_harga,
                            "nilai_af" => $sub_harga * -1,

                            "cabang_id" => $placeID,
                            "cabang_nama" => $placeName,
                            "dtime" => $dtime,
                            "tanggal" => $tanggal,
                            "tg" => $tg,
                            "mg" => $mg,
                            "bl" => $bl,
                            "th" => $th,
                            // "counter"   => $counter,
                        );
                        break;
                    case "467":
                        $newDatas = array(
                            "subject_id" => $pihakID,
                            "subject_nama" => $pihakName,
                            "object_id" => $id,
                            "object_nama" => $nama,
                            // "object_kode"  => $produk_kode,

                            "unit_ot" => $jml,
                            "unit_in" => 0,
                            "unit_af" => $jml,

                            "nilai_ot" => $sub_harga,
                            "nilai_in" => 0,
                            "nilai_af" => $sub_harga,

                            "cabang_id" => $placeID,
                            "cabang_nama" => $placeName,
                            "dtime" => $dtime,
                            "tanggal" => $tanggal,
                            "tg" => $tg,
                            "mg" => $mg,
                            "bl" => $bl,
                            "th" => $th,
                            // "counter"   => $counter,
                        );
                        break;
                    case "967":
                        $newDatas = array(
                            "subject_id" => $pihakID,
                            "subject_nama" => $pihakName,
                            "object_id" => $id,
                            "object_nama" => $nama,
                            // "object_kode"  => $produk_kode,

                            "unit_ot" => 0,
                            "unit_in" => $jml,
                            "unit_af" => $jml * -1,

                            "nilai_ot" => 0,
                            "nilai_in" => $sub_harga,
                            "nilai_af" => $sub_harga * -1,

                            "cabang_id" => $placeID,
                            "cabang_nama" => $placeName,
                            "dtime" => $dtime,
                            "tanggal" => $tanggal,
                            "tg" => $tg,
                            "mg" => $mg,
                            "bl" => $bl,
                            "th" => $th,
                            // "counter"   => $counter,
                        );
                        break;
                    default:
                        matiHere($transaksi_jenis . " " . __METHOD__ . " @" . __LINE__);
                        break;
                }

                // arrPrint($newDatas);
                $dbReport->setDebug(true);
                $dbReport->setJenis($jenis);
                $dbReport->setOrder("id DESC");
                $dbReport->setLimit("1");

                //region harian
                $periode = "harian";
                $dbReport->setPeriode($periode);
                $dbReport->setTanggal($tanggal);
                // $dbReport->setMinggu($mg);
                // $dbReport->setBulan($bl);
                // $dbReport->setTahun($th);
                // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
                //region counter
                $tmp_c = $dbReport->lastCounterPeriode()->result();
                if (sizeof($tmp_c) > 0) {
                    $tCounter = $tmp_c[0];
                    $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                    $lastTanggal = $tCounter->tanggal;

                    if ($tanggal != $lastTanggal) {
                        $counter = $lastCounter + 1;
                    }
                    else {
                        $counter = $lastCounter;
                    }
                }
                else {
                    $counter = 1;
                    $lastCounter = 0;
                    $lastTanggal = "";
                }
                //endregion
                // arrPrintWebs($tmp_c);
                cekHitam("$cek *** $periode:: $tanggal != $lastTanggal :: new::$counter last::$lastCounter");

                $counter_hr["counter"] = $counter;
                $newDatas_hr = $newDatas + $counter_hr;
                $dbReport->setDatas($newDatas_hr);
                $dbReport->writePenjualanSellerProduk($olehID, $id);
                // endregion

                // region mingguan
                $periode = "mingguan";
                // $dbReport->setDebug(true);
                // $dbReport->setJenis($jenis);
                $dbReport->setPeriode($periode);
                // $dbReport->setTanggal($tanggal);
                $dbReport->setMinggu($mg);
                // $dbReport->setBulan($bl);
                $dbReport->setTahun($th);
                // $dbReport->setOrder("id DESC");
                // $dbReport->setLimit("1");
                // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
                //region counter
                $tmp_c = $dbReport->lastCounterPeriode()->result();
                if (sizeof($tmp_c) > 0) {
                    $tCounter = $tmp_c[0];
                    // matiHere(__LINE__);
                    $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                    $lastMg = $tCounter->mg;
                    $lastTh = $tCounter->th;

                    if ($th . $mg != $lastTh . $lastMg) {
                        $counter = $lastCounter + 1;
                    }
                    else {
                        $counter = $lastCounter;
                    }
                }
                else {
                    $counter = 1;
                    $lastCounter = 0;
                    $lastMg = "";
                    $lastTh = "";
                }
                //endregion
                // arrPrintWebs($tmp_c);
                cekHitam("$periode:: $th.$mg != $lastTh.$lastMg :: new::$counter last::$lastCounter");

                $counter_mg["counter"] = $counter;
                $newDatas_mg = $newDatas + $counter_mg;
                $dbReport->setDatas($newDatas_mg);
                $dbReport->writePenjualanSellerProduk($olehID, $id);
                // endregion mingguan

                // region bulanan
                $periode = "bulanan";
                // $dbReport->setDebug(true);
                // $dbReport->setJenis($jenis);
                $dbReport->setPeriode($periode);
                // $dbReport->setTanggal($tanggal);
                $dbReport->setMinggu($bl);
                // $dbReport->setBulan($bl);
                $dbReport->setTahun($th);

                //region counter
                $tmp_c = $dbReport->lastCounterPeriode()->result();
                if (sizeof($tmp_c) > 0) {
                    $tCounter = $tmp_c[0];
                    // matiHere(__LINE__);
                    $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                    $lastBl = $tCounter->bl;
                    $lastTh = $tCounter->th;

                    if ($th . $bl != $lastTh . $lastBl) {
                        $counter = $lastCounter + 1;
                    }
                    else {
                        $counter = $lastCounter;
                    }
                }
                else {
                    $counter = 1;
                    $lastCounter = 0;
                    $lastBl = "";
                    $lastTh = "";
                }
                //endregion
                // arrPrintWebs($tmp_c);
                cekHitam("$periode:: $th.$bl != $lastTh.$lastBl :: new::$counter last::$lastCounter");

                $counter_bl["counter"] = $counter;
                $newDatas_bl = $newDatas + $counter_bl;
                $dbReport->setDatas($newDatas_bl);
                $dbReport->writePenjualanSellerProduk($olehID, $id);
                // endregion bulanan

                // region tahunan
                $periode = "tahunan";
                $dbReport->setPeriode($periode);
                $dbReport->setTahun($th);

                //region counter
                $tmp_c = $dbReport->lastCounterPeriode()->result();
                if (sizeof($tmp_c) > 0) {
                    $tCounter = $tmp_c[0];
                    // matiHere(__LINE__);
                    $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                    $lastTh = $tCounter->th;

                    if ($th != $lastTh) {
                        $counter = $lastCounter + 1;
                    }
                    else {
                        $counter = $lastCounter;
                    }
                }
                else {
                    $counter = 1;
                    $lastCounter = 0;
                    $lastTh = "";
                }
                //endregion
                // arrPrintWebs($tmp_c);
                cekHitam("$periode:: $th != $lastTh :: new::$counter last::$lastCounter");

                $counter_th["counter"] = $counter;
                $newDatas_th = $newDatas + $counter_th;
                $dbReport->setDatas($newDatas_th);
                $dbReport->writePenjualanSellerProduk($olehID, $id);
                // endregion tahunan

                // $dbReport->trans_complete() or matiHere("gagal ocmit");
            }

        }
    }

    public function genPenjualan12_5_2020()
    {

        // header("Refresh:2");
        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        $jenis = "penjualan";
        // $jenis = key($reportJenis);
        // cekMerah("$jenis");
        $jenis_list = implode("','", $reportJenis[$jenis]);
        // cekHere($jenis." ".$jenis_list);
        //         matiHere(__METHOD__ ." @". __LINE__);
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComJurnal");
        $this->load->model("Mdls/MdlReport");
        $tr = new MdlTransaksi();
        $ju = new ComJurnal();

        $this->db->limit(1);
        $this->db->where("jenis in('" . $jenis_list . "')");
        $this->db->where("rekening in('penjualan','return penjualan')");
        // $ju->addFilter("rekening in('penjualan','return penjualan')");
        $ju->addFilter("r_jenis='0'");
        $ju->setSortBy(array("kolom" => "id", "mode" => "asc"));
        $scrJurnal = $ju->lookupAll()->result();
        cekMerah($this->db->last_query());
        sizeof($scrJurnal) < 1 ? matiHere(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // sizeof($scrJurnal) < 1 ? cekHitam(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // arrPrint($scrJurnal);
        $jTransaksi_id = $scrJurnal[0]->transaksi_id;


        // $ju->addFilter("rekening='penjualan'");
        $this->db->where("rekening in('penjualan','return penjualan')");
        $ju->addFilter("transaksi_id='" . $jTransaksi_id . "'");
        $jurnalPenjualans = $ju->lookupAll()->result();
        cekHere($this->db->last_query());
        if (sizeof($jurnalPenjualans) > 1) {
            $cekDatas = "trId: $jTransaksi_id";
            $cekDatas .= "<br>trId: $jTransaksi_id";
            matiHere(__METHOD__ . " ada kemungkinan doble jurnal, harap diperikasa $cekDatas");
        };
        // arrPrint($jurnalPenjualans);
        $jurnalIds[] = $jurnalPenjualans[0]->id;


        // matiHere(__LINE__ . __METHOD__);

        // region membaca 1 data
        // $condite = "status = '1'";
        $tr->setFilters(array());
        $this->db->order_by("id");
        $this->db->limit(1);
        $tr->addFilter("id='" . $jTransaksi_id . "'");
        $tr->addFilter("id_master>'0'");
        // $tr->addFilter("jenis in('".$jenis_list."')");
        // $tr->addFilter("r_jenis='0'");
        $this->db->where("jenis in('" . $jenis_list . "')");
        // $scr_0 = $tr->lookupByCondition($condite)->result();
        $scr = $tr->lookupAll()->result();
        cekHijau($this->db->last_query());

        sizeof($scr) == 0 ? matiHere(__LINE__ . __METHOD__ . " source data sudah habis") : "";
        $scr_0 = $scr[0];

        $transaksi_id = $scr_0->id;
        $transaksi_jenis = $scr_0->jenis;
        $id_master = $scr_0->id_master;
        // $oleh_nama = $scr_0->oleh_nama;
        $dtime = $transaksi_dtime = $scr_0->dtime;

        $tanggal = formatTanggal($dtime, 'Y-m-d');
        $tg = formatTanggal($dtime, 'd') * 1;
        $mg = formatTanggal($dtime, 't') * 1;
        $bl = formatTanggal($dtime, 'm') * 1;
        $th = formatTanggal($dtime, 'Y') * 1;


        // region transaksi awal SO
        $scr_1Koloms = array(
            "oleh_nama",
            "oleh_id",
        );
        $tr->setFilters(array());
        $this->db->select($scr_1Koloms);
        $scr_1 = $tr->lookupByCondition("id = '$id_master'")->result()[0];
        cekMerah($this->db->last_query());
        foreach ($scr_1Koloms as $kolom) {
            $$kolom = $scr_1->$kolom;
        }
        // endregion transaksi awal SO

        // endregion membaca 1 data


        // arrPrint($scr);
        // arrPrint($scr_1);
        // mati_disini(__LINE__ . __METHOD__);

        // region membaca data registry
        // main
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "jml",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
        );
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
        $tr->addFilter("param ='main'");
        $regMains = $tr->lookupRegistries()->result();
        cekLime($this->db->last_query() . " ::@" . __LINE__);
        $mainDatas = blobDecode($regMains[0]->values);
        // arrPrintWebs($mainDatas);
        if ((isset($mainDatas['shippingService'])) && ($mainDatas['shippingService'] == "ongkir_ppn_by_cust")) {
            $specMains['ongkir_net'] = $mainDatas['ongkir_net'];
        }
        else {
            $specMains['ongkir_net'] = 0;
        }

        // $datas[] = $specMains;
        // item
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "jml",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
            "ongkir_net",
        );
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
        $tr->addFilter("param ='items'");
        $regs = $tr->lookupRegistries()->result();
        cekLime($this->db->last_query());
        // arrPrint($regs);
        $regDatas = array();
        $sumNett1 = 0;
        foreach ($regs as $reg) {
            $trId = $reg->transaksi_id;
            // $refId = $reg->referenceID;
            $regValues = blobDecode($reg->values);
            // cekBiru("trId:: $trId");
            // arrPrint($regValues);
            foreach ($regValues as $regValue) {
                foreach ($itemKoloms as $itemKolom) {
                    $$itemKolom = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;

                    // $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;
                    $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : $specMains[$itemKolom];
                }
                // isset($sumJml) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                isset($sumNett1) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                $datas[] = $specs;

            }
            // $regDatas[$trId]['nett1'] = $regValues['nett1'];
            // $regDatas[$trId]['referenceID'] = isset($regValues['referenceID']) ? $regValues['referenceID'] : 0;
            // arrPrint($regValues);
        }
        // endregion membaca 1 data

        // arrPrint($datas);
        cekLime("nett1:: $sumNett1");
        // mati_disini(__LINE__ . __METHOD__);

        // region marking data transaksi yg sudah dibaca
        $tr->setFilters(array());
        $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));
        cekHere($this->db->last_query());

        foreach ($jurnalIds as $jurnalId) {

            $ju->updateData("id = '$jurnalId'", array("r_jenis" => 1));
            cekBiru($this->db->last_query());
        }
        // endregion marking data transaksi yg sudah dibaca

        $cek = 0;
        foreach ($datas as $data) {
            $dbReport = new MdlReport();
            $cek++;
            // $dbReport->trans_start();

            foreach ($itemKoloms as $itemKolom) {
                $$itemKolom = $data[$itemKolom];
            }
            switch ($transaksi_jenis) {
                case "382spd":
                case "582spd":
                    $newDatas_00 = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        // "object_id"    => $id,
                        // "object_nama"  => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
//                        "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => ($sub_nett1),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_nett1),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_01 = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => ($sub_nett1),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_nett1),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_02 = array(
                        // "subject_id"   => $id,
                        // "subject_nama" => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => 1,
                        "unit_in" => 0,
                        "unit_af" => 1,
                        // ------------------
                        "nilai_ot" => ($sub_nett1),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_nett1),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    break;
                case "982":
                    $newDatas_00 = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => ($sub_nett1),
                        "nilai_af" => ($sub_nett1) * -1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_01 = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => ($sub_nett1),
                        "nilai_af" => ($sub_nett1) * -1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_02 = array(
                        // "subject_id"   => $id,
                        // "subject_nama" => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => 1,
                        "unit_af" => 1 * -1,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => ($sub_nett1),
                        "nilai_af" => ($sub_nett1) * -1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    break;
                default:
                    matiHere(__METHOD__ . " @" . __LINE__);
                    break;
            }

            // $newDatas['test'] = 9999;

            $dbReport->setJenis($jenis);
            $dbReport->setOrder("id DESC");
            $dbReport->setLimit("1");

            $dbReport->setDebug(true);
            //region harian
            $periode = "harian";
            $dbReport->setPeriode($periode);
            $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            // $dbReport->setTahun($th);
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
            //region counter
            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastTanggal_00 = $tCounter_00->tanggal;

                if ($tanggal != $lastTanggal_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastTanggal_00 = "";
            }
            // -------------------------------------
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTanggal = $tCounter->tanggal;

                if ($tanggal != $lastTanggal) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTanggal = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            // cekHitam("$cek *** $periode:: $tanggal != $lastTanggal :: new::$counter last::$lastCounter");

            $counter_hr["counter"] = $counter;
            $counter_hr_00["counter"] = $counter_00;
            // -------------------------------------
            $newDatas_hr = $newDatas + $counter_hr;
            $newDatas_00hr = $newDatas_00 + $counter_hr_00;
            // -------------------------------------
            // arrPrintWebs($newDatas_hr);
            // matiHere(__LINE__);
            $dbReport->setDataGlundungs($newDatas_00hr);
            $dbReport->setDataPp($newDatas_01);
            $dbReport->setDataP($newDatas_02);
            $dbReport->setDatas($newDatas_hr);
            // -------------------------------------
            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
            $dbReport->writePenjualanSeller($oleh_id);
            $dbReport->writePenjualanProdukCabang($id, $placeID);
            $dbReport->writePenjualanProduk($id);
            $dbReport->writePenjualan();
            $dbReport->writePenjualanCabang($placeID);
            // endregion
            // matiHere("done " . __LINE__);
            // region mingguan
            $periode = "mingguan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            $dbReport->setTahun($th);
            // $dbReport->setOrder("id DESC");
            // $dbReport->setLimit("1");
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];

            //region counter
            $tmp_c00 = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastMg_00 = $tCounter_00->mg;
                $lastTh_00 = $tCounter_00->th;

                if ($th . $mg != $lastTh_00 . $lastMg_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastMg_00 = "";
                $lastTh_00 = "";
            }
            // ----------------------
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastMg = $tCounter->mg;
                $lastTh = $tCounter->th;

                if ($th . $mg != $lastTh . $lastMg) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastMg = "";
                $lastTh = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$mg != $lastTh.$lastMg :: new::$counter last::$lastCounter");

            $counter_mg["counter"] = $counter;
            $counter_mg00["counter"] = $counter_00;
            // ----------------------------------
            $newDatas_mg = $newDatas + $counter_mg;
            $newDatas_mg00 = $newDatas_00 + $counter_mg00;
            //--------------------------------
            $dbReport->setDatas($newDatas_mg);
            $dbReport->setDataGlundungs($newDatas_mg00);

            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
            $dbReport->writePenjualanSeller($oleh_id);
            $dbReport->writePenjualanProdukCabang($id, $placeID);
            $dbReport->writePenjualanProduk($id);
            $dbReport->writePenjualan();
            $dbReport->writePenjualanCabang($placeID);
            // endregion mingguan

            // region bulanan
            $periode = "bulanan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            $dbReport->setBulan($bl);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastBl = $tCounter->bl;
                $lastTh = $tCounter->th;

                if ($th . $bl != $lastTh . $lastBl) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastBl = "";
                $lastTh = "";
            }

            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastBl_00 = $tCounter_00->bl;
                $lastTh_00 = $tCounter_00->th;

                if ($th . $bl != $lastTh_00 . $lastBl_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastBl_00 = "";
                $lastTh_00 = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$bl != $lastTh.$lastBl :: new::$counter last::$lastCounter");

            $counter_bl["counter"] = $counter;
            $counter_bl00["counter"] = $counter_00;
            // --------------------------------
            $newDatas_bl = $newDatas + $counter_bl;
            $newDatas_bl00 = $newDatas_00 + $counter_bl00;
            // --------------------------------
            $dbReport->setDatas($newDatas_bl);
            $dbReport->setDataGlundungs($newDatas_bl00);
            // --------------------------------
            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
            $dbReport->writePenjualanSeller($oleh_id);
            $dbReport->writePenjualanProdukCabang($id, $placeID);
            $dbReport->writePenjualanProduk($id);
            $dbReport->writePenjualan();
            $dbReport->writePenjualanCabang($placeID);
            // endregion bulanan

            // region tahunan
            $periode = "tahunan";
            $dbReport->setPeriode($periode);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTh = $tCounter->th;

                if ($th != $lastTh) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTh = "";
            }

            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastTh_00 = $tCounter_00->th;

                if ($th != $lastTh_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastTh_00 = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th != $lastTh :: new::$counter last::$lastCounter");

            $counter_th["counter"] = $counter;
            $counter_th00["counter"] = $counter_00;
            $newDatas_th = $newDatas + $counter_th;
            $newDatas_th00 = $newDatas_00 + $counter_th00;
            $dbReport->setDatas($newDatas_th);
            $dbReport->setDataGlundungs($newDatas_th00);
            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
            $dbReport->writePenjualanSeller($oleh_id);
            $dbReport->writePenjualanProdukCabang($id, $placeID);
            $dbReport->writePenjualanProduk($id);
            $dbReport->writePenjualan();
            $dbReport->writePenjualanCabang($placeID);
            // endregion tahunan

            // $dbReport->trans_complete() or matiHere("gagal ocmit");
        }


    }

    public function genPenjualanOLD()
    {

        // header("Refresh:2");
        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        $jenis = "penjualan";
        // $jenis = key($reportJenis);
        // cekMerah("$jenis");
        $jenis_list = implode("','", $reportJenis[$jenis]);
        // cekHere($jenis." ".$jenis_list);
        //         matiHere(__METHOD__ ." @". __LINE__);
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComJurnal");
        $this->load->model("Mdls/MdlReport");
        $tr = new MdlTransaksi();
        $ju = new ComJurnal();

        $this->db->limit(1);
        $this->db->where("jenis in('" . $jenis_list . "')");
        $this->db->where("rekening in('penjualan','return penjualan')");
        // $ju->addFilter("rekening in('penjualan','return penjualan')");
        $ju->addFilter("r_jenis='0'");
        $ju->setSortBy(array("kolom" => "id", "mode" => "asc"));
        $scrJurnal = $ju->lookupAll()->result();
        cekMerah($this->db->last_query());
        sizeof($scrJurnal) < 1 ? matiHere(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // sizeof($scrJurnal) < 1 ? cekHitam(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // arrPrint($scrJurnal);
        $jTransaksi_id = $scrJurnal[0]->transaksi_id;


        // $ju->addFilter("rekening='penjualan'");
        $this->db->where("rekening in('penjualan','return penjualan')");
        $ju->addFilter("transaksi_id='" . $jTransaksi_id . "'");
        $jurnalPenjualans = $ju->lookupAll()->result();
        cekHere($this->db->last_query());
        if (sizeof($jurnalPenjualans) > 1) {
            $cekDatas = "trId: $jTransaksi_id";
            $cekDatas .= "<br>trId: $jTransaksi_id";
            matiHere(__METHOD__ . " ada kemungkinan doble jurnal, harap diperikasa $cekDatas");
        };
        // arrPrint($jurnalPenjualans);
        $jurnalIds[] = $jurnalPenjualans[0]->id;


        // matiHere(__LINE__ . __METHOD__);

        // region membaca 1 data
        // $condite = "status = '1'";
        $tr->setFilters(array());
        $this->db->order_by("id");
        $this->db->limit(1);
        $tr->addFilter("id='" . $jTransaksi_id . "'");
        $tr->addFilter("id_master>'0'");
        // $tr->addFilter("jenis in('".$jenis_list."')");
        // $tr->addFilter("r_jenis='0'");
        $this->db->where("jenis in('" . $jenis_list . "')");
        // $scr_0 = $tr->lookupByCondition($condite)->result();
        $scr = $tr->lookupAll()->result();
        cekHijau($this->db->last_query());

        sizeof($scr) == 0 ? matiHere(__LINE__ . __METHOD__ . " source data sudah habis") : "";
        $scr_0 = $scr[0];

        $transaksi_id = $scr_0->id;
        $transaksi_jenis = $scr_0->jenis;
        $id_master = $scr_0->id_master;
        // $oleh_nama = $scr_0->oleh_nama;
        $dtime = $transaksi_dtime = $scr_0->dtime;

        $tanggal = formatTanggal($dtime, 'Y-m-d');
        $tg = formatTanggal($dtime, 'd') * 1;
        $mg = formatTanggal($dtime, 't') * 1;
        $bl = formatTanggal($dtime, 'm') * 1;
        $th = formatTanggal($dtime, 'Y') * 1;


        // region transaksi awal SO
        $scr_1Koloms = array(
            "oleh_nama",
            "oleh_id",
        );
        $tr->setFilters(array());
        $this->db->select($scr_1Koloms);
        $scr_1 = $tr->lookupByCondition("id = '$id_master'")->result()[0];
        cekMerah($this->db->last_query());
        foreach ($scr_1Koloms as $kolom) {
            $$kolom = $scr_1->$kolom;
        }
        // endregion transaksi awal SO

        // endregion membaca 1 data


        // arrPrint($scr);
        // arrPrint($scr_1);
        // mati_disini(__LINE__ . __METHOD__);

        // region membaca data registry
        // main
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "jml",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
        );
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
        $tr->addFilter("param ='main'");
        $regMains = $tr->lookupRegistries()->result();
        cekLime($this->db->last_query() . " ::@" . __LINE__);
        $mainDatas = blobDecode($regMains[0]->values);
        // arrPrintWebs($mainDatas);
        if ((isset($mainDatas['shippingService'])) && ($mainDatas['shippingService'] == "ongkir_ppn_by_cust")) {
            $specMains['ongkir_net'] = $mainDatas['ongkir_net'];
        }
        else {
            $specMains['ongkir_net'] = 0;
        }

        // $datas[] = $specMains;
        // item
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "jml",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
            "ongkir_net",
        );
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
        $tr->addFilter("param ='items'");
        $regs = $tr->lookupRegistries()->result();
        cekLime($this->db->last_query());
        // arrPrint($regs);
        $regDatas = array();
        $sumNett1 = 0;
        foreach ($regs as $reg) {
            $trId = $reg->transaksi_id;
            // $refId = $reg->referenceID;
            $regValues = blobDecode($reg->values);
            // cekBiru("trId:: $trId");
            // arrPrint($regValues);
            foreach ($regValues as $regValue) {
                foreach ($itemKoloms as $itemKolom) {
                    $$itemKolom = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;

                    // $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;
                    $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : $specMains[$itemKolom];
                }
                // isset($sumJml) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                isset($sumNett1) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                $datas[] = $specs;

            }
            // $regDatas[$trId]['nett1'] = $regValues['nett1'];
            // $regDatas[$trId]['referenceID'] = isset($regValues['referenceID']) ? $regValues['referenceID'] : 0;
            // arrPrint($regValues);
        }
        // endregion membaca 1 data

        // arrPrint($datas);
        cekLime("nett1:: $sumNett1");
        // mati_disini(__LINE__ . __METHOD__);

        // region marking data transaksi yg sudah dibaca
        $tr->setFilters(array());
        $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));
        cekHere($this->db->last_query());

        foreach ($jurnalIds as $jurnalId) {

            $ju->updateData("id = '$jurnalId'", array("r_jenis" => 1));
            cekBiru($this->db->last_query());
        }
        // endregion marking data transaksi yg sudah dibaca

        $cek = 0;
        foreach ($datas as $data) {
            $dbReport = new MdlReport();
            $cek++;
            // $dbReport->trans_start();

            foreach ($itemKoloms as $itemKolom) {
                $$itemKolom = $data[$itemKolom];
            }
            switch ($transaksi_jenis) {
                case "382spd":
                case "582spd":
                    $newDatas_00 = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        // "object_id"    => $id,
                        // "object_nama"  => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
//                        "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => ($sub_nett1),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_nett1),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_01 = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => ($sub_nett1),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_nett1),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_02 = array(
                        // "subject_id"   => $id,
                        // "subject_nama" => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => 1,
                        "unit_in" => 0,
                        "unit_af" => 1,
                        // ------------------
                        "nilai_ot" => ($sub_nett1),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_nett1),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    break;
                case "982":
                    $newDatas_00 = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => ($sub_nett1),
                        "nilai_af" => ($sub_nett1) * -1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_01 = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => ($sub_nett1),
                        "nilai_af" => ($sub_nett1) * -1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_02 = array(
                        // "subject_id"   => $id,
                        // "subject_nama" => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => 1,
                        "unit_af" => 1 * -1,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => ($sub_nett1),
                        "nilai_af" => ($sub_nett1) * -1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    break;
                default:
                    matiHere(__METHOD__ . " @" . __LINE__);
                    break;
            }

            // $newDatas['test'] = 9999;

            $dbReport->setJenis($jenis);
            $dbReport->setOrder("id DESC");
            $dbReport->setLimit("1");

            $dbReport->setDebug(true);
            //region harian
            $periode = "harian";
            $dbReport->setPeriode($periode);
            $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            // $dbReport->setTahun($th);
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
            //region counter
            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastTanggal_00 = $tCounter_00->tanggal;

                if ($tanggal != $lastTanggal_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastTanggal_00 = "";
            }
            // -------------------------------------
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTanggal = $tCounter->tanggal;

                if ($tanggal != $lastTanggal) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTanggal = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            // cekHitam("$cek *** $periode:: $tanggal != $lastTanggal :: new::$counter last::$lastCounter");

            $counter_hr["counter"] = $counter;
            $counter_hr_00["counter"] = $counter_00;
            // -------------------------------------
            $newDatas_hr = $newDatas + $counter_hr;
            $newDatas_00hr = $newDatas_00 + $counter_hr_00;
            // -------------------------------------
            // arrPrintWebs($newDatas_hr);
            // matiHere(__LINE__);
            $dbReport->setDataGlundungs($newDatas_00hr);
            $dbReport->setDataPp($newDatas_01);
            $dbReport->setDataP($newDatas_02);
            $dbReport->setDatas($newDatas_hr);
            // -------------------------------------
            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
            $dbReport->writePenjualanSeller($oleh_id);
            $dbReport->writePenjualanProdukCabang($id, $placeID);
            $dbReport->writePenjualanProduk($id);
            $dbReport->writePenjualan();
            $dbReport->writePenjualanCabang($placeID);
            // endregion
            // matiHere("done " . __LINE__);
            // region mingguan
            $periode = "mingguan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            $dbReport->setTahun($th);
            // $dbReport->setOrder("id DESC");
            // $dbReport->setLimit("1");
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];

            //region counter
            $tmp_c00 = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastMg_00 = $tCounter_00->mg;
                $lastTh_00 = $tCounter_00->th;

                if ($th . $mg != $lastTh_00 . $lastMg_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastMg_00 = "";
                $lastTh_00 = "";
            }
            // ----------------------
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastMg = $tCounter->mg;
                $lastTh = $tCounter->th;

                if ($th . $mg != $lastTh . $lastMg) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastMg = "";
                $lastTh = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$mg != $lastTh.$lastMg :: new::$counter last::$lastCounter");

            $counter_mg["counter"] = $counter;
            $counter_mg00["counter"] = $counter_00;
            // ----------------------------------
            $newDatas_mg = $newDatas + $counter_mg;
            $newDatas_mg00 = $newDatas_00 + $counter_mg00;
            //--------------------------------
            $dbReport->setDatas($newDatas_mg);
            $dbReport->setDataGlundungs($newDatas_mg00);

            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
            $dbReport->writePenjualanSeller($oleh_id);
            $dbReport->writePenjualanProdukCabang($id, $placeID);
            $dbReport->writePenjualanProduk($id);
            $dbReport->writePenjualan();
            $dbReport->writePenjualanCabang($placeID);
            // endregion mingguan

            // region bulanan
            $periode = "bulanan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            $dbReport->setBulan($bl);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastBl = $tCounter->bl;
                $lastTh = $tCounter->th;

                if ($th . $bl != $lastTh . $lastBl) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastBl = "";
                $lastTh = "";
            }

            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastBl_00 = $tCounter_00->bl;
                $lastTh_00 = $tCounter_00->th;

                if ($th . $bl != $lastTh_00 . $lastBl_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastBl_00 = "";
                $lastTh_00 = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$bl != $lastTh.$lastBl :: new::$counter last::$lastCounter");

            $counter_bl["counter"] = $counter;
            $counter_bl00["counter"] = $counter_00;
            // --------------------------------
            $newDatas_bl = $newDatas + $counter_bl;
            $newDatas_bl00 = $newDatas_00 + $counter_bl00;
            // --------------------------------
            $dbReport->setDatas($newDatas_bl);
            $dbReport->setDataGlundungs($newDatas_bl00);
            // --------------------------------
            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
            $dbReport->writePenjualanSeller($oleh_id);
            $dbReport->writePenjualanProdukCabang($id, $placeID);
            $dbReport->writePenjualanProduk($id);
            $dbReport->writePenjualan();
            $dbReport->writePenjualanCabang($placeID);
            // endregion bulanan

            // region tahunan
            $periode = "tahunan";
            $dbReport->setPeriode($periode);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTh = $tCounter->th;

                if ($th != $lastTh) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTh = "";
            }

            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastTh_00 = $tCounter_00->th;

                if ($th != $lastTh_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastTh_00 = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th != $lastTh :: new::$counter last::$lastCounter");

            $counter_th["counter"] = $counter;
            $counter_th00["counter"] = $counter_00;
            $newDatas_th = $newDatas + $counter_th;
            $newDatas_th00 = $newDatas_00 + $counter_th00;
            $dbReport->setDatas($newDatas_th);
            $dbReport->setDataGlundungs($newDatas_th00);
            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
            $dbReport->writePenjualanSeller($oleh_id);
            $dbReport->writePenjualanProdukCabang($id, $placeID);
            $dbReport->writePenjualanProduk($id);
            $dbReport->writePenjualan();
            $dbReport->writePenjualanCabang($placeID);
            // endregion tahunan

            // $dbReport->trans_complete() or matiHere("gagal ocmit");
        }


    }

    public function genHppOld()
    {

        // header("Refresh:2");
        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        $jenis = "hpp";
        // $jenis = key($reportJenis);
        // cekMerah("$jenis");
        $jenis_list = implode("','", $reportJenis[$jenis]);
        // cekHere($jenis." ".$jenis_list);
        //         matiHere(__METHOD__ ." @". __LINE__);
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComJurnal");
        $this->load->model("Mdls/MdlReport");
        $tr = new MdlTransaksi();
        $ju = new ComJurnal();

        $this->db->limit(1);
        $this->db->where("jenis in('" . $jenis_list . "')");
        $this->db->where("rekening in('hpp')");
        // $ju->addFilter("rekening in('penjualan','return penjualan')");
        $ju->addFilter("r_jenis='0'");
        $ju->setSortBy(array("kolom" => "id", "mode" => "asc"));
        $scrJurnal = $ju->lookupAll()->result();

//        arrPrint($scrJurnal);

        cekMerah($this->db->last_query());
        sizeof($scrJurnal) < 1 ? matiHere(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // sizeof($scrJurnal) < 1 ? cekHitam(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // arrPrint($scrJurnal);
        $jTransaksi_id = $scrJurnal[0]->transaksi_id;


        // $ju->addFilter("rekening='penjualan'");
        $this->db->where("rekening in('hpp')");
        $ju->addFilter("transaksi_id='" . $jTransaksi_id . "'");
        $jurnalHpp = $ju->lookupAll()->result();
        cekHere($this->db->last_query());
//        arrPrint($jurnalHpp);
        if (sizeof($jurnalHpp) > 1) {
            $cekDatas = "trId: $jTransaksi_id";
            $cekDatas .= "<br>trId: $jTransaksi_id";
            matiHere(__METHOD__ . " ada kemungkinan doble jurnal, harap diperikasa $cekDatas");
        };
        // arrPrint($jurnalHpp);
        $jurnalIds[] = $jurnalHpp[0]->id;


        // matiHere(__LINE__ . __METHOD__);

        // region membaca 1 data
        // $condite = "status = '1'";
        $tr->setFilters(array());
        $this->db->order_by("id");
        $this->db->limit(1);
        $tr->addFilter("id='" . $jTransaksi_id . "'");
        $tr->addFilter("id_master>'0'");
        // $tr->addFilter("jenis in('".$jenis_list."')");
        // $tr->addFilter("r_jenis='0'");
        $this->db->where("jenis in('" . $jenis_list . "')");
        // $scr_0 = $tr->lookupByCondition($condite)->result();
        $scr = $tr->lookupAll()->result();
        cekHijau($this->db->last_query());

        sizeof($scr) == 0 ? matiHere(__LINE__ . __METHOD__ . " source data sudah habis") : "";
        $scr_0 = $scr[0];

        $transaksi_id = $scr_0->id;
        $transaksi_jenis = $scr_0->jenis;
        $id_master = $scr_0->id_master;
        // $oleh_nama = $scr_0->oleh_nama;
        $dtime = $transaksi_dtime = $scr_0->dtime;

        $tanggal = formatTanggal($dtime, 'Y-m-d');
        $tg = formatTanggal($dtime, 'd') * 1;
        $mg = formatTanggal($dtime, 't') * 1;
        $bl = formatTanggal($dtime, 'm') * 1;
        $th = formatTanggal($dtime, 'Y') * 1;


        // region transaksi awal SO
        $scr_1Koloms = array(
            "oleh_nama",
            "oleh_id",
        );
        $tr->setFilters(array());
        $this->db->select($scr_1Koloms);
        $scr_1 = $tr->lookupByCondition("id = '$id_master'")->result()[0];
        cekMerah($this->db->last_query());
        foreach ($scr_1Koloms as $kolom) {
            $$kolom = $scr_1->$kolom;
        }
        // endregion transaksi awal SO

        // endregion membaca 1 data


        // arrPrint($scr);
        // arrPrint($scr_1);
        // mati_disini(__LINE__ . __METHOD__);

        // region membaca data registry
        // main
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "jml",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
        );
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
        $tr->addFilter("param ='main'");
        $regMains = $tr->lookupRegistries()->result();
        cekLime($this->db->last_query() . " ::@" . __LINE__);
        $mainDatas = blobDecode($regMains[0]->values);
        // arrPrintWebs($mainDatas);
//        if ((isset($mainDatas['shippingService'])) && ($mainDatas['shippingService'] == "ongkir_ppn_by_cust")) {
//            $specMains['ongkir_net'] = $mainDatas['ongkir_net'];
//        }
//        else {
//            $specMains['ongkir_net'] = 0;
//        }

        // $datas[] = $specMains;
        // item
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "jml",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
            "ongkir_net",
        );
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
        $tr->addFilter("param ='items'");
        $regs = $tr->lookupRegistries()->result();
        cekLime($this->db->last_query());
        // arrPrint($regs);
        $regDatas = array();
        $sumNett1 = 0;
        foreach ($regs as $reg) {
            $trId = $reg->transaksi_id;
            // $refId = $reg->referenceID;
            $regValues = blobDecode($reg->values);
            // cekBiru("trId:: $trId");
            // arrPrint($regValues);
            foreach ($regValues as $regValue) {
                foreach ($itemKoloms as $itemKolom) {
                    $$itemKolom = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;

                    // $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;
                    $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : (isset($specMains[$itemKolom]) ? $specMains[$itemKolom] : 0);
                }
                // isset($sumJml) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                isset($sumNett1) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                $datas[] = $specs;

            }
            // $regDatas[$trId]['nett1'] = $regValues['nett1'];
            // $regDatas[$trId]['referenceID'] = isset($regValues['referenceID']) ? $regValues['referenceID'] : 0;
            // arrPrint($regValues);
        }
        // endregion membaca 1 data

        // arrPrint($datas);
        cekLime("nett1:: $sumNett1");
        // mati_disini(__LINE__ . __METHOD__);

        // region marking data transaksi yg sudah dibaca
        $tr->setFilters(array());
        $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));        //dimatikan dulu untuk debug
        cekHere($this->db->last_query());

        foreach ($jurnalIds as $jurnalId) {

            $ju->updateData("id = '$jurnalId'", array("r_jenis" => 1)); //dimatikan dulu untuk debug
            cekBiru($this->db->last_query());
        }
        // endregion marking data transaksi yg sudah dibaca

        $cek = 0;
        foreach ($datas as $data) {
            $dbReport = new MdlReport();
            $cek++;
            // $dbReport->trans_start();

            foreach ($itemKoloms as $itemKolom) {
                $$itemKolom = $data[$itemKolom];
            }
            switch ($transaksi_jenis) {
                case "382spd":
                case "582spd":
                    $newDatas_00 = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        // "object_id"    => $id,
                        // "object_nama"  => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => ($sub_nett1 + $ongkir_net),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_nett1 + $ongkir_net),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_01 = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => ($sub_nett1 + $ongkir_net),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_nett1 + $ongkir_net),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_02 = array(
                        // "subject_id"   => $id,
                        // "subject_nama" => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => 1,
                        "unit_in" => 0,
                        "unit_af" => 1,
                        // ------------------
                        "nilai_ot" => ($sub_nett1 + $ongkir_net),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_nett1 + $ongkir_net),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    break;
                case "982":
                    $newDatas_00 = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => ($sub_nett1 + $ongkir_net) * -1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_01 = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => ($sub_nett1 + $ongkir_net) * -1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_02 = array(
                        // "subject_id"   => $id,
                        // "subject_nama" => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => 1,
                        "unit_af" => 1 * -1,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => ($sub_nett1 + $ongkir_net) * -1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    break;
                default:
                    matiHere(__METHOD__ . " @" . __LINE__);
                    break;
            }

            // $newDatas['test'] = 9999;

            $dbReport->setJenis($jenis);
            $dbReport->setOrder("id DESC");
            $dbReport->setLimit("1");

            $dbReport->setDebug(true);
            //region harian
            $periode = "harian";
            $dbReport->setPeriode($periode);
            $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            // $dbReport->setTahun($th);
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
            //region counter
            $tmp_c00 = $dbReport->lastCounterHppSellerPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastTanggal_00 = $tCounter_00->tanggal;

                if ($tanggal != $lastTanggal_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastTanggal_00 = "";
            }
            // -------------------------------------
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTanggal = $tCounter->tanggal;

                if ($tanggal != $lastTanggal) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTanggal = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            // cekHitam("$cek *** $periode:: $tanggal != $lastTanggal :: new::$counter last::$lastCounter");

            $counter_hr["counter"] = $counter;
            $counter_hr_00["counter"] = $counter_00;
            // -------------------------------------
            $newDatas_hr = $newDatas + $counter_hr;
            $newDatas_00hr = $newDatas_00 + $counter_hr_00;
            // -------------------------------------
            // arrPrintWebs($newDatas_hr);
            // matiHere(__LINE__);
            $dbReport->setDataGlundungs($newDatas_00hr);
            $dbReport->setDataPp($newDatas_01);
            $dbReport->setDataP($newDatas_02);
            $dbReport->setDatas($newDatas_hr);
            // -------------------------------------
            $dbReport->writeHppSellerProduk($oleh_id, $id);
            $dbReport->writeHppSeller($oleh_id);
            $dbReport->writeHppProdukCabang($id, $placeID);
            $dbReport->writeHppProduk($id);
            $dbReport->writeHpp();
            $dbReport->writeHppCabang($placeID);
            // endregion
            // matiHere("done " . __LINE__);
            // region mingguan
            $periode = "mingguan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            $dbReport->setTahun($th);
            // $dbReport->setOrder("id DESC");
            // $dbReport->setLimit("1");
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];

            //region counter
            $tmp_c00 = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastMg_00 = $tCounter_00->mg;
                $lastTh_00 = $tCounter_00->th;

                if ($th . $mg != $lastTh_00 . $lastMg_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastMg_00 = "";
                $lastTh_00 = "";
            }
            // ----------------------
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastMg = $tCounter->mg;
                $lastTh = $tCounter->th;

                if ($th . $mg != $lastTh . $lastMg) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastMg = "";
                $lastTh = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$mg != $lastTh.$lastMg :: new::$counter last::$lastCounter");

            $counter_mg["counter"] = $counter;
            $counter_mg00["counter"] = $counter_00;
            // ----------------------------------
            $newDatas_mg = $newDatas + $counter_mg;
            $newDatas_mg00 = $newDatas_00 + $counter_mg00;
            //--------------------------------
            $dbReport->setDatas($newDatas_mg);
            $dbReport->setDataGlundungs($newDatas_mg00);

            $dbReport->writeHppSellerProduk($oleh_id, $id);
            $dbReport->writeHppSeller($oleh_id);
            $dbReport->writeHppProdukCabang($id, $placeID);
            $dbReport->writeHppProduk($id);
            $dbReport->writeHpp();
            $dbReport->writeHppCabang($placeID);
            // endregion mingguan

            // region bulanan
            $periode = "bulanan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            $dbReport->setBulan($bl);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            $tmp_c00 = $dbReport->lastCounterHppSellerPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastBl = $tCounter->bl;
                $lastTh = $tCounter->th;

                if ($th . $bl != $lastTh . $lastBl) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastBl = "";
                $lastTh = "";
            }

            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastBl_00 = $tCounter_00->bl;
                $lastTh_00 = $tCounter_00->th;

                if ($th . $bl != $lastTh_00 . $lastBl_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastBl_00 = "";
                $lastTh_00 = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$bl != $lastTh.$lastBl :: new::$counter last::$lastCounter");

            $counter_bl["counter"] = $counter;
            $counter_bl00["counter"] = $counter_00;
            // --------------------------------
            $newDatas_bl = $newDatas + $counter_bl;
            $newDatas_bl00 = $newDatas_00 + $counter_bl00;
            // --------------------------------
            $dbReport->setDatas($newDatas_bl);
            $dbReport->setDataGlundungs($newDatas_bl00);
            // --------------------------------
            $dbReport->writeHppSellerProduk($oleh_id, $id);
            $dbReport->writeHppSeller($oleh_id);
            $dbReport->writeHppProdukCabang($id, $placeID);
            $dbReport->writeHppProduk($id);
            $dbReport->writeHpp();
            $dbReport->writeHppCabang($placeID);
            // endregion bulanan

            // region tahunan
            $periode = "tahunan";
            $dbReport->setPeriode($periode);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTh = $tCounter->th;

                if ($th != $lastTh) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTh = "";
            }

            $tmp_c00 = $dbReport->lastCounterHppSellerPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastTh_00 = $tCounter_00->th;

                if ($th != $lastTh_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastTh_00 = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th != $lastTh :: new::$counter last::$lastCounter");

            $counter_th["counter"] = $counter;
            $counter_th00["counter"] = $counter_00;
            $newDatas_th = $newDatas + $counter_th;
            $newDatas_th00 = $newDatas_00 + $counter_th00;
            $dbReport->setDatas($newDatas_th);
            $dbReport->setDataGlundungs($newDatas_th00);

            //=====================================
            $dbReport->writeHppSellerProduk($oleh_id, $id);
            $dbReport->writeHppSeller($oleh_id);
            $dbReport->writeHppProdukCabang($id, $placeID);
            $dbReport->writeHppProduk($id);
            $dbReport->writeHpp();
            $dbReport->writeHppCabang($placeID);
            // endregion tahunan

            // $dbReport->trans_complete() or matiHere("gagal ocmit");
        }


    }

    public function genHpp()
    {

        $arrRekening = array(
            'hpp'
        );

        // header("Refresh:2");
//        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        $jenis = "hpp";
        // $jenis = key($reportJenis);
        // cekMerah("$jenis");
        $jenis_list = implode("','", $reportJenis[$jenis]);
        // cekHere($jenis." ".$jenis_list);
        //         matiHere(__METHOD__ ." @". __LINE__);
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComJurnal");
        $this->load->model("Mdls/MdlReport");
        $tr = new MdlTransaksi();
        $ju = new ComJurnal();

        $this->db->limit(1);
        $this->db->where("jenis in('" . $jenis_list . "')");
        $this->db->where("rekening in(" . "'" . implode("','", $arrRekening) . "'" . ")");
        // $ju->addFilter("rekening in('penjualan','return penjualan')");
        $ju->addFilter("transaksi_id!=''");
        $ju->addFilter("r_jenis='0'");
        $ju->setSortBy(array("kolom" => "id", "mode" => "asc"));
        $scrJurnal = $ju->lookupAll()->result();

//        cekHitam('$scrJurnal');
//        cekHitam( sizeof($scrJurnal) );
//        arrPrint($scrJurnal);

        cekMerah($this->db->last_query());
        sizeof($scrJurnal) < 1 ? matiHere(__METHOD__ . " source data sudah habis " . __LINE__) : "";
        // sizeof($scrJurnal) < 1 ? cekHitam(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // arrPrint($scrJurnal);
        $jTransaksi_id = $scrJurnal[0]->transaksi_id;


        // $ju->addFilter("rekening='penjualan'");
//        $this->db->limit(1);
//        $this->db->where("rekening in(" . "'" . implode("','", $arrRekeningHpp) . "'" . ")");
//        $ju->addFilter("transaksi_id='" . $jTransaksi_id . "'");
//        $jurnalPenjualans = $ju->lookupAll()->result();
//        cekHere($this->db->last_query());
//        arrPrint($jurnalPenjualans);
//        if (sizeof($jurnalPenjualans) > 1) {
//            $rekening=array();
//            $debet=0;
//            $kredit=0;
//            foreach($jurnalPenjualans as $ky=>$dataT){
//                $debet+=$dataT->debet;
//                $kredit+=$dataT->kredit;
//            }
//            arrPrint($debet-$kredit);

//            $cekDatas = "trId: $jTransaksi_id";
//            $cekDatas .= "<br>trId: $jTransaksi_id";
//            arrPrint($jurnalPenjualans);
//            matiHere(__METHOD__ . " ada kemungkinan doble jurnal, harap diperikasa $cekDatas");
//        };
        // arrPrint($jurnalPenjualans);

        $jurnalIds[] = $scrJurnal[0]->id;

//arrPrint($jurnalIds);
//         matiHere(__LINE__ . __METHOD__);

        // region membaca 1 data
        // $condite = "status = '1'";
        $tr->setFilters(array());
        $this->db->order_by("id");
        $this->db->limit(1);
        $tr->addFilter("id='" . $jTransaksi_id . "'");
        $tr->addFilter("id_master>'0'");
        // $tr->addFilter("jenis in('".$jenis_list."')");
        // $tr->addFilter("r_jenis='0'");
//        $this->db->where("jenis in('" . $jenis_list . "')");
        // $scr_0 = $tr->lookupByCondition($condite)->result();
        $scr = $tr->lookupAll()->result();
        cekHijau($this->db->last_query());
//arrPrint($scr);
//        matiHere();
        sizeof($scr) == 0 ? matiHere(__LINE__ . __METHOD__ . " source data sudah habis") : "";
        $scr_0 = $scr[0];

        $transaksi_id = $scr_0->id;
        $transaksi_jenis = $scr_0->jenis;
        $id_master = $scr_0->id_master;
        // $oleh_nama = $scr_0->oleh_nama;
        $dtime = $transaksi_dtime = $scr_0->dtime;

        $tanggal = formatTanggal($dtime, 'Y-m-d');
        $tg = formatTanggal($dtime, 'd') * 1;
        $mg = formatTanggal($dtime, 't') * 1;
        $bl = formatTanggal($dtime, 'm') * 1;
        $th = formatTanggal($dtime, 'Y') * 1;


        // region transaksi awal SO
        $scr_1Koloms = array(
            "oleh_nama",
            "oleh_id",
        );

        $tr->setFilters(array());
        $this->db->select($scr_1Koloms);
        $scr_1 = $tr->lookupByCondition("id = '$id_master'")->result()[0];
        cekMerah($this->db->last_query());
        foreach ($scr_1Koloms as $kolom) {
            $$kolom = $scr_1->$kolom;
        }
        // endregion transaksi awal SO

        // endregion membaca 1 data

        // arrPrint($scr);
        // arrPrint($scr_1);
        // mati_disini(__LINE__ . __METHOD__);

        // region membaca data registry
        // main
//        $itemKoloms = array(
//            "id",
//            "nama",
//            "produk_kode",
//            "jml",
//            "harga",
//            "sub_nett1",
//            "sub_ppn",
//            "sub_nett2",
//            "pihakID",
//            "pihakName",
//            "placeID",
//            "placeName",
//            "gudangID",
//            "gudangName",
//            "olehID",
//            "olehName",
//        );
//        $tr->setFilters(array());
//        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
//        $tr->addFilter("param ='main'");
//        $regMains = $tr->lookupRegistries()->result();
//        cekLime($this->db->last_query() . " ::@" . __LINE__);
//        $mainDatas = blobDecode($regMains[0]->values);
        // arrPrintWebs($mainDatas);
//        if ((isset($mainDatas['shippingService'])) && ($mainDatas['shippingService'] == "ongkir_ppn_by_cust")) {
//            $specMains['ongkir_net'] = $mainDatas['ongkir_net'];
//        }
//        else {
//            $specMains['ongkir_net'] = 0;
//        }

        // $datas[] = $specMains;
        // item
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "jml",
            "harga",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
            "ongkir_net",
        );
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
        $tr->addFilter("param ='items'");
        $regs = $tr->lookupRegistries()->result();

//        cekLime($this->db->last_query());
//         arrPrint($regs);
//matiHere();
        $regDatas = array();
        $sumNett1 = 0;
        foreach ($regs as $reg) {
            $trId = $reg->transaksi_id;
            // $refId = $reg->referenceID;
            $regValues = blobDecode($reg->values);
            // cekBiru("trId:: $trId");
            // arrPrint($regValues);
            foreach ($regValues as $regValue) {
                foreach ($itemKoloms as $itemKolom) {
                    $$itemKolom = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;
                    // $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;
                    $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : $specMains[$itemKolom];
                }
                // isset($sumJml) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                isset($sumNett1) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                $datas[] = $specs;
            }
            // $regDatas[$trId]['nett1'] = $regValues['nett1'];
            // $regDatas[$trId]['referenceID'] = isset($regValues['referenceID']) ? $regValues['referenceID'] : 0;
            // arrPrint($regValues);
        }
        // endregion membaca 1 data

        // arrPrint($datas);
        cekLime("nett1:: $sumNett1");
        // mati_disini(__LINE__ . __METHOD__);

//        region marking data transaksi yg sudah dibaca
//        $tr->setFilters(array());
//        $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));        //dimatikan dulu untuk debug
//        cekHere($this->db->last_query());

        foreach ($jurnalIds as $jurnalId) {
            $ju->updateData("id = '$jurnalId'", array("r_jenis" => 1)); //dimatikan dulu untuk debug
            cekBiru($this->db->last_query());
        }
        // endregion marking data transaksi yg sudah dibaca

//        cekHitam($transaksi_jenis);
        arrPrint('$jurnalId');
        arrPrint($jurnalId);
        cekMerah('$scrJurnal');
        arrPrint($scrJurnal);
        cekMerah('$datas LINE:' . __LINE__);
        arrPrint($datas);
//        matiHere();
        $cek = 0;
        foreach ($scrJurnal as $data) {
            $data = (array)$data;
            cekHitam('masuk sini gak sih');
            cekHitam($transaksi_jenis);
            arrPrint($data);

            $dbReport = new MdlReport();
            $cek++;
            // $dbReport->trans_start();

            foreach ($itemKoloms as $itemKolom) {
                $$itemKolom = isset($data[$itemKolom]) ? $data[$itemKolom] : "";
            }
            switch ($transaksi_jenis) {
                case "582spd":
                case "982":
                case "382spd":
                    //SERVICE RECEIVED NOTE
                    //debet -> biaya import
                    $kredit = isset($scrJurnal[0]->kredit) ? $scrJurnal[0]->kredit : 0;
                    $debet = isset($scrJurnal[0]->debet) ? $scrJurnal[0]->debet : 0;
                    $newDatas_00 = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        // "object_id"    => $id,
                        // "object_nama"  => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => $debet,
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => $kredit,
                        "nilai_af" => ($debet - $kredit),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_01 = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => $debet,
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => $kredit,
                        "nilai_af" => ($debet - $kredit),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas_02 = array(
                        // "subject_id"   => $id,
                        // "subject_nama" => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => 1,
                        "unit_in" => 0,
                        "unit_af" => 1,
                        // ------------------
                        "nilai_ot" => $debet,
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => $kredit,
                        "nilai_af" => ($debet - $kredit),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    $newDatas = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => $debet,
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => $kredit,
                        "nilai_af" => ($debet - $kredit),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                    );
                    break;
                default:
                    cekOrange('$transaksi_jenis: ' . $transaksi_jenis);
                    matiHere(__METHOD__ . " @" . __LINE__);
                    break;

            }

            cekMerah('$newDatas');
            arrPrint($newDatas);

//            matiHere();
            // $newDatas['test'] = 9999;

            $dbReport->setJenis($jenis);
            $dbReport->setOrder("id DESC");
            $dbReport->setLimit("1");

            $dbReport->setDebug(true);
            //region harian
            $periode = "harian";
            $dbReport->setPeriode($periode);
            $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            // $dbReport->setTahun($th);
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
            //region counter
//            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
//            if (sizeof($tmp_c00) > 0) {
//                $tCounter_00 = $tmp_c00[0];
//                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
//                $lastTanggal_00 = $tCounter_00->tanggal;
//
//                if ($tanggal != $lastTanggal_00) {
//                    $counter_00 = $lastCounter_00 + 1;
//                }
//                else {
//                    $counter_00 = $lastCounter_00;
//                }
//            }
//            else {
//                $counter_00 = 1;
//                $lastCounter_00 = 0;
//                $lastTanggal_00 = "";
//            }
            // -------------------------------------
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTanggal = $tCounter->tanggal;

                if ($tanggal != $lastTanggal) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTanggal = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            // cekHitam("$cek *** $periode:: $tanggal != $lastTanggal :: new::$counter last::$lastCounter");

            $counter_hr["counter"] = $counter;
//            $counter_hr_00["counter"] = $counter_00;
            // -------------------------------------
            $newDatas_hr = $newDatas + $counter_hr;
//            $newDatas_00hr = $newDatas_00 + $counter_hr_00;
            // -------------------------------------
            cekLime($periode);
//             arrPrintWebs($newDatas_01);
//             arrPrintWebs($newDatas_02);
//             arrPrintWebs($newDatas_hr);
//             matiHere(__LINE__);
//            $dbReport->setDataGlundungs($newDatas_00hr);
            $dbReport->setDataPp($newDatas_01);
            $dbReport->setDataP($newDatas_02);
            $dbReport->setDatas($newDatas_hr);
            // -------------------------------------
//            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
//            $dbReport->writePenjualanSeller($oleh_id);
//            $dbReport->writePenjualanProdukCabang($id, $placeID);
//            $dbReport->writePenjualanProduk($id);
            $dbReport->writeHpp();
//            $dbReport->writePenjualanCabang($placeID);
            // endregion
//             matiHere("done " . __LINE__);
            // region mingguan
            $periode = "mingguan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            $dbReport->setTahun($th);
            // $dbReport->setOrder("id DESC");
            // $dbReport->setLimit("1");
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];

            //region counter
            $tmp_c00 = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c00) > 0) {
                $tCounter_00 = $tmp_c00[0];
                // matiHere(__LINE__);
                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
                $lastMg_00 = $tCounter_00->mg;
                $lastTh_00 = $tCounter_00->th;

                if ($th . $mg != $lastTh_00 . $lastMg_00) {
                    $counter_00 = $lastCounter_00 + 1;
                }
                else {
                    $counter_00 = $lastCounter_00;
                }
            }
            else {
                $counter_00 = 1;
                $lastCounter_00 = 0;
                $lastMg_00 = "";
                $lastTh_00 = "";
            }
            // ----------------------
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastMg = $tCounter->mg;
                $lastTh = $tCounter->th;

                if ($th . $mg != $lastTh . $lastMg) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastMg = "";
                $lastTh = "";
            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$mg != $lastTh.$lastMg :: new::$counter last::$lastCounter");

            $counter_mg["counter"] = $counter;
            $counter_mg00["counter"] = $counter_00;
            // ----------------------------------
            $newDatas_mg = $newDatas + $counter_mg;
            $newDatas_mg00 = $newDatas_00 + $counter_mg00;
            //--------------------------------
            $dbReport->setDatas($newDatas_mg);
            $dbReport->setDataGlundungs($newDatas_mg00);

//            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
//            $dbReport->writePenjualanSeller($oleh_id);
//            $dbReport->writePenjualanProdukCabang($id, $placeID);
//            $dbReport->writePenjualanProduk($id);
            $dbReport->writeHpp();
//            $dbReport->writePenjualanCabang($placeID);
            // endregion mingguan

            // region bulanan
            $periode = "bulanan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            $dbReport->setBulan($bl);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
//            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastBl = $tCounter->bl;
                $lastTh = $tCounter->th;

                if ($th . $bl != $lastTh . $lastBl) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastBl = "";
                $lastTh = "";
            }

//            if (sizeof($tmp_c00) > 0) {
//                $tCounter_00 = $tmp_c00[0];
//                // matiHere(__LINE__);
//                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
//                $lastBl_00 = $tCounter_00->bl;
//                $lastTh_00 = $tCounter_00->th;
//
//                if ($th . $bl != $lastTh_00 . $lastBl_00) {
//                    $counter_00 = $lastCounter_00 + 1;
//                }
//                else {
//                    $counter_00 = $lastCounter_00;
//                }
//            }
//            else {
//                $counter_00 = 1;
//                $lastCounter_00 = 0;
//                $lastBl_00 = "";
//                $lastTh_00 = "";
//            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th.$bl != $lastTh.$lastBl :: new::$counter last::$lastCounter");

            $counter_bl["counter"] = $counter;
//            $counter_bl00["counter"] = $counter_00;
            // --------------------------------
            $newDatas_bl = $newDatas + $counter_bl;
//            $newDatas_bl00 = $newDatas_00 + $counter_bl00;
            // --------------------------------
            $dbReport->setDatas($newDatas_bl);
//            $dbReport->setDataGlundungs($newDatas_bl00);
            // --------------------------------
//            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
//            $dbReport->writePenjualanSeller($oleh_id);
//            $dbReport->writePenjualanProdukCabang($id, $placeID);
//            $dbReport->writePenjualanProduk($id);
            $dbReport->writeHpp();
//            $dbReport->writePenjualanCabang($placeID);
            // endregion bulanan

            // region tahunan
            $periode = "tahunan";
            $dbReport->setPeriode($periode);
            $dbReport->setTahun($th);

            //region counter
            $tmp_c = $dbReport->lastCounterPeriode()->result();
            if (sizeof($tmp_c) > 0) {
                $tCounter = $tmp_c[0];
                // matiHere(__LINE__);
                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                $lastTh = $tCounter->th;

                if ($th != $lastTh) {
                    $counter = $lastCounter + 1;
                }
                else {
                    $counter = $lastCounter;
                }
            }
            else {
                $counter = 1;
                $lastCounter = 0;
                $lastTh = "";
            }

//            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
//            if (sizeof($tmp_c00) > 0) {
//                $tCounter_00 = $tmp_c00[0];
//                // matiHere(__LINE__);
//                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
//                $lastTh_00 = $tCounter_00->th;
//
//                if ($th != $lastTh_00) {
//                    $counter_00 = $lastCounter_00 + 1;
//                }
//                else {
//                    $counter_00 = $lastCounter_00;
//                }
//            }
//            else {
//                $counter_00 = 1;
//                $lastCounter_00 = 0;
//                $lastTh_00 = "";
//            }
            //endregion
            // arrPrintWebs($tmp_c);
            cekHitam("$periode:: $th != $lastTh :: new::$counter last::$lastCounter");

            $counter_th["counter"] = $counter;
//            $counter_th00["counter"] = $counter_00;
            $newDatas_th = $newDatas + $counter_th;
//            $newDatas_th00 = $newDatas_00 + $counter_th00;
            $dbReport->setDatas($newDatas_th);
//            $dbReport->setDataGlundungs($newDatas_th00);

            //=====================================
//            $dbReport->writePenjualanSellerProduk($oleh_id, $id);
//            $dbReport->writePenjualanSeller($oleh_id);
//            $dbReport->writePenjualanProdukCabang($id, $placeID);
//            $dbReport->writePenjualanProduk($id);
            $dbReport->writeHpp();
//            $dbReport->writePenjualanCabang($placeID);
            // endregion tahunan

            // $dbReport->trans_complete() or matiHere("gagal ocmit");
        }

    }

    public function genPembelianOLD()
    {

        // header("Refresh:2");
        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        // $jenis = "582spd";
        // $jenis = key($reportJenis);
        $jenieses = array(
            "pembelian_supplies",
            "pembelian_produk",
        );
        // $jenis = "pembelian_produk";
        foreach ($jenieses as $jenis) {

            cekMerah("$jenis");

            $jenis_list = implode("','", $reportJenis[$jenis]);
            // cekHere($jenis." ".$jenis_list);
            //         matiHere(__METHOD__ ." @". __LINE__);
            $this->load->model("MdlTransaksi");
            $this->load->model("Mdls/MdlReport");
            $tr = new MdlTransaksi();

            // region membaca 1 data
            // $condite = "status = '1'";
            $tr->setFilters(array());
            $this->db->order_by("id");
            $this->db->limit(1);
            $tr->addFilter("id_master>'0'");
            // $tr->addFilter("jenis in('".$jenis_list."')");
            $tr->addFilter("r_jenis='0'");
            $this->db->where("jenis in('" . $jenis_list . "')");
            // $scr_0 = $tr->lookupByCondition($condite)->result();
            $scr = $tr->lookupAll()->result();
            cekHijau($this->db->last_query());
            // sizeof($scr) < 1 ? matiHere(__METHOD__ . " source data sudah habis") : "";
            sizeof($scr) < 1 ? cekHitam(__METHOD__ . " source data $jenis sudah habis") : "";
            $scr_0 = $scr[0];
            $transaksi_id = $scr_0->id;
            $transaksi_jenis = $scr_0->jenis;
            $id_master = $scr_0->id_master;
            // $oleh_nama = $scr_0->oleh_nama;
            $dtime = $transaksi_dtime = $scr_0->dtime;

            $tanggal = formatTanggal($dtime, 'Y-m-d');
            $tg = formatTanggal($dtime, 'd') * 1;
            $mg = formatTanggal($dtime, 't') * 1;
            $bl = formatTanggal($dtime, 'm') * 1;
            $th = formatTanggal($dtime, 'Y') * 1;

            if ($transaksi_id > 0) {


                // region transaksi awal SO
                $scr_1Koloms = array(
                    "oleh_nama",
                    "oleh_id",
                );
                $tr->setFilters(array());
                $this->db->select($scr_1Koloms);
                $scr_1 = $tr->lookupByCondition("id = '$id_master'")->result()[0];
                cekMerah($this->db->last_query());
                foreach ($scr_1Koloms as $kolom) {
                    $$kolom = $scr_1->$kolom;
                }
                // endregion transaksi awal SO

                // endregion membaca 1 data


                // arrPrint($scr);
                // arrPrint($scr_1);
                // mati_disini(__LINE__);

                // region membaca data registry
                $itemKoloms = array(
                    "id",
                    "nama",
                    // "produk_kode",
                    "jml",
                    "sub_harga",
                    "sub_ppn",
                    // "sub_nett2",
                    "pihakID",
                    "pihakName",
                    "placeID",
                    "placeName",
                    "gudangID",
                    "gudangName",
                    "olehID",
                    "olehName",
                );
                $tr->setFilters(array());
                $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
                $tr->addFilter("param ='items'");
                $regs = $tr->lookupRegistries()->result();
                cekLime($this->db->last_query());
                // arrPrint($regs);
                $regDatas = array();
                $sumNett1 = 0;
                foreach ($regs as $reg) {
                    $trId = $reg->transaksi_id;
                    // $refId = $reg->referenceID;
                    $regValues = blobDecode($reg->values);
                    // cekBiru("trId:: $trId");
                    // arrPrint($regValues);
                    foreach ($regValues as $regValue) {
                        foreach ($itemKoloms as $itemKolom) {
                            $$itemKolom = $regValue[$itemKolom];

                            $specs[$itemKolom] = $regValue[$itemKolom];
                        }
                        // isset($sumJml) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                        isset($sumNett1) ? $sumNett1 += $sub_harga : $sumNett1 = 0;
                        $datas[] = $specs;

                    }
                    // $regDatas[$trId]['nett1'] = $regValues['nett1'];
                    // $regDatas[$trId]['referenceID'] = isset($regValues['referenceID']) ? $regValues['referenceID'] : 0;
                    // arrPrint($regValues);
                }
                // endregion membaca 1 data

                // arrPrint($datas);
                // cekLime("nett1:: $sumNett1");
                // mati_disini(__LINE__);

                // region marking data transaksi yg sudah dibaca
                $tr->setFilters(array());
                $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));
                cekHere($this->db->last_query());
                // endregion marking data transaksi yg sudah dibaca

                $cek = 0;
                foreach ($datas as $data) {
                    $dbReport = new MdlReport();
                    $cek++;
                    // $dbReport->trans_start();

                    foreach ($itemKoloms as $itemKolom) {
                        $$itemKolom = $data[$itemKolom];
                    }
                    switch ($transaksi_jenis) {
                        case "582spd":
                            $newDatas = array(
                                "subject_id" => $oleh_id,
                                "subject_nama" => $oleh_nama,
                                "object_id" => $id,
                                "object_nama" => $nama,
                                "object_kode" => $produk_kode,

                                "unit_ot" => $jml,
                                "unit_in" => 0,
                                "unit_af" => $jml,

                                "nilai_ot" => $sub_nett1,
                                "nilai_in" => 0,
                                "nilai_af" => $sub_nett1,

                                "cabang_id" => $placeID,
                                "cabang_nama" => $placeName,
                                "dtime" => $dtime,
                                "tanggal" => $tanggal,
                                "tg" => $tg,
                                "mg" => $mg,
                                "bl" => $bl,
                                "th" => $th,
                                // "counter"   => $counter,
                            );
                            break;
                        case "982":
                            $newDatas = array(
                                "subject_id" => $oleh_id,
                                "subject_nama" => $oleh_nama,
                                "object_id" => $id,
                                "object_nama" => $nama,
                                "object_kode" => $produk_kode,

                                "unit_ot" => 0,
                                "unit_in" => $jml,
                                "unit_af" => $jml * -1,

                                "nilai_ot" => 0,
                                "nilai_in" => $sub_nett1,
                                "nilai_af" => $sub_nett1 * -1,

                                "cabang_id" => $placeID,
                                "cabang_nama" => $placeName,
                                "dtime" => $dtime,
                                "tanggal" => $tanggal,
                                "tg" => $tg,
                                "mg" => $mg,
                                "bl" => $bl,
                                "th" => $th,
                                // "counter"   => $counter,
                            );
                            break;
                        case "461":
                            $newDatas = array(
                                "subject_id" => $pihakID,
                                "subject_nama" => $pihakName,
                                "object_id" => $id,
                                "object_nama" => $nama,
                                // "object_kode"  => $produk_kode,

                                "unit_ot" => $jml,
                                "unit_in" => 0,
                                "unit_af" => $jml,

                                "nilai_ot" => $sub_harga,
                                "nilai_in" => 0,
                                "nilai_af" => $sub_harga,

                                "cabang_id" => $placeID,
                                "cabang_nama" => $placeName,
                                "dtime" => $dtime,
                                "tanggal" => $tanggal,
                                "tg" => $tg,
                                "mg" => $mg,
                                "bl" => $bl,
                                "th" => $th,
                                // "counter"   => $counter,
                            );
                            break;
                        case "961":
                            $newDatas = array(
                                "subject_id" => $pihakID,
                                "subject_nama" => $pihakName,
                                "object_id" => $id,
                                "object_nama" => $nama,
                                // "object_kode"  => $produk_kode,

                                "unit_ot" => 0,
                                "unit_in" => $jml,
                                "unit_af" => $jml * -1,

                                "nilai_ot" => 0,
                                "nilai_in" => $sub_harga,
                                "nilai_af" => $sub_harga * -1,

                                "cabang_id" => $placeID,
                                "cabang_nama" => $placeName,
                                "dtime" => $dtime,
                                "tanggal" => $tanggal,
                                "tg" => $tg,
                                "mg" => $mg,
                                "bl" => $bl,
                                "th" => $th,
                                // "counter"   => $counter,
                            );
                            break;
                        case "467":
                            $newDatas = array(
                                "subject_id" => $pihakID,
                                "subject_nama" => $pihakName,
                                "object_id" => $id,
                                "object_nama" => $nama,
                                // "object_kode"  => $produk_kode,

                                "unit_ot" => $jml,
                                "unit_in" => 0,
                                "unit_af" => $jml,

                                "nilai_ot" => $sub_harga,
                                "nilai_in" => 0,
                                "nilai_af" => $sub_harga,

                                "cabang_id" => $placeID,
                                "cabang_nama" => $placeName,
                                "dtime" => $dtime,
                                "tanggal" => $tanggal,
                                "tg" => $tg,
                                "mg" => $mg,
                                "bl" => $bl,
                                "th" => $th,
                                // "counter"   => $counter,
                            );
                            break;
                        case "967":
                            $newDatas = array(
                                "subject_id" => $pihakID,
                                "subject_nama" => $pihakName,
                                "object_id" => $id,
                                "object_nama" => $nama,
                                // "object_kode"  => $produk_kode,

                                "unit_ot" => 0,
                                "unit_in" => $jml,
                                "unit_af" => $jml * -1,

                                "nilai_ot" => 0,
                                "nilai_in" => $sub_harga,
                                "nilai_af" => $sub_harga * -1,

                                "cabang_id" => $placeID,
                                "cabang_nama" => $placeName,
                                "dtime" => $dtime,
                                "tanggal" => $tanggal,
                                "tg" => $tg,
                                "mg" => $mg,
                                "bl" => $bl,
                                "th" => $th,
                                // "counter"   => $counter,
                            );
                            break;
                        default:
                            matiHere(__METHOD__ . " @" . __LINE__);
                            break;
                    }


                    $dbReport->setDebug(true);
                    $dbReport->setJenis($jenis);
                    $dbReport->setOrder("id DESC");
                    $dbReport->setLimit("1");

                    //region harian
                    $periode = "harian";
                    $dbReport->setPeriode($periode);
                    $dbReport->setTanggal($tanggal);
                    // $dbReport->setMinggu($mg);
                    // $dbReport->setBulan($bl);
                    // $dbReport->setTahun($th);
                    // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
                    //region counter
                    $tmp_c = $dbReport->lastCounterPeriode()->result();
                    if (sizeof($tmp_c) > 0) {
                        $tCounter = $tmp_c[0];
                        $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                        $lastTanggal = $tCounter->tanggal;

                        if ($tanggal != $lastTanggal) {
                            $counter = $lastCounter + 1;
                        }
                        else {
                            $counter = $lastCounter;
                        }
                    }
                    else {
                        $counter = 1;
                        $lastCounter = 0;
                        $lastTanggal = "";
                    }
                    //endregion
                    // arrPrintWebs($tmp_c);
                    cekHitam("$cek *** $periode:: $tanggal != $lastTanggal :: new::$counter last::$lastCounter");

                    $counter_hr["counter"] = $counter;
                    $newDatas_hr = $newDatas + $counter_hr;
                    $dbReport->setDatas($newDatas_hr);
                    $dbReport->writePenjualanSellerProduk($olehID, $id);
                    // endregion

                    // region mingguan
                    $periode = "mingguan";
                    // $dbReport->setDebug(true);
                    // $dbReport->setJenis($jenis);
                    $dbReport->setPeriode($periode);
                    // $dbReport->setTanggal($tanggal);
                    $dbReport->setMinggu($mg);
                    // $dbReport->setBulan($bl);
                    $dbReport->setTahun($th);
                    // $dbReport->setOrder("id DESC");
                    // $dbReport->setLimit("1");
                    // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];
                    //region counter
                    $tmp_c = $dbReport->lastCounterPeriode()->result();
                    if (sizeof($tmp_c) > 0) {
                        $tCounter = $tmp_c[0];
                        // matiHere(__LINE__);
                        $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                        $lastMg = $tCounter->mg;
                        $lastTh = $tCounter->th;

                        if ($th . $mg != $lastTh . $lastMg) {
                            $counter = $lastCounter + 1;
                        }
                        else {
                            $counter = $lastCounter;
                        }
                    }
                    else {
                        $counter = 1;
                        $lastCounter = 0;
                        $lastMg = "";
                        $lastTh = "";
                    }
                    //endregion
                    // arrPrintWebs($tmp_c);
                    cekHitam("$periode:: $th.$mg != $lastTh.$lastMg :: new::$counter last::$lastCounter");

                    $counter_mg["counter"] = $counter;
                    $newDatas_mg = $newDatas + $counter_mg;
                    $dbReport->setDatas($newDatas_mg);
                    $dbReport->writePenjualanSellerProduk($olehID, $id);
                    // endregion mingguan

                    // region bulanan
                    $periode = "bulanan";
                    // $dbReport->setDebug(true);
                    // $dbReport->setJenis($jenis);
                    $dbReport->setPeriode($periode);
                    // $dbReport->setTanggal($tanggal);
                    $dbReport->setMinggu($bl);
                    // $dbReport->setBulan($bl);
                    $dbReport->setTahun($th);

                    //region counter
                    $tmp_c = $dbReport->lastCounterPeriode()->result();
                    if (sizeof($tmp_c) > 0) {
                        $tCounter = $tmp_c[0];
                        // matiHere(__LINE__);
                        $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                        $lastBl = $tCounter->bl;
                        $lastTh = $tCounter->th;

                        if ($th . $bl != $lastTh . $lastBl) {
                            $counter = $lastCounter + 1;
                        }
                        else {
                            $counter = $lastCounter;
                        }
                    }
                    else {
                        $counter = 1;
                        $lastCounter = 0;
                        $lastBl = "";
                        $lastTh = "";
                    }
                    //endregion
                    // arrPrintWebs($tmp_c);
                    cekHitam("$periode:: $th.$bl != $lastTh.$lastBl :: new::$counter last::$lastCounter");

                    $counter_bl["counter"] = $counter;
                    $newDatas_bl = $newDatas + $counter_bl;
                    $dbReport->setDatas($newDatas_bl);
                    $dbReport->writePenjualanSellerProduk($olehID, $id);
                    // endregion bulanan

                    // region tahunan
                    $periode = "tahunan";
                    $dbReport->setPeriode($periode);
                    $dbReport->setTahun($th);

                    //region counter
                    $tmp_c = $dbReport->lastCounterPeriode()->result();
                    if (sizeof($tmp_c) > 0) {
                        $tCounter = $tmp_c[0];
                        // matiHere(__LINE__);
                        $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
                        $lastTh = $tCounter->th;

                        if ($th != $lastTh) {
                            $counter = $lastCounter + 1;
                        }
                        else {
                            $counter = $lastCounter;
                        }
                    }
                    else {
                        $counter = 1;
                        $lastCounter = 0;
                        $lastTh = "";
                    }
                    //endregion
                    // arrPrintWebs($tmp_c);
                    cekHitam("$periode:: $th != $lastTh :: new::$counter last::$lastCounter");

                    $counter_th["counter"] = $counter;
                    $newDatas_th = $newDatas + $counter_th;
                    $dbReport->setDatas($newDatas_th);
                    $dbReport->writePenjualanSellerProduk($olehID, $id);
                    // endregion tahunan

                    // $dbReport->trans_complete() or matiHere("gagal ocmit");
                }
            }
        }
    }

    public function genPenerimaan()
    {
        matiHere(__METHOD__);
    }

    public function genPembayaran()
    {

        mati_disini(__METHOD__);
    }

    public function resetorReport()
    {
        $jenis = $this->uri->segment(3);
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlReport");
        $this->load->model("Coms/ComJurnal");
        $tr = new MdlTransaksi();
        $rp = new MdlReport();
        $ju = new ComJurnal();

        if (!isset($_GET['g']) && $_GET['g'] != 1) {

            matiHere("-------------stop-------------- @" . __LINE__ . __METHOD__);
        }

        cekKuning($jenis);
        $jenis_array = $this->reportJenis[$jenis];
        // arrPrint($jenis_array);
        switch ($jenis) {
            case "hpp":
            case "biaya":
                $tr->_resetorReport($jenis_array);
                cekOrange($this->db->last_query() . "<hr>" . $this->db->affected_rows() . " row telah dieksekusi");
                $ju->_resetorReport($jenis_array);
                cekLime($this->db->last_query() . "<hr>" . $this->db->affected_rows() . " row telah dieksekusi");
                break;
            case "penjualan":
                $tr->_resetorReport($jenis_array);
                cekOrange($this->db->last_query() . "<hr>" . $this->db->affected_rows() . " row telah dieksekusi");

                $ju->_resetorReport($jenis_array);
                cekLime($this->db->last_query() . "<hr>" . $this->db->affected_rows() . " row telah dieksekusi");

                break;

            case "pembelian_produk":
            case "pembelian_supplies":
            case "pre_penjualan":
                $tr->_resetorReport($jenis_array);
                cekOrange($this->db->last_query() . "<hr>" . $this->db->affected_rows() . " row telah dieksekusi");

                break;

            case "pre_penjualan_canceled":

                $tr->_resetorReportSign($jenis_array);
                cekMerah($this->db->last_query() . "<hr>" . $this->db->affected_rows() . " row telah dieksekusi");
                break;

            default:
                matiHere(__METHOD__ . " STOP");
                break;

        }


        $rp->setDebug(true);
        $rp->_resetorReport($jenis);

    }

    public function lowercaseRekening()
    {
        $tableDta = array(
            "dta_aktivatakberwujud" => "nama",
            "dta_akumpenyusutanaktivatetap" => "nama",
            "dta_bebanlainlain" => "nama",
            "dta_biayaoperasional" => "nama",
            "dta_biayaproduksi" => "nama",
            "dta_biayaumum" => "nama",
            "dta_biayausaha" => "nama",
            "dta_labaditahan" => "nama",
            "dta_modal" => "nama",
            "dta_person2" => "nama",
            "dta_subpendapatan" => "nama",
            "dta_supplier2" => "nama",
            "__rek_pembantu_subbiayaproduksi__biaya_produksi" => "extern_nama",
            "__rek_pembantu_subbiayaumum__biaya_umum" => "extern_nama",
            "__rek_pembantu_subbiayausaha__biaya_usaha" => "extern_nama",
            "__rek_pembantu_person2__piutang_lain" => "extern_nama",
            "__rek_pembantu_modal__modal" => "extern_nama",
            "__rek_pembantu_supplier2__beban_harus_dibayar" => "extern_nama",
            "__rek_pembantu_subpendapatan__pendapatan" => "extern_nama",
            "__rek_pembantu_labaditahan__laba_ditahan" => "extern_nama",
            "__rek_pembantu_bebanlainlain__beban_lain_lain" => "extern_nama",
            "__rek_pembantu_akumpenyuaktivatetap__akum_penyu_aktiva_tetap" => "extern_nama",
            "__rek_pembantu_aktivatetap__aktiva_tetap" => "extern_nama",
            "_rek_pembantu_subbiaya_cache" => "extern_nama",
            "_rek_pembantu_subbiayaproduksi_cache" => "extern_nama",
            "_rek_pembantu_subbiayaumum_cache" => "extern_nama",
            "_rek_pembantu_subbiayausaha_cache" => "extern_nama",
            "_rek_pembantu_subpendapatan_cache" => "extern_nama",
            "_rek_pembantu_supplier2_cache" => "extern_nama",
            "_rek_pembantu_person2_cache" => "extern_nama",
            "_rek_pembantu_modal_cache" => "extern_nama",
            "_rek_pembantu_labaditahan_cache" => "extern_nama",
            "_rek_pembantu_bebanlainlain_cache" => "extern_nama",
            "_rek_pembantu_akumpenyuaktivatetap_cache" => "extern_nama",
            "_rek_pembantu_aktivatetap_cache" => "extern_nama",
            "_rek_pembantu_aktivatakberwujud_cache" => "extern_nama",
        );

        $afected = 0;
        foreach ($tableDta as $tbl => $kolom) {

            // $this->db->select($kolom);
            $this->db->trans_commit();

            $scr = $this->db->get($tbl)->result();
            cekHere($this->db->last_query());
            // arrPrint($scr);
            foreach ($scr as $item) {
                $id = $item->id;
                $$kolom = $item->$kolom;

                $namaNew = str_replace("&", "dan", strtolower($$kolom));


                // region update data ke lowercase
                $this->db->set($kolom, $namaNew);
                $this->db->where("id", $id);
                $this->db->update($tbl);
                cekUngu($this->db->last_query());
                // endregion update data ke lowercase

                $afected += $this->db->affected_rows();
            }
            // mati_disini(__LINE__ . " **** $afected");

            $this->db->trans_complete();
            cekHitam("$afected terdampak");
        }
    }

    public function syncProdukNama()
    {

        $prods = $this->db->get("produk")->result();
        showLast_query("merah");
        // arrPrint($prods);
        foreach ($prods as $prod) {
            $prodSpecs[$prod->id] = $prod->kode . " " . $prod->nama;
        }

        $tbl_target = array(
            "_rek_pembantu_produk_cache" => "extern_nama"
        );

        foreach ($tbl_target as $itemTbl => $itemKolom) {

            $targets = $this->db->get($itemTbl)->result();
            showLast_query("hijau");

            $sumAffected = 0;
            foreach ($targets as $target) {
                // arrPrint($target);

                $xtern_id = $target->extern_id;
                $newNama = $prodSpecs[$xtern_id];

                $updtes = array(
                    $itemKolom => $newNama,
                );
                $wheres = array(
                    "extern_id" => $xtern_id,
                );
                $this->db->update($itemTbl, $updtes, $wheres);
                showLast_query("lime");
                $affected = $this->db->affected_rows();

                $sumAffected += $affected;
                // mati_disini(__LINE__ . " $sumAffected");
            }

            cekHijau("$sumAffected updated /" . sizeof($targets));
        }

    }

    public function genTransaksiReports()
    {
        $read_registry = 1;
        $this->db2 = $this->load->database('report', TRUE);
        $this->load->model("Mdls/MdlReport");
        $rp = new MdlReport();

        $rp->setDebug(true);
        // $rp->setLimit(1);
        $rp->setOrder("dtime ASC");
        $srcRps = $rp->lookupPrePenjualan()->result();
        $srcRpc = $rp->lookupPrePenjualanCanceled()->result();
        $srcRpp = $rp->lookupSalesMonthly()->result();

        mati_disini(__LINE__ . " " . __METHOD__);
        // arrPrint($srcRpc);

        //region spo
        $replacecer = array(
            "unit_ot" => "unit_ot_spo",
            "unit_in" => "unit_in_spo",
            "unit_af" => "unit_af_spo",
            "nilai_ot" => "nilai_ot_spo",
            "nilai_in" => "nilai_in_spo",
            "nilai_af" => "nilai_af_spo",
        );
        $srcRps1 = array();
        foreach ($srcRps as $items) {
            $items2 = array();
            foreach ($items as $item_key => $item_value) {
                $new_key = array_key_exists($item_key, $replacecer) ? $replacecer[$item_key] : $item_key;
                $items2[$new_key] = $item_value;
            }
            $srcRps1[] = (object)$items2;
        }
        //endregion
        // arrPrint($srcRps1);
        //region cancel so
        $replacecer = array(
            "unit_ot" => "unit_ot_cl",
            "unit_in" => "unit_in_cl",
            "unit_af" => "unit_af_cl",
            "nilai_ot" => "nilai_ot_cl",
            "nilai_in" => "nilai_in_cl",
            "nilai_af" => "nilai_af_cl",
        );
        $cancelScr1 = array();
        foreach ($srcRpc as $items) {
            $items2 = array();
            foreach ($items as $item_key => $item_value) {
                $new_key = array_key_exists($item_key, $replacecer) ? $replacecer[$item_key] : $item_key;
                $items2[$new_key] = $item_value;
            }
            $srcRpc1[] = (object)$items2;
        }
        //endregion
        // arrPrintWebs($srcRpc1);
        //region spd
        $replacecer = array(
            "unit_ot" => "unit_ot_spd",
            "unit_in" => "unit_in_spd",
            "unit_af" => "unit_af_spd",
            "nilai_ot" => "nilai_ot_spd",
            "nilai_in" => "nilai_in_spd",
            "nilai_af" => "nilai_af_spd",
        );
        $srcRpp1 = array();
        foreach ($srcRpp as $items) {
            $items2 = array();
            foreach ($items as $item_key => $item_value) {
                $new_key = array_key_exists($item_key, $replacecer) ? $replacecer[$item_key] : $item_key;
                $items2[$new_key] = $item_value;
            }
            $srcRpp1[] = (object)$items2;
        }
        //endregion
        // arrPrintWebs($srcRpp1);

        $srcRp = array_merge($srcRps1, $srcRpc1, $srcRpp1);

        $newSumKoloms = array(
            "cabang" => array(
                "unit_ot_spo" => "unit_in_spo",
                "nilai_ot_spo" => "nilai_in_spo",
                "unit_ot_cl" => "unit_in_cl",
                "nilai_ot_cl" => "nilai_in_cl",

                "unit_ot_spd" => "unit_otin",
                "unit_in_spd" => "unit_otot",
                "unit_af_spd" => "unit_ot",

                "nilai_ot_spd" => "nilai_otin",
                "nilai_in_spd" => "nilai_otot",
                "nilai_af_spd" => "nilai_ot",
            ),
            "subject" => array(
                "unit_ot_spo" => "unit_in_spo",
                "nilai_ot_spo" => "nilai_in_spo",
                "unit_ot_cl" => "unit_in_cl",
                "nilai_ot_cl" => "nilai_in_cl",

                "unit_ot_spd" => "unit_otin",
                "unit_in_spd" => "unit_otot",
                "unit_af_spd" => "unit_ot",

                "nilai_ot_spd" => "nilai_otin",
                "nilai_in_spd" => "nilai_otot",
                "nilai_af_spd" => "nilai_ot",
            ),
            "object" => array(
                "unit_ot_spo" => "unit_in_spo",
                "nilai_ot_spo" => "nilai_in_spo",
                "unit_ot_cl" => "unit_in_cl",
                "nilai_ot_cl" => "nilai_in_cl"
            ,
                "unit_ot_spd" => "unit_otin",
                "unit_in_spd" => "unit_otot",
                "unit_af_spd" => "unit_ot",

                "nilai_ot_spd" => "nilai_otin",
                "nilai_in_spd" => "nilai_otot",
                "nilai_af_spd" => "nilai_ot",
            ),
        );
        $newKoloms = array(
            "cabang" => array(
                // "old_kolom" => "new_kolom",
                "cabang_id" => "subject_id",
                "cabang_nama" => "subject_nama",
                "dtime" => "dtime",
                "tanggal" => "tanggal",
                "th" => "th",
                "bl" => "bl",
            ),
            "subject" => array(
                // "old_kolom" => "new_kolom",
                "subject_id" => "subject_id",
                "subject_nama" => "subject_nama",
                "dtime" => "dtime",
                "tanggal" => "tanggal",
                "th" => "th",
                "bl" => "bl",
            ),
            "object" => array(
                // "old_kolom" => "new_kolom",
                "object_id" => "subject_id",
                "object_nama" => "subject_nama",
                "object_kode" => "subject_kode",
                "dtime" => "dtime",
                "tanggal" => "tanggal",
                "th" => "th",
                "bl" => "bl",
            ),
        );

        // arrPrint($srcRp);

        $cabangDatas = array();
        foreach ($srcRp as $datas) {

            //region fix data
            foreach ($newKoloms as $dataNama => $koloms) {
                // cekHere("$dataNama");
                foreach ($koloms as $oldKolom => $newKolom) {
                    $data_key = $dataNama . "_id";
                    $datas2[$dataNama][$datas->$data_key][$datas->th][$datas->bl][$newKolom] = $datas->$oldKolom;
                }
            }
            //endregion

            //region sum data
            foreach ($newSumKoloms as $dataNama => $koloms) {
                // cekHere("$dataNama");
                foreach ($koloms as $oldKolom => $newKolom) {

                    // $values = isset($datas->$oldKolom) ? $datas->$oldKolom : 0;
                    // cekMerah("$oldKolom:: $values");

                    $data_key = $dataNama . "_id";
                    if (!isset($datas2[$dataNama][$datas->$data_key][$datas->th][$datas->bl][$newKolom])) {
                        $datas2[$dataNama][$datas->$data_key][$datas->th][$datas->bl][$newKolom] = 0;
                    }
                    $datas2[$dataNama][$datas->$data_key][$datas->th][$datas->bl][$newKolom] += isset($datas->$oldKolom) ? $datas->$oldKolom : 0;
                }
            }
            //endregion

        }

        // arrPrint($datas2);
        foreach ($newSumKoloms as $dataNama => $koloms) {
            // cekHijau($dataNama);
            foreach ($datas2[$dataNama] as $subjId => $dateDatas) {
                // arrPrint($dateDatas);
                foreach ($dateDatas as $year => $dateData) {
                    // arrPrint($dateData);
                    foreach ($dateData as $month => $datas3) {

                        // arrPrint($datas3);
                        $sumNettSpo = $datas3["nilai_in_spo"] - $datas3['nilai_in_cl'];
                        $sumNett = $sumNettSpo - $datas3['nilai_ot'];

                        $sumUnitNettSpo = $datas3["unit_in_spo"] - $datas3['unit_in_cl'];
                        $sumUnitNett = $sumUnitNettSpo - $datas3['unit_ot'];

                        $datas4[$dataNama][$subjId][$year][$month] = $datas3;
                        $datas4[$dataNama][$subjId][$year][$month]["unit_inin"] = $datas3["unit_in_spo"];
                        $datas4[$dataNama][$subjId][$year][$month]["unit_inot"] = $datas3["unit_in_cl"];
                        $datas4[$dataNama][$subjId][$year][$month]["unit_in"] = $sumUnitNettSpo;
                        // $datas4[$dataNama][$year][$month]["nilai_in"] = $sumUnitNettSpo;
                        $datas4[$dataNama][$subjId][$year][$month]["unit_af"] = $sumUnitNett;

                        $datas4[$dataNama][$subjId][$year][$month]["nilai_inin"] = $datas3["nilai_in_spo"];
                        $datas4[$dataNama][$subjId][$year][$month]["nilai_inot"] = $datas3["nilai_in_cl"];
                        $datas4[$dataNama][$subjId][$year][$month]["nilai_in"] = $sumNettSpo;
                        $datas4[$dataNama][$subjId][$year][$month]["nilai_af"] = $sumNett;

                    }
                }
            }
        }

        // arrPrint($cabangDatas);
        // arrPrintLime($datas4);
        $kolomMasukan = array(
            "unit_inin",
            "unit_inot",
            "unit_in",
            "unit_otin",
            "unit_otot",
            "unit_ot",
            "unit_af",
            "nilai_inin",
            "nilai_inot",
            "nilai_in",
            "nilai_otin",
            "nilai_otot",
            "nilai_ot",
            "nilai_af",
        );

        foreach ($newKoloms as $reportnama => $kolomMasuks1) {
            $kolomMasuks = array_merge($kolomMasuks1, $kolomMasukan);
            // cekHijau($reportnama);
            // arrPrint($kolomMasuks);
            foreach ($datas4[$reportnama] as $sId => $datas5) {
                foreach ($datas5 as $th => $datas6) {
                    foreach ($datas6 as $bl => $datas7) {

                        foreach ($kolomMasuks as $kolomMasuk) {

                            $insDatas[$kolomMasuk] = isset($datas7[$kolomMasuk]) ? $datas7[$kolomMasuk] : 0;
                        }
                        $insDatas['periode'] = 'bulanan';
                        // cekHijau("$reportnama :: $sId :: $th :: $bl");
                        arrPrint($insDatas);

                        // matiHere();
                        // $rp->setTahun($th);
                        // $rp->setBulan($bl);
                        $condites = array(
                            "subject_id" => $sId,
                        );
                        $rp->setCondites($condites);
                        $rp->setLimit(1);
                        $rp->setOrder("id DESC");
                        $rp->writePrePenjualanMovement($reportnama, $insDatas);

                        // $ceks = $rp->lookupPrePenjualan_($reportnama)->result();
                        // arrPrint($ceks);
                    }


                }
            }
        }

    }

    public function insertMutasiProduk()
    {
        $mdlName = "ComRekeningPembantuProduk";
        $this->load->model("Coms/" . $mdlName);
        $com = new $mdlName();
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $com = new ComRekeningPembantuProduk();

        $tmlLast = $com->fetchLastMoves()->result();
        showLast_query("lime");
        $koloms = array(
            // "id",
            // "dtime",
            "rek_id",
            "rekening",
            "extern_id",
            "extern_nama",
            "cabang_id",
            "gudang_id",
            "gudang_nama",
            "debet_akhir",
            "qty_debet_akhir",
            "harga",
            "harga_avg",
            "harga_awal",
        );
        $insertKoloms = array(
            "debet_akhir" => "debet_awal",
            "qty_debet_akhir" => "qty_debet_awal",
        );
        // arrPrint($tmlLast);
        $scrDatas = array();
        foreach ($tmlLast as $items) {
            $cbId = $items->cabang_id;
            foreach ($koloms as $kolom) {
                $$kolom = $items->$kolom;

                $specs[$kolom] = $items->$kolom;
            }

            $scrDatas[$cabang_id][$extern_id] = $specs;
        }
        // arrPrint($scrDatas);

        // arrPrint($insertKoloms);
        $this->db->trans_begin();
        foreach ($scrDatas as $cbId => $scrData) {
            cekHitam($cbId);
            foreach ($scrData as $items) {
                $specs = array();
                foreach ($koloms as $kolom) {

                    $kolomIn = array_key_exists($kolom, $insertKoloms) ? $insertKoloms[$kolom] : $kolom;
                    $datas[$kolomIn] = $items[$kolom];
                }
                $datas["dtime"] = dtimeNow();
                $datas["fulldate"] = dtimeNow("Y-m-d");
                arrPrint($items);
                $allDatas = $datas + $items;
                // $datas[]
                // showLast_query("merah");
                arrPrint($allDatas);
                $com->insertTodayMoves("rek_pembantu_produk", $allDatas);
                showLast_query("lime");

            }

        }


        matiHere("belom commit");
        $this->db->trans_complete();
        // cekHitam($this->db->affected_rows());
    }

    public function insertSalesNama()
    {
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        // melihat so yg ada
        // $condite = $confDatas['condite'];
        $kolomSpo = array(
            "id",
            "oleh_id",
            "oleh_nama",
        );
        $condite = array(
            "jenis" => "582spo",
            "r_sales" => "0",
        );
        $tr->setFilters(array());
        $this->db->limit(100);
        $this->db->where($condite);
        $tmpSo = $tr->lookupAll()->result();
        showLast_query("lime");

        $saleses = array();
        foreach ($tmpSo as $datas) {
            foreach ($kolomSpo as $kolomItem) {
                $$kolomItem = $datas->$kolomItem;

                // cekLime($$kolomItem);
            }

            $saleses[$id]["seller_id"] = $oleh_id;
            $saleses[$id]["seller_nama"] = $oleh_nama;
        }
        // arrPrint($saleses);
        $this->db->trans_begin();
        foreach ($saleses as $id_master => $new_datas) {

            $wheres = "id_master = '$id_master'";
            $tr->updateData($wheres, $new_datas);
            showLast_query("kuning");
            cekHijau("terdampak update " . $this->db->affected_rows());

            $wheres = "id = '$id'";
            $marks = array("r_sales" => "1");
            $tr->updateData($wheres, $marks);
            showLast_query("merah");
            cekHijau("terdampak update " . $this->db->affected_rows());
        }

        // matiHere(__LINE__);
        $this->db->trans_complete();

    }

    public function insertRekMutasiDaily()
    {
        cekBiru("STARTING " . date("Y-m-d H:i:s"));


        $this->load->helper("he_mass_table");
        $catException = $this->config->item('accountCatExceptions') != null ? $this->config->item('accountCatExceptions') : array();
        //        $arrRekening = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $arrRekening = $this->config->item("accountChilds2") != null ? $this->config->item("accountChilds2") : array();
        //        arrPrint($catException);
        //        arrPrint($arrRekening);


        $this->db->trans_start();

        // generator untuk rekening pembantu
        $pakai_pembantu = 1;
        if ($pakai_pembantu == 1) {
            if (sizeof($arrRekening) > 0) {
                foreach ($arrRekening as $rek => $tmpSpecs) {
                    foreach ($tmpSpecs as $comName) {
                        $result_cache = array();

                        $mdlName = "Com" . $comName;
                        $this->load->model("Coms/" . $mdlName);
                        $com = new $mdlName();
                        if (method_exists($com, "fetchBalances")) {
                            $tblNames = $com->getTableName();
                            $tblExist = $this->db->query("SHOW TABLES LIKE '" . $tblNames . "'")->result();
                            if (sizeof($tblExist) > 0) {
                                $result_cache = $com->fetchBalances($rek);
                            }
                        }
                        else {
                            mati_disini(":: $mdlName tidak memiliki method fetchBalances ::");
                        }

                        //                    mati_disini(":: UNDER MAINTENANCE ::");
                        cekBiru(":: cek dan insert ke cache, mutasi masing-masing ::");
                        if (sizeof($result_cache) > 0) {
                            foreach ($result_cache as $result_cacheSpec) {
                                $rek = $result_cacheSpec->rekening;

                                cekBiru(":: cek dan insert ke mutasi masing-masing ::");
                                $com = new $mdlName();
                                if (method_exists($com, "fetchMoves")) {
                                    $com->addFilter("fulldate='" . date("Y-m-d") . "'");
                                    $com->addFilter("cabang_id='" . $result_cacheSpec->cabang_id . "'");
                                    if ($result_cacheSpec->gudang_id == "") {
                                        $com->addFilter("gudang_id is NULL");
                                    }
                                    else {
                                        $com->addFilter("gudang_id='" . $result_cacheSpec->gudang_id . "'");
                                    }

                                    $tblNames = heReturnTableName($com->getTableNameMaster(), array($rek));
                                    $tblNames_mutasi = $tblNames[$rek]['mutasi'];

                                    $tblExist = $this->db->query("SHOW TABLES LIKE '" . $tblNames_mutasi . "'")->result();
                                    if (sizeof($tblExist) > 0) {

                                        $result_mutasi = $com->fetchMoves($rek, $result_cacheSpec->extern_id);
                                        cekBiru($this->db->last_query() . " -- " . sizeof($result_mutasi));

                                        if (sizeof($result_mutasi) == 0) {
                                            $datas = array(
                                                "rek_id" => $result_cacheSpec->rek_id,
                                                "rekening" => $result_cacheSpec->rekening,
                                                "extern_id" => $result_cacheSpec->extern_id,
                                                "extern_nama" => $result_cacheSpec->extern_nama,
                                                "cabang_id" => $result_cacheSpec->cabang_id,
                                                "gudang_id" => $result_cacheSpec->gudang_id,
                                                "gudang_nama" => isset($result_cacheSpec->gudang_nama) ? $result_cacheSpec->gudang_nama : "",

                                                "debet_awal" => $result_cacheSpec->debet,
                                                "qty_debet_awal" => $result_cacheSpec->qty_debet,
                                                "debet_akhir" => $result_cacheSpec->debet,
                                                "qty_debet_akhir" => $result_cacheSpec->qty_debet,

                                                "kredit_awal" => $result_cacheSpec->kredit,
                                                "qty_kredit_awal" => $result_cacheSpec->qty_kredit,
                                                "kredit_akhir" => $result_cacheSpec->kredit,
                                                "qty_kredit_akhir" => $result_cacheSpec->qty_kredit,

                                                "harga" => isset($result_cacheSpec->harga) ? $result_cacheSpec->harga : 0,
                                                "harga_avg" => isset($result_cacheSpec->harga_avg) ? $result_cacheSpec->harga_avg : 0,
                                                "harga_awal" => isset($result_cacheSpec->harga_awal) ? $result_cacheSpec->harga_awal : 0,

                                                "dtime" => dtimeNow(),
                                                "fulldate" => dtimeNow("Y-m-d"),
                                            );
                                            if (method_exists($com, "insertTodayMoves")) {
                                                $com->insertTodayMoves($rek, $datas);
                                                cekMerah($this->db->last_query() . " [" . $this->db->affected_rows() . "]");
                                            }
                                            else {
                                                mati_disini("method insertTodayMoves tidak ada di $mdlName");
                                            }
                                        }
                                    }
                                }
                                else {
                                    mati_disini("method fetchMoves tidak ada di $mdlName");
                                }

                                cekPink(":: cek dan insert ke cache masing-masing ::");
                                $com = new $mdlName();
                                $getPeriode = $com->getPeriode();
                                if (method_exists($com, "fetchBalancePeriode")) {
                                    foreach ($getPeriode as $periode) {
                                        $tgl = date("d");
                                        $bln = date("m");
                                        $thn = date("Y");
                                        $com->setFilters(array());
                                        switch ($periode) {
                                            case "harian":
                                                $com->addFilter("tgl='$tgl'");
                                                $com->addFilter("bln='$bln'");
                                                $com->addFilter("thn='$thn'");
                                                break;
                                            case "bulanan":
                                                $com->addFilter("bln='$bln'");
                                                $com->addFilter("thn='$thn'");
                                                break;
                                            case "tahunan":
                                                $com->addFilter("thn='$thn'");
                                                break;
                                            case "forever":
                                                break;
                                        }
                                        $com->addFilter("cabang_id='" . $result_cacheSpec->cabang_id . "'");
                                        if ($result_cacheSpec->gudang_id == "") {
                                            $com->addFilter("gudang_id is NULL");
                                        }
                                        else {
                                            $com->addFilter("gudang_id='" . $result_cacheSpec->gudang_id . "'");
                                        }


                                        $tblNames = $com->getTableName();
                                        cekPink("tabel -> $tblNames");
                                        $tblExist = $this->db->query("SHOW TABLES LIKE '" . $tblNames . "'")->result();
                                        if (sizeof($tblExist) > 0) {

                                            $result_cache = $com->fetchBalancePeriode($rek, $result_cacheSpec->extern_id, $periode);
                                            cekPink($this->db->last_query() . " -- " . sizeof($result_cache) . " -- " . $periode);
                                            //                                arrPrint($result_cache);
                                            if (sizeof($result_cache) == 0) {
                                                $rekCat = detectRekCategory($rek);
                                                if (!in_array($rekCat, $catException)) {
                                                    $debet = $result_cacheSpec->debet;
                                                    $qty_debet = $result_cacheSpec->qty_debet;
                                                    $kredit = $result_cacheSpec->kredit;
                                                    $qty_kredit = $result_cacheSpec->qty_kredit;
                                                }
                                                else {
                                                    $debet = 0;
                                                    $qty_debet = 0;
                                                    $kredit = 0;
                                                    $qty_kredit = 0;
                                                }
                                                $datas_cache = array(
                                                    "rek_id" => $result_cacheSpec->rek_id,
                                                    "rekening" => $result_cacheSpec->rekening,
                                                    "extern_id" => $result_cacheSpec->extern_id,
                                                    "extern_nama" => $result_cacheSpec->extern_nama,
                                                    "cabang_id" => $result_cacheSpec->cabang_id,
                                                    "gudang_id" => $result_cacheSpec->gudang_id,
                                                    "gudang_nama" => isset($result_cacheSpec->gudang_nama) ? $result_cacheSpec->gudang_nama : "",

                                                    "debet" => $debet,
                                                    "qty_debet" => $qty_debet,
                                                    "kredit" => $kredit,
                                                    "qty_kredit" => $qty_kredit,

                                                    //                                        "debet_awal" => $result_cacheSpec->debet,
                                                    //                                        "qty_debet_awal" => $result_cacheSpec->qty_debet,
                                                    //                                        "debet_akhir" => $result_cacheSpec->debet,
                                                    //                                        "qty_debet_akhir" => $result_cacheSpec->qty_debet,
                                                    //
                                                    //                                        "kredit_awal" => $result_cacheSpec->kredit,
                                                    //                                        "qty_kredit_awal" => $result_cacheSpec->qty_kredit,
                                                    //                                        "kredit_akhir" => $result_cacheSpec->kredit,
                                                    //                                        "qty_kredit_akhir" => $result_cacheSpec->qty_kredit,

                                                    "harga" => isset($result_cacheSpec->harga) ? $result_cacheSpec->harga : 0,
                                                    "harga_avg" => isset($result_cacheSpec->harga_avg) ? $result_cacheSpec->harga_avg : 0,
                                                    "harga_awal" => isset($result_cacheSpec->harga_awal) ? $result_cacheSpec->harga_awal : 0,

                                                    "dtime" => dtimeNow(),
                                                    "fulldate" => dtimeNow("Y-m-d"),
                                                    "thn" => dtimeNow("Y"),
                                                    "bln" => dtimeNow("m"),
                                                    "tgl" => dtimeNow("d"),
                                                    "periode" => $periode,
                                                );
                                                //                                    arrPrint($datas_cache);
                                                if (method_exists($com, "insertTodayBalances")) {
                                                    $com->insertTodayBalances($datas_cache);
                                                    cekPink2($this->db->last_query() . " [" . $this->db->affected_rows() . "]");
                                                }
                                                else {
                                                    mati_disini("method insertTodayBalances tidak ada di $mdlName");
                                                }
                                            }
                                        }

                                    }
                                }
                                else {
                                    mati_disini("method fetchBalancePeriode tidak ada di $mdlName");
                                }

                                //                            mati_disini();
                            }
                        }

                        //                    break;
                    }
                }
            }
        }
        cekHitam(":: REKENING PEMBANTU DONE ::");


        cekHitam(":: REKENING MAIN START ::");

        // generator untuk rekening main
        $pakai_main = 1;
        if ($pakai_main == 1) {
            $comName = "Rekening";
            $mdlName = "Com" . $comName;
            $this->load->model("Coms/" . $mdlName);

            $com = New $mdlName();
            $main_cache = $com->fetchAllBalances_all();
            if (sizeof($main_cache) > 0) {
                foreach ($main_cache as $mainSpec) {
                    $rek = $mainSpec->rekening;
                    $com = new $mdlName();
                    if (method_exists($com, "fetchMoves")) {
                        $com->addFilter("cabang_id='" . $mainSpec->cabang_id . "'");
                        //                            $com->addFilter("gudang_id='" . $result_cacheSpec->gudang_id . "'");
                        $com->addFilter("fulldate='" . date("Y-m-d") . "'");


                        $tblNames = heReturnTableName($com->getTableNameMaster(), array("$rek"));
                        $tblNames_mutasi = $tblNames[$rek]['mutasi'];

                        $tblExist = $this->db->query("SHOW TABLES LIKE '" . $tblNames_mutasi . "'")->result();


                        if (sizeof($tblExist) > 0) {

                            $result_mutasi = $com->fetchMoves($rek);
                            cekUngu($this->db->last_query() . " -- " . sizeof($result_mutasi));

                            if (sizeof($result_mutasi) == 0) {
                                $data_mutasi = array(
                                    "rek_id" => $mainSpec->rek_id,
                                    "rekening" => $mainSpec->rekening,
                                    //                            "extern_id" => $mainSpec->extern_id,
                                    //                            "extern_nama" => $mainSpec->extern_nama,
                                    "cabang_id" => $mainSpec->cabang_id,
                                    "gudang_id" => $mainSpec->gudang_id,
                                    "gudang_nama" => isset($mainSpec->gudang_nama) ? $mainSpec->gudang_nama : "",

                                    "debet_awal" => $mainSpec->debet,
                                    "qty_debet_awal" => $mainSpec->qty_debet,
                                    "debet_akhir" => $mainSpec->debet,
                                    "qty_debet_akhir" => $mainSpec->qty_debet,

                                    "kredit_awal" => $mainSpec->kredit,
                                    "qty_kredit_awal" => $mainSpec->qty_kredit,
                                    "kredit_akhir" => $mainSpec->kredit,
                                    "qty_kredit_akhir" => $mainSpec->qty_kredit,

                                    //                            "harga" => isset($mainSpec->harga) ? $mainSpec->harga : 0,
                                    //                            "harga_avg" => isset($mainSpec->harga_avg) ? $mainSpec->harga_avg : 0,

                                    "dtime" => dtimeNow(),
                                    "fulldate" => dtimeNow("Y-m-d"),
                                );
                                if (method_exists($com, "insertTodayMoves")) {

                                    $com->insertTodayMoves($rek, $data_mutasi);

                                    cekMerah($this->db->last_query() . " [" . $this->db->affected_rows() . "]");

                                }
                                else {
                                    mati_disini("method insertTodayMoves tidak ada di $mdlName");
                                }
                            }
                        }

                    }


                    $com = new $mdlName();
                    $getPeriode = $com->getPeriode();
                    if (method_exists($com, "fetchBalancePeriode")) {
                        foreach ($getPeriode as $periode) {
                            $tgl = date("d");
                            $bln = date("m");
                            $thn = date("Y");
                            $com->setFilters(array());
                            switch ($periode) {
                                case "harian":
                                    $com->addFilter("tgl='$tgl'");
                                    $com->addFilter("bln='$bln'");
                                    $com->addFilter("thn='$thn'");
                                    break;
                                case "bulanan":
                                    $com->addFilter("bln='$bln'");
                                    $com->addFilter("thn='$thn'");
                                    break;
                                case "tahunan":
                                    $com->addFilter("thn='$thn'");
                                    break;
                                case "forever":
                                    break;
                            }
                            $com->addFilter("cabang_id='" . $mainSpec->cabang_id . "'");

                            $tblNames = $com->getTableName();
                            cekPink("tabel -> $tblNames");
                            $tblExist = $this->db->query("SHOW TABLES LIKE '" . $tblNames . "'")->result();

                            if (sizeof($tblExist) > 0) {

                                $result_cache = $com->fetchBalancePeriode($rek, $periode);
                                cekOrange($this->db->last_query() . " -- " . sizeof($result_cache) . " -- " . $periode);

                                if (sizeof($result_cache) == 0) {
                                    $rekCat = detectRekCategory($rek);
                                    if (!in_array($rekCat, $catException)) {
                                        $debet = $mainSpec->debet;
                                        $qty_debet = $mainSpec->qty_debet;
                                        $kredit = $mainSpec->kredit;
                                        $qty_kredit = $mainSpec->qty_kredit;
                                    }
                                    else {
                                        $debet = 0;
                                        $qty_debet = 0;
                                        $kredit = 0;
                                        $qty_kredit = 0;
                                    }
                                    $datas_cache = array(
                                        "rek_id" => $mainSpec->rek_id,
                                        "rekening" => $mainSpec->rekening,
                                        //                                "extern_id" => $mainSpec->extern_id,
                                        //                                "extern_nama" => $mainSpec->extern_nama,
                                        "cabang_id" => $mainSpec->cabang_id,
                                        "gudang_id" => $mainSpec->gudang_id,
                                        "gudang_nama" => isset($mainSpec->gudang_nama) ? $mainSpec->gudang_nama : "",

                                        "debet" => $debet,
                                        "qty_debet" => $qty_debet,
                                        "kredit" => $kredit,
                                        "qty_kredit" => $qty_kredit,

                                        "dtime" => dtimeNow(),
                                        "fulldate" => dtimeNow("Y-m-d"),
                                        "thn" => dtimeNow("Y"),
                                        "bln" => dtimeNow("m"),
                                        "tgl" => dtimeNow("d"),
                                        "periode" => $periode,
                                    );
                                    if (method_exists($com, "insertTodayBalances")) {
                                        $com->insertTodayBalances($datas_cache);
                                        cekUngu($this->db->last_query() . " [" . $this->db->affected_rows() . "]");

                                    }
                                    else {
                                        mati_disini("method insertTodayBalances tidak ada di $mdlName");
                                    }
                                }
                            }

                        }
                    }
                    //                    mati_disini();
                }
            }
        }


        cekBiru("STOP " . date("Y-m-d H:i:s"));
        //        mati_disini("::: --UNDER MAINTENANCE-- :::");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau(":: DONE ::");
    }

    public function insertMovement()
    {
        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        // __rek_pembantu_produk__persediaan_produk
        $tbl_src = "__rek_pembantu_produk__persediaan_produk";
        $tbl_hr = "__rek_pembantu_produk__persediaan_produk_hr";
        $tbl_bl = "__rek_pembantu_produk__persediaan_produk_bl";
        $tbl_bl_external = "__rek_pembantu_produk__persediaan_produk_bl_external";
        $tbl_grouping_bl = "__rek_pembantu_produk__persediaan_produk_groupjenis_bl";

        $reset = "<a href='" . base_url() . "Cli/resetorMovement?tbl=$tbl_grouping_bl'>reset data</a>";

        // $srcJenis = array(
        //     "982", "582spd", "382spd"
        // );
        // $this->db->where_in("jenis", $srcJenis);

        $this->db->where("r_move='0'");
        $this->db->order_by("id", "ASC");
        $this->db->limit(1);
        $src = $this->db->get($tbl_src)->result();
        // echo $reset;
        showLast_query("kuning");
        if (sizeof($src) == 0) {


            matiHere("data habis " . __METHOD__ . __LINE__ . " | $reset");
        }
        else {
            // arrPrint($src);
            $src_datas = $src[0];
            $src_id = $src_datas->id;
            $src_extern_id = $src_datas->extern_id;
            $src_cabang_id = $src_datas->cabang_id;
            $src_gudang_id = $src_datas->gudang_id;
            $src_harga_awal = $src_datas->harga;
            $src_jenis = $src_datas->jenis;
            $src_dtime = $src_datas->dtime;
            $tanggal = formatTanggal($src_dtime, 'Y-m-d');
            $tg = formatTanggal($src_dtime, 'd') * 1;
            $mg = formatTanggal($src_dtime, 't') * 1;
            $bl = formatTanggal($src_dtime, 'm') * 1;
            $th = formatTanggal($src_dtime, 'Y') * 1;

            // $arrWhere = array(
            //     "extern_id" => $src_extern_id,
            //     "cabang_id" => $src_cabang_id,
            //     "gudang_id" => $src_gudang_id,
            //     "bln"       => $bl,
            //     "thn"       => $th,
            // );

            $confPeriode = array(
                // "hr" => array(
                //     "table"    => $tbl_hr,
                //     "condites" => array(
                //         "extern_id" => $src_extern_id,
                //         "cabang_id" => $src_cabang_id,
                //         "gudang_id" => $src_gudang_id,
                //         "tgl"       => $tg,
                //         "bln"       => $bl,
                //         "thn"       => $th,
                //     ),
                //     "jenis"    => array(
                //         "982", "582spd", "382spd"
                //     ),
                // ),
                "bl" => array(
                    "table" => $tbl_grouping_bl,
                    "condites" => array(
                        "extern_id" => $src_extern_id,
                        "cabang_id" => $src_cabang_id,
                        "gudang_id" => $src_gudang_id,
                        "bln" => $bl,
                        "thn" => $th,
                        "jenis" => $src_jenis,
                    ),
                ),
            );

            $this->db->trans_begin();

            foreach ($confPeriode as $periode => $perSpecs) {

                $tbl_bl = $perSpecs['table'];
                $arrWhere = $perSpecs['condites'];
                // $filterjenis = $perSpecs['jenis'];

                // $this->db->where_in($filterjenis);
                $this->db->where($arrWhere);
                $srcBl = $this->db->get($tbl_bl)->result();
                showLast_query("merah");// arrPrint(sizeof($srcBl));

                if (sizeof($srcBl) == 0) {
                    // insert
                    cekHijau("insert");
                    $arrBlock = array(
                        "id",
                        "r_move",
                    );
                    $moveDatas = array_diff_key((array)$src_datas, array_flip($arrBlock));
                    $moveDatas["tgl"] = $tg;
                    $moveDatas["bln"] = $bl;
                    $moveDatas["thn"] = $th;
                    $moveDatas["harga_awal"] = $src_harga_awal;
                    arrPrint($moveDatas);
                    $this->db->insert($tbl_bl, $moveDatas);
                    showLast_query("lime");
                }
                else {
                    // update
                    cekHijau("update");
                    $srcBl_datas = $srcBl[0];
                    $src_idbl = $srcBl_datas->id;

                    $debet_awal = $srcBl_datas->debet_awal;
                    $debet = $srcBl_datas->debet + $src_datas->debet;
                    $kredit = $srcBl_datas->kredit + $src_datas->kredit;
                    $debet_akhir = $debet_awal + $debet - $kredit;

                    $qty_debet_awal = $srcBl_datas->qty_debet_awal;
                    $qty_debet = $srcBl_datas->qty_debet + $src_datas->qty_debet;
                    $qty_kredit = $srcBl_datas->qty_kredit + $src_datas->qty_kredit;
                    $qty_debet_akhir = $qty_debet_awal + $qty_debet - $qty_kredit;

                    $harga_awal = $src_datas->harga * 1;
                    $harga = $src_datas->harga * 1;
                    // $harga_avg = $src_datas->harga_avg * 1;
                    if ($qty_debet_akhir > 0) {
                        $harga_avg = $debet_akhir / $qty_debet_akhir;
                    }
                    else {
                        $harga_avg = $harga;
                    }
                    // $harga_avg = $debet_akhir / $qty_debet_akhir ;
                    // cekKuning("$harga_avg = $debet_akhir / $qty_debet_akhir");
                    $this->db->where("id='$src_idbl'");
                    $newDatas = array(
                        // "debet_awal"  => $debet_awal,
                        "debet" => $debet,
                        "kredit" => $kredit,
                        "debet_akhir" => $debet_akhir,

                        // "qty_debet_awal"  => $qty_debet_awal,
                        "qty_debet" => $qty_debet,
                        "qty_kredit" => $qty_kredit,
                        "qty_debet_akhir" => $qty_debet_akhir,


                        // "harga_awal" => $harga_awal,
                        "harga" => $harga,
                        "harga_avg" => $harga_avg,
                    );
                    arrPrint($newDatas);
                    $this->db->update($tbl_bl, $newDatas);
                    showLast_query("biru");
                }
            }

            //region marking data
            $this->db->where("id='$src_id'");
            $newDatas = array(
                "r_move" => 1,
            );
            $this->db->update($tbl_src, $newDatas);
            showLast_query("hitam");
            //endregion

            // matiHere("belom commit bosss.....");
            $this->db->trans_complete();
        }

    }

    public function resetorMovement()
    {
        $tbl_src = "__rek_pembantu_produk__persediaan_produk";
        $tbl_hr = "__rek_pembantu_produk__persediaan_produk_hr";
        $tbl_bl = "__rek_pembantu_produk__persediaan_produk_bl";
        $tbl_bl_external = "__rek_pembantu_produk__persediaan_produk_bl_external";
        $tbl_grouping_bl = "__rek_pembantu_produk__persediaan_produk_groupjenis_bl";

        #truncate __rek_pembantu_produk__persediaan_produk_bl;
        #truncate __rek_pembantu_produk__persediaan_produk_bl_external;
        $this->db->trans_begin();
        $trcTbl = $_GET['tbl'];
        $this->db->truncate($trcTbl);
        showLast_query("merah");

        $newDatas = array(
            "r_move" => 0,
        );
        $this->db->where("r_move", "1");
        $this->db->update($tbl_src, $newDatas);
        showLast_query("hitam");
        $jml = $this->db->affected_rows();
        cekHijau("data terdampak update :: $jml");

        // matiHere("belom commit refto::" . url_referer());
        $this->db->trans_complete();
        redirect(url_referer());
    }

    public function exeBiNotifikasi()
    {
        $arrJenis = array(
            "bi_pembelian_produk"
        );

        $this->load->model("Mdls/MdlBi");
        $bpp = new MdlBi();
        $em = new SmtpMailer();
        $tgl_now = dtimeNow("Y-m-d");
        $jam_now = dtimeNow("H:m");
        $jam_now = "20:00";
        $tglNowStamp = dtimeToSecond($tgl_now . " " . $jam_now);
        $tmp_0 = $bpp->lookupBiPenjualanProduk()->result();
        showLast_query("kuning");
        // arrPrint($tmp_0->result());
        $biSettings = array();
        foreach ($tmp_0 as $items) {
            $biSettings[$items->nama] = $items->nilai;
        }

        $setDtime = $biSettings["exe_tgl"];
        $setjam = $biSettings["exe_jam"];
        $setEmail = $biSettings['exe_email'];

        $setStamp = dtimeToSecond($setDtime . " " . $setjam);

        arrPrint($biSettings);
        cekOrange("$setDtime $setStamp $setjam");
        cekHijau("$tgl_now $tglNowStamp  $jam_now");
        //
        if ($tglNowStamp == $setStamp) {

            /* ---------------------------------------------
             * menyusun data untuk dikirim via email
             * ------------------------------------------*/
            // region
            $emBody = "";
            // endregion

            //region pengirim email
            $em->setAddressFrom("noreply.mgkcore@gmail.com");
            $em->setAddressTo($setEmail);
            $em->setSubject("notif BI");
            $kirim = $em->kirim_email("testing");
            //endregion
            cekMerah($kirim);

        }
        //
    }

    public function exeFifoCutoff()
    {
        $this->load->model("Mdls/MdlFifoProdukJadi");
        $ff = new MdlFifoProdukJadi();
        $this->db->trans_begin();
        $ff->moveToTrash();

        if (PHP_SAPI != "cli") {
            matiHere("belom commit");
        }

        $this->db->trans_complete();
    }


    //-----UNTUK GENERATE LAPORAN PEMBELIAN--------------------------
    public function genPembelianProduk()
    {
//        header("refresh:5");


        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        $jenis = "pembelian_produk";


        $jenis_list = implode("','", $reportJenis[$jenis]);


        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlReport");
        $tr = new MdlTransaksi();

        // region membaca 1 data
        $tr->setFilters(array());
        $this->db->order_by("id");
        $this->db->limit(1);
        $tr->addFilter("id_master>'0'");
        $tr->addFilter("r_jenis='0'");
        $this->db->where("jenis in ('" . $jenis_list . "')");
        $scr = $tr->lookupAll()->result();
        cekHijau($this->db->last_query());
        sizeof($scr) == 0 ? matiHere(__LINE__ . __METHOD__ . " source data sudah habis") : "";
        $scr_0 = $scr[0];
        $transaksi_id = $scr_0->id;
        $transaksi_nomer = $scr_0->nomer;
        $transaksi_jenis = $scr_0->jenis;
        $id_master = $scr_0->id_master;
        $dtime = $transaksi_dtime = $scr_0->dtime;
        $index_registry = $scr_0->indexing_registry;

        cekPink2("transaksi jenis: $transaksi_jenis, id: $transaksi_id");

        $tanggal = formatTanggal($dtime, 'Y-m-d');
        $tg = formatTanggal($dtime, 'd') * 1;
        $mg = formatTanggal($dtime, 'W') * 1;
        $bl = formatTanggal($dtime, 'm') * 1;
        $th = formatTanggal($dtime, 'Y') * 1;
        // endregion membaca 1 data


        // region membaca data registry

        //region ITEMS
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "label",
            "jml",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "sub_hpp_nppn",
            //---------
            "hpp_nppn",
            "hpp_nppv",
            "ppv",
            "harga",
            //---------
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",

        );
        $tr->setFilters(array());
//        if ($index_registry != NULL) {
//            $index_registry_decode = blobDecode($index_registry);
//            $arrRegIDs = array(
//                "main" => $index_registry_decode['main'],
//                "items" => $index_registry_decode['items'],
//            );
////            $tr->addFilter("id='" . $index_registry_decode['items'] . "'");
//            $tr->addFilter("id in ('" . implode("','", $arrRegIDs) . "')");
//            $regs = $tr->lookupRegistries()->result();
//        }
//        else {
//            $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
//            $tr->addFilter("param in ('main', 'items')");
//            $regs = $tr->lookupRegistries()->result();
//        }
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
        $tr->setJointSelectFields("main, items, transaksi_id");
        $regs = $tr->lookupDataRegistries()->result();
        cekLime($this->db->last_query());
        //endregion
//mati_disini();

        $main_hpp_nppn = 0;
        $item_hpp_nppn = 0;

        $datas = array();
        $regDatas = array();
        foreach ($regs as $reg) {
            foreach($reg as $key_reg => $val_reg){
                if($key_reg != "transaksi_id"){
                    $trId = $reg->transaksi_id;
                    if ($key_reg == "items") {
                        $regValues = blobDecode($val_reg);
                        foreach ($regValues as $regValue) {
                            foreach ($itemKoloms as $itemKolom) {
                                $$itemKolom = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;
                                if (isset($regValue[$itemKolom])) {

                                    $specs[$itemKolom] = $regValue[$itemKolom];
                                }
                                else {

                                    $specs[$itemKolom] = isset($specMains[$itemKolom]) ? $specMains[$itemKolom] : 0;
                                }
                            }
                            $datas[] = $specs;

                            $item_hpp_nppn += ($regValue['hpp_nppn'] * $regValue['jml']);
                        }
                    }
                    else {
                        $regValues = blobDecode($val_reg);
                        $main_hpp_nppn = $regValues['hpp_nppn'];
//                arrPrintWebs($regValues);
                    }
                }
            }


        }
        // endregion membaca 1 data


        if ($main_hpp_nppn != $item_hpp_nppn) {
            mati_disini("main dan items tidak cocok");
        }


        $dbReport = new MdlReport();

        // membaca tabel __cli_laporan sudah ada atau belum
        $cek = $dbReport->lookUpCliReportID($transaksi_id);

        if ($cek == 1) {
            // region marking data transaksi yg sudah masuk database report
            $tr->setFilters(array());
            $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));
            cekHere($this->db->last_query());

            // endregion marking data transaksi yg sudah masuk database report

            mati_disini("transaksi $transaksi_id sudah masuk ke databse report.");
        }
        else {

        }

//mati_disini("-----  -----");
        $dbReport->setStartCommit();


        $total_hpp_nppn = 0;
        $cek = 0;
        foreach ($datas as $data) {

            $cek++;
            foreach ($itemKoloms as $itemKolom) {
//                cekHere("$itemKolom => " . $data[$itemKolom]);
                $$itemKolom = $data[$itemKolom];
            }


            $label = (($label == null) || ($label == 0)) ? "-" : $label;
            $sub_hpp_nppn = $hpp_nppn * $jml;
            $sub_harga = $harga * $jml;
            $sub_hpp_nppv = $hpp_nppv * $jml;
            $sub_ppv = $ppv * $jml;
            $pihakName = htmlspecialchars($pihakName);
            $nama = htmlspecialchars($nama);


            $total_hpp_nppn += $sub_hpp_nppn;

            switch ($transaksi_jenis) {
                case "467":

                    $newDatas = array(
//                        "subject_id" => $id,
//                        "subject_nama" => $nama,
//                        "subject_kode" => $produk_kode,
//                        "subject_label" => $label,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => $sub_harga,
                        "nilai_af" => $sub_harga,

                        "nilai_nppn_ot" => 0,
                        "nilai_nppn_in" => $sub_hpp_nppn,
                        "nilai_nppn_af" => $sub_hpp_nppn,

                        "nilai_nppv_ot" => 0,
                        "nilai_nppv_in" => $sub_hpp_nppv,
                        "nilai_nppv_af" => $sub_hpp_nppv,

                        "nilai_ppv_ot" => 0,
                        "nilai_ppv_in" => $sub_ppv,
                        "nilai_ppv_af" => $sub_ppv,
                        // ------------------

                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_Produk = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
//                        "subject_kode" => $produk_kode,
//                        "subject_label" => $label,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => $sub_harga,
                        "nilai_af" => $sub_harga,

                        "nilai_nppn_ot" => 0,
                        "nilai_nppn_in" => $sub_hpp_nppn,
                        "nilai_nppn_af" => $sub_hpp_nppn,

                        "nilai_nppv_ot" => 0,
                        "nilai_nppv_in" => $sub_hpp_nppv,
                        "nilai_nppv_af" => $sub_hpp_nppv,

                        "nilai_ppv_ot" => 0,
                        "nilai_ppv_in" => $sub_ppv,
                        "nilai_ppv_af" => $sub_ppv,
                        // ------------------

                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_ProdukSupplier = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
//                        "subject_kode" => $produk_kode,
//                        "subject_label" => $label,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
//                        "object_kode" => 0,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => $sub_harga,
                        "nilai_af" => $sub_harga,

                        "nilai_nppn_ot" => 0,
                        "nilai_nppn_in" => $sub_hpp_nppn,
                        "nilai_nppn_af" => $sub_hpp_nppn,

                        "nilai_nppv_ot" => 0,
                        "nilai_nppv_in" => $sub_hpp_nppv,
                        "nilai_nppv_af" => $sub_hpp_nppv,

                        "nilai_ppv_ot" => 0,
                        "nilai_ppv_in" => $sub_ppv,
                        "nilai_ppv_af" => $sub_ppv,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_Supplier = array(
//                    "subject_id" => $id,
//                    "subject_nama" => $nama,
//                    "subject_kode" => $produk_kode,
//                    "subject_label" => $label,
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
//                        "subject_kode" => 0,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => $sub_harga,
                        "nilai_af" => $sub_harga,

                        "nilai_nppn_ot" => 0,
                        "nilai_nppn_in" => $sub_hpp_nppn,
                        "nilai_nppn_af" => $sub_hpp_nppn,

                        "nilai_nppv_ot" => 0,
                        "nilai_nppv_in" => $sub_hpp_nppv,
                        "nilai_nppv_af" => $sub_hpp_nppv,

                        "nilai_ppv_ot" => 0,
                        "nilai_ppv_in" => $sub_ppv,
                        "nilai_ppv_af" => $sub_ppv,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_SupplierProduk = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
//                        "subject_kode" => 0,
                        "object_id" => $id,
                        "object_nama" => $nama,
//                        "object_kode" => $produk_kode,
//                        "object_label" => $label,

                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => $sub_harga,
                        "nilai_af" => $sub_harga,

                        "nilai_nppn_ot" => 0,
                        "nilai_nppn_in" => $sub_hpp_nppn,
                        "nilai_nppn_af" => $sub_hpp_nppn,

                        "nilai_nppv_ot" => 0,
                        "nilai_nppv_in" => $sub_hpp_nppv,
                        "nilai_nppv_af" => $sub_hpp_nppv,

                        "nilai_ppv_ot" => 0,
                        "nilai_ppv_in" => $sub_ppv,
                        "nilai_ppv_af" => $sub_ppv,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );

                    break;

                case "967":

                    $newDatas = array(
//                        "subject_id" => $id,
//                        "subject_nama" => $nama,
//                        "subject_kode" => $produk_kode,
//                        "subject_label" => $label,

                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => $sub_harga,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_harga) * -1,

                        "nilai_nppn_ot" => $sub_hpp_nppn,
                        "nilai_nppn_in" => 0,
                        "nilai_nppn_af" => $sub_hpp_nppn * -1,

                        "nilai_nppv_ot" => $sub_hpp_nppv,
                        "nilai_nppv_in" => 0,
                        "nilai_nppv_af" => $sub_hpp_nppv * -1,

                        "nilai_ppv_ot" => $sub_ppv,
                        "nilai_ppv_in" => 0,
                        "nilai_ppv_af" => $sub_ppv * -1,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_Produk = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
//                        "subject_kode" => $produk_kode,
//                        "subject_label" => $label,

                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => $sub_harga,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_harga) * -1,

                        "nilai_nppn_ot" => $sub_hpp_nppn,
                        "nilai_nppn_in" => 0,
                        "nilai_nppn_af" => $sub_hpp_nppn * -1,

                        "nilai_nppv_ot" => $sub_hpp_nppv,
                        "nilai_nppv_in" => 0,
                        "nilai_nppv_af" => $sub_hpp_nppv * -1,

                        "nilai_ppv_ot" => $sub_ppv,
                        "nilai_ppv_in" => 0,
                        "nilai_ppv_af" => $sub_ppv * -1,
                        // ------------------

                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_ProdukSupplier = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
//                        "subject_kode" => $produk_kode,
//                        "subject_label" => $label,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
//                        "object_kode" => 0,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => $sub_harga,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_harga) * -1,

                        "nilai_nppn_ot" => $sub_hpp_nppn,
                        "nilai_nppn_in" => 0,
                        "nilai_nppn_af" => $sub_hpp_nppn * -1,

                        "nilai_nppv_ot" => $sub_hpp_nppv,
                        "nilai_nppv_in" => 0,
                        "nilai_nppv_af" => $sub_hpp_nppv * -1,

                        "nilai_ppv_ot" => $sub_ppv,
                        "nilai_ppv_in" => 0,
                        "nilai_ppv_af" => $sub_ppv * -1,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_Supplier = array(
//                    "subject_id" => $id,
//                    "subject_nama" => $nama,
//                    "subject_kode" => $produk_kode,
//                    "subject_label" => $label,
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
//                        "subject_kode" => 0,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => $sub_harga,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_harga) * -1,

                        "nilai_nppn_ot" => $sub_hpp_nppn,
                        "nilai_nppn_in" => 0,
                        "nilai_nppn_af" => $sub_hpp_nppn * -1,

                        "nilai_nppv_ot" => $sub_hpp_nppv,
                        "nilai_nppv_in" => 0,
                        "nilai_nppv_af" => $sub_hpp_nppv * -1,

                        "nilai_ppv_ot" => $sub_ppv,
                        "nilai_ppv_in" => 0,
                        "nilai_ppv_af" => $sub_ppv * -1,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_SupplierProduk = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
//                        "subject_kode" => 0,
                        "object_id" => $id,
                        "object_nama" => $nama,
//                        "object_kode" => $produk_kode,
//                        "object_label" => $label,

                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => $sub_harga,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_harga) * -1,

                        "nilai_nppn_ot" => $sub_hpp_nppn,
                        "nilai_nppn_in" => 0,
                        "nilai_nppn_af" => $sub_hpp_nppn * -1,

                        "nilai_nppv_ot" => $sub_hpp_nppv,
                        "nilai_nppv_in" => 0,
                        "nilai_nppv_af" => $sub_hpp_nppv * -1,

                        "nilai_ppv_ot" => $sub_ppv,
                        "nilai_ppv_in" => 0,
                        "nilai_ppv_af" => $sub_ppv * -1,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );

                    break;
                default:
                    matiHere(__METHOD__ . " @" . __LINE__);
                    break;
            }


            $dbReport->setJenis($jenis);
            $dbReport->setOrder("id DESC");
            $dbReport->setLimit("1");

            $dbReport->setDebug(false);

            //region harian
            $periode = "harian";
            $dbReport->setPeriode($periode);
            $dbReport->setTanggal($tanggal);

            $dbReport->setDatas($newDatas);
            $dbReport->setDataProduk($newDatas_Produk);
            $dbReport->setDataProdukSupplier($newDatas_ProdukSupplier);
            $dbReport->setDataSupplier($newDatas_Supplier);
            $dbReport->setDataSupplierProduk($newDatas_SupplierProduk);

            // -------------------------------------

            $dbReport->writePembelian();
            $dbReport->writePembelianSupplier($pihakID);// penjualan customer
            $dbReport->writePembelianSupplierProduk($placeID, $pihakID, $id);// penjualan customer cabang
            $dbReport->writePembelianProduk($id);// penjualan customer seller
            $dbReport->writePembelianProdukSupplier($placeID, $id, $pihakID);// penjualan customer produk

            // endregion

//            mati_disini("----MATI HARIAN----");

            // region mingguan
            $periode = "mingguan";
            $dbReport->setPeriode($periode);
            $dbReport->setMinggu($mg);
            $dbReport->setTahun($th);

            // -------------------------------------
            $dbReport->writePembelian();
            $dbReport->writePembelianSupplier($pihakID);// penjualan customer
            $dbReport->writePembelianSupplierProduk($placeID, $pihakID, $id);// penjualan customer cabang
            $dbReport->writePembelianProduk($id);// penjualan customer seller
            $dbReport->writePembelianProdukSupplier($placeID, $id, $pihakID);// penjualan customer produk

            // endregion mingguan

//            mati_disini("----MATI MINGGUAN----");

            // region bulanan
            $periode = "bulanan";
            $dbReport->setPeriode($periode);
            $dbReport->setBulan($bl);
            $dbReport->setTahun($th);

            // -------------------------------------
            $dbReport->writePembelian();
            $dbReport->writePembelianSupplier($pihakID);// penjualan customer
            $dbReport->writePembelianSupplierProduk($placeID, $pihakID, $id);// penjualan customer cabang
            $dbReport->writePembelianProduk($id);// penjualan customer seller
            $dbReport->writePembelianProdukSupplier($placeID, $id, $pihakID);// penjualan customer produk

            // endregion bulanan

//            mati_disini("----MATI BULANAN----");

            // region tahunan
            $periode = "tahunan";
            $dbReport->setPeriode($periode);
            $dbReport->setTahun($th);

            // -------------------------------------
            $dbReport->writePembelian();
            $dbReport->writePembelianSupplier($pihakID);// penjualan customer
            $dbReport->writePembelianSupplierProduk($placeID, $pihakID, $id);// penjualan customer cabang
            $dbReport->writePembelianProduk($id);// penjualan customer seller
            $dbReport->writePembelianProdukSupplier($placeID, $id, $pihakID);// penjualan customer produk

            // endregion tahunan

//            mati_disini("...STOP TAHUNAN...");
        }


        if ($total_hpp_nppn != $item_hpp_nppn) {
            mati_disini("main dan items tidak cocok");
        }


        $dbReport->setDebug(true);
        $tbl_cli = $dbReport->getTabelCli();
        $dbReport->setTableName($tbl_cli);
        $arrData = array(
            "transaksi_id" => $transaksi_id,
            "nomer" => $transaksi_nomer,
        );
        $dbReport->addData($arrData);


//        mati_disini(":: SETOP... :: trID -> $transaksi_id :: items: $total_hpp_nppn :: main: $main_hpp_nppn");

        $dbReport->setEndCommit() or matiHere("gagal ocmit");


        // region marking data transaksi yg sudah dibaca
        $tr->setFilters(array());
        $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));
        cekHere($this->db->last_query());

        // endregion marking data transaksi yg sudah dibaca


        cekHijau("<h1>- DONE -</h1>");

    }

    //-----UNTUK GENERATE LAPORAN PEMBELIAN SUPPLIES--------------------------
    public function genPembelianSupplies()
    {
//        header("refresh:5");


        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        $jenis = "pembelian_supplies";


        $jenis_list = implode("','", $reportJenis[$jenis]);


        $this->load->model("MdlTransaksi");
//        $this->load->model("Coms/ComJurnal");
        $this->load->model("Mdls/MdlReport");
        $tr = new MdlTransaksi();
//        $ju = new ComJurnal();


        // region membaca 1 data
        // $condite = "status = '1'";
        $tr->setFilters(array());
        $this->db->order_by("id");
        $this->db->limit(1);
//        $tr->addFilter("id='" . $jTransaksi_id . "'");
        $tr->addFilter("id_master>'0'");
        $tr->addFilter("r_jenis='0'");
        $this->db->where("jenis in ('" . $jenis_list . "')");
        $scr = $tr->lookupAll()->result();

        cekHijau($this->db->last_query());


        sizeof($scr) == 0 ? matiHere(__LINE__ . __METHOD__ . " source data sudah habis") : "";
        $scr_0 = $scr[0];

        $transaksi_id = $scr_0->id;
        $transaksi_nomer = $scr_0->nomer;
        $transaksi_jenis = $scr_0->jenis;
        $id_master = $scr_0->id_master;
        $dtime = $transaksi_dtime = $scr_0->dtime;
        $index_registry = $scr_0->indexing_registry;

        cekPink2("transaksi jenis: $transaksi_jenis, id: $transaksi_id");

        $tanggal = formatTanggal($dtime, 'Y-m-d');
        $tg = formatTanggal($dtime, 'd') * 1;
        $mg = formatTanggal($dtime, 'W') * 1;
        $bl = formatTanggal($dtime, 'm') * 1;
        $th = formatTanggal($dtime, 'Y') * 1;


        // endregion membaca 1 data


        // region membaca data registry

        // main
//        $itemKoloms = array(
//            "id",
//            "nama",
//            "produk_kode",
//            "jml",
//            "sub_nett1",
//            "sub_ppn",
//            "sub_nett2",
//            "pihakID",
//            "pihakName",
//            "placeID",
//            "placeName",
//            "gudangID",
//            "gudangName",
//            "olehID",
//            "olehName",
//        );
//        $tr->setFilters(array());
//        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
//        $tr->addFilter("param ='main'");
//        $regMains = $tr->lookupRegistries()->result();
//        cekLime($this->db->last_query() . " ::@ " . __LINE__);
//        $mainDatas = blobDecode($regMains[0]->values);
//        // arrPrintWebs($mainDatas);
//        if ((isset($mainDatas['shippingService'])) && ($mainDatas['shippingService'] == "ongkir_ppn_by_cust")) {
//            $specMains['ongkir_net'] = $mainDatas['ongkir_net'];
//        }
//        else {
//            $specMains['ongkir_net'] = 0;
//        }

        // $datas[] = $specMains;

        // item
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "label",
            "jml",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "sub_hpp_nppn",
            //---------
            "hpp_nppn",
            "hpp_nppv",
            "ppv",
            "harga",
            //---------
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",

        );
        $tr->setFilters(array());
//        if ($index_registry != NULL) {
//            $index_registry_decode = blobDecode($index_registry);
//            $tr->addFilter("id='" . $index_registry_decode['items'] . "'");
//            $regs = $tr->lookupRegistries()->result();
//        }
//        else {
//            $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
//            $tr->addFilter("param ='items'");
//            $regs = $tr->lookupRegistries()->result();
//        }
        $tr->setJointSelectFields("items, transaksi_id");
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
//        $tr->addFilter("param ='items'");
        $regs = $tr->lookupDataRegistries()->result();
        cekLime($this->db->last_query());


        $datas = array();
        $regDatas = array();
//        $sumNett1 = 0;
        foreach ($regs as $reg) {
            $trId = $reg->transaksi_id;
            $regValues = blobDecode($reg->items);
            foreach ($regValues as $regValue) {
                foreach ($itemKoloms as $itemKolom) {
                    $$itemKolom = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;

                    if (isset($regValue[$itemKolom])) {

                        $specs[$itemKolom] = $regValue[$itemKolom];
                    }
                    else {

                        $specs[$itemKolom] = isset($specMains[$itemKolom]) ? $specMains[$itemKolom] : 0;
                    }
                }
//                isset($sumNett1) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                $datas[] = $specs;

            }

        }
        // endregion membaca 1 data

//mati_disini("-------");
        $dbReport = new MdlReport();

        // membaca tabel __cli_laporan sudah ada atau belum
        $cek = $dbReport->lookUpCliReportID($transaksi_id);

        if ($cek == 1) {
            // region marking data transaksi yg sudah masuk database report
            $tr->setFilters(array());
            $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));
            cekHere($this->db->last_query());

            // endregion marking data transaksi yg sudah masuk database report

            mati_disini("transaksi $transaksi_id sudah masuk ke databse report.");
        }
        else {

        }


        $dbReport->setStartCommit();


        $cek = 0;
        foreach ($datas as $data) {

            $cek++;
            foreach ($itemKoloms as $itemKolom) {
//                cekHere("$itemKolom => " . $data[$itemKolom]);
                $$itemKolom = $data[$itemKolom];
            }


            $label = (($label == null) || ($label == 0)) ? "-" : $label;
            $sub_hpp_nppn = $hpp_nppn * $jml;
            $sub_harga = $harga * $jml;
            $sub_hpp_nppv = $hpp_nppv * $jml;
            $sub_ppv = $ppv * $jml;

            switch ($transaksi_jenis) {
                case "461":

                    $newDatas = array(
//                        "subject_id" => $id,
//                        "subject_nama" => $nama,
//                        "subject_kode" => $produk_kode,
//                        "subject_label" => $label,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => $sub_harga,
                        "nilai_af" => $sub_harga,

                        "nilai_nppn_ot" => 0,
                        "nilai_nppn_in" => $sub_hpp_nppn,
                        "nilai_nppn_af" => $sub_hpp_nppn,

                        "nilai_nppv_ot" => 0,
                        "nilai_nppv_in" => $sub_hpp_nppv,
                        "nilai_nppv_af" => $sub_hpp_nppv,

                        "nilai_ppv_ot" => 0,
                        "nilai_ppv_in" => $sub_ppv,
                        "nilai_ppv_af" => $sub_ppv,
                        // ------------------

                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_Produk = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
//                        "subject_kode" => $produk_kode,
//                        "subject_label" => $label,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => $sub_harga,
                        "nilai_af" => $sub_harga,

                        "nilai_nppn_ot" => 0,
                        "nilai_nppn_in" => $sub_hpp_nppn,
                        "nilai_nppn_af" => $sub_hpp_nppn,

                        "nilai_nppv_ot" => 0,
                        "nilai_nppv_in" => $sub_hpp_nppv,
                        "nilai_nppv_af" => $sub_hpp_nppv,

                        "nilai_ppv_ot" => 0,
                        "nilai_ppv_in" => $sub_ppv,
                        "nilai_ppv_af" => $sub_ppv,
                        // ------------------

                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_ProdukSupplier = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
//                        "subject_kode" => $produk_kode,
//                        "subject_label" => $label,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
//                        "object_kode" => 0,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => $sub_harga,
                        "nilai_af" => $sub_harga,

                        "nilai_nppn_ot" => 0,
                        "nilai_nppn_in" => $sub_hpp_nppn,
                        "nilai_nppn_af" => $sub_hpp_nppn,

                        "nilai_nppv_ot" => 0,
                        "nilai_nppv_in" => $sub_hpp_nppv,
                        "nilai_nppv_af" => $sub_hpp_nppv,

                        "nilai_ppv_ot" => 0,
                        "nilai_ppv_in" => $sub_ppv,
                        "nilai_ppv_af" => $sub_ppv,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_Supplier = array(
//                    "subject_id" => $id,
//                    "subject_nama" => $nama,
//                    "subject_kode" => $produk_kode,
//                    "subject_label" => $label,
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
//                        "subject_kode" => 0,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => $sub_harga,
                        "nilai_af" => $sub_harga,

                        "nilai_nppn_ot" => 0,
                        "nilai_nppn_in" => $sub_hpp_nppn,
                        "nilai_nppn_af" => $sub_hpp_nppn,

                        "nilai_nppv_ot" => 0,
                        "nilai_nppv_in" => $sub_hpp_nppv,
                        "nilai_nppv_af" => $sub_hpp_nppv,

                        "nilai_ppv_ot" => 0,
                        "nilai_ppv_in" => $sub_ppv,
                        "nilai_ppv_af" => $sub_ppv,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_SupplierProduk = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
//                        "subject_kode" => 0,
                        "object_id" => $id,
                        "object_nama" => $nama,
//                        "object_kode" => $produk_kode,
//                        "object_label" => $label,

                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => $sub_harga,
                        "nilai_af" => $sub_harga,

                        "nilai_nppn_ot" => 0,
                        "nilai_nppn_in" => $sub_hpp_nppn,
                        "nilai_nppn_af" => $sub_hpp_nppn,

                        "nilai_nppv_ot" => 0,
                        "nilai_nppv_in" => $sub_hpp_nppv,
                        "nilai_nppv_af" => $sub_hpp_nppv,

                        "nilai_ppv_ot" => 0,
                        "nilai_ppv_in" => $sub_ppv,
                        "nilai_ppv_af" => $sub_ppv,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );

                    break;

                case "961":

                    $newDatas = array(
//                        "subject_id" => $id,
//                        "subject_nama" => $nama,
//                        "subject_kode" => $produk_kode,
//                        "subject_label" => $label,

                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => $sub_harga,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_harga) * -1,

                        "nilai_nppn_ot" => $sub_hpp_nppn,
                        "nilai_nppn_in" => 0,
                        "nilai_nppn_af" => $sub_hpp_nppn * -1,

                        "nilai_nppv_ot" => $sub_hpp_nppv,
                        "nilai_nppv_in" => 0,
                        "nilai_nppv_af" => $sub_hpp_nppv * -1,

                        "nilai_ppv_ot" => $sub_ppv,
                        "nilai_ppv_in" => 0,
                        "nilai_ppv_af" => $sub_ppv * -1,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_Produk = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
//                        "subject_kode" => $produk_kode,
//                        "subject_label" => $label,

                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => $sub_harga,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_harga) * -1,

                        "nilai_nppn_ot" => $sub_hpp_nppn,
                        "nilai_nppn_in" => 0,
                        "nilai_nppn_af" => $sub_hpp_nppn * -1,

                        "nilai_nppv_ot" => $sub_hpp_nppv,
                        "nilai_nppv_in" => 0,
                        "nilai_nppv_af" => $sub_hpp_nppv * -1,

                        "nilai_ppv_ot" => $sub_ppv,
                        "nilai_ppv_in" => 0,
                        "nilai_ppv_af" => $sub_ppv * -1,
                        // ------------------

                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_ProdukSupplier = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
//                        "subject_kode" => $produk_kode,
//                        "subject_label" => $label,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
//                        "object_kode" => 0,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => $sub_harga,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_harga) * -1,

                        "nilai_nppn_ot" => $sub_hpp_nppn,
                        "nilai_nppn_in" => 0,
                        "nilai_nppn_af" => $sub_hpp_nppn * -1,

                        "nilai_nppv_ot" => $sub_hpp_nppv,
                        "nilai_nppv_in" => 0,
                        "nilai_nppv_af" => $sub_hpp_nppv * -1,

                        "nilai_ppv_ot" => $sub_ppv,
                        "nilai_ppv_in" => 0,
                        "nilai_ppv_af" => $sub_ppv * -1,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_Supplier = array(
//                    "subject_id" => $id,
//                    "subject_nama" => $nama,
//                    "subject_kode" => $produk_kode,
//                    "subject_label" => $label,
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
//                        "subject_kode" => 0,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => $sub_harga,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_harga) * -1,

                        "nilai_nppn_ot" => $sub_hpp_nppn,
                        "nilai_nppn_in" => 0,
                        "nilai_nppn_af" => $sub_hpp_nppn * -1,

                        "nilai_nppv_ot" => $sub_hpp_nppv,
                        "nilai_nppv_in" => 0,
                        "nilai_nppv_af" => $sub_hpp_nppv * -1,

                        "nilai_ppv_ot" => $sub_ppv,
                        "nilai_ppv_in" => 0,
                        "nilai_ppv_af" => $sub_ppv * -1,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );
                    $newDatas_SupplierProduk = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
//                        "subject_kode" => 0,
                        "object_id" => $id,
                        "object_nama" => $nama,
//                        "object_kode" => $produk_kode,
//                        "object_label" => $label,

                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => $sub_harga,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_harga) * -1,

                        "nilai_nppn_ot" => $sub_hpp_nppn,
                        "nilai_nppn_in" => 0,
                        "nilai_nppn_af" => $sub_hpp_nppn * -1,

                        "nilai_nppv_ot" => $sub_hpp_nppv,
                        "nilai_nppv_in" => 0,
                        "nilai_nppv_af" => $sub_hpp_nppv * -1,

                        "nilai_ppv_ot" => $sub_ppv,
                        "nilai_ppv_in" => 0,
                        "nilai_ppv_af" => $sub_ppv * -1,
                        // ------------------
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,

                        "mongo" => 0,
                    );

                    break;

                default:
                    matiHere(__METHOD__ . " @" . __LINE__);
                    break;
            }


            $dbReport->setJenis($jenis);
            $dbReport->setOrder("id DESC");
            $dbReport->setLimit("1");

            $dbReport->setDebug(true);

            //region harian
            $periode = "harian";
            $dbReport->setPeriode($periode);
            $dbReport->setTanggal($tanggal);

//            $dbReport->setDatas($newDatas);
            $dbReport->setDataSupplies($newDatas_Produk);
            $dbReport->setDataSuppliesSupplier($newDatas_ProdukSupplier);
//            $dbReport->setDataSupplier($newDatas_Supplier);
            $dbReport->setDataSupplierSupplies($newDatas_SupplierProduk);

            // -------------------------------------

//            $dbReport->writePembelian();
//            $dbReport->writePembelianSupplier($pihakID);// penjualan customer
            $dbReport->writePembelianSupplierSupplies($placeID, $pihakID, $id);// penjualan customer cabang
            $dbReport->writePembelianSupplies($id);// penjualan customer seller
            $dbReport->writePembelianSuppliesSupplier($placeID, $id, $pihakID);// penjualan customer produk

            // endregion

//            mati_disini("----MATI HARIAN----");

            // region mingguan
            $periode = "mingguan";
            $dbReport->setPeriode($periode);
            $dbReport->setMinggu($mg);
            $dbReport->setTahun($th);

            // -------------------------------------
//            $dbReport->writePembelian();
//            $dbReport->writePembelianSupplier($pihakID);// penjualan customer
            $dbReport->writePembelianSupplierSupplies($placeID, $pihakID, $id);// penjualan customer cabang
            $dbReport->writePembelianSupplies($id);// penjualan customer seller
            $dbReport->writePembelianSuppliesSupplier($placeID, $id, $pihakID);// penjualan customer produk

            // endregion mingguan

//            mati_disini("----MATI MINGGUAN----");

            // region bulanan
            $periode = "bulanan";
            $dbReport->setPeriode($periode);
            $dbReport->setBulan($bl);
            $dbReport->setTahun($th);

            // -------------------------------------
//            $dbReport->writePembelian();
//            $dbReport->writePembelianSupplier($pihakID);// penjualan customer
            $dbReport->writePembelianSupplierSupplies($placeID, $pihakID, $id);// penjualan customer cabang
            $dbReport->writePembelianSupplies($id);// penjualan customer seller
            $dbReport->writePembelianSuppliesSupplier($placeID, $id, $pihakID);// penjualan customer produk

            // endregion bulanan

//            mati_disini("----MATI BULANAN----");

            // region tahunan
            $periode = "tahunan";
            $dbReport->setPeriode($periode);
            $dbReport->setTahun($th);

            // -------------------------------------
//            $dbReport->writePembelian();
//            $dbReport->writePembelianSupplier($pihakID);// penjualan customer
            $dbReport->writePembelianSupplierSupplies($placeID, $pihakID, $id);// penjualan customer cabang
            $dbReport->writePembelianSupplies($id);// penjualan customer seller
            $dbReport->writePembelianSuppliesSupplier($placeID, $id, $pihakID);// penjualan customer produk

            // endregion tahunan

//            mati_disini("...STOP TAHUNAN...");
        }


        $dbReport->setDebug(true);
        $tbl_cli = $dbReport->getTabelCli();
        $dbReport->setTableName($tbl_cli);
        $arrData = array(
            "transaksi_id" => $transaksi_id,
            "nomer" => $transaksi_nomer,
        );
        $dbReport->addData($arrData);


//        mati_disini(":: SETOP... :: trID -> $transaksi_id ::");

        $dbReport->setEndCommit() or matiHere("gagal ocmit");


        // region marking data transaksi yg sudah dibaca
        $tr->setFilters(array());
        $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));
        cekHere($this->db->last_query());

        // endregion marking data transaksi yg sudah dibaca

        cekHijau("<h1>- DONE -</h1>");

    }

    //-----UNTUK GENERATE LAPORAN PENJUALAN--------------------------
    public function genPenjualan()
    {
//        header("refresh:5");


        isset($_GET['r']) && $_GET['r'] > 0 ? header("Refresh:" . $_GET['r']) : "";
        $reportJenis = $this->reportJenis;
        $jenis = "penjualan";
        // $jenis = key($reportJenis);
        // cekMerah("$jenis");
        $jenis_list = implode("','", $reportJenis[$jenis]);
        // cekHere($jenis." ".$jenis_list);
        //         matiHere(__METHOD__ ." @". __LINE__);
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComJurnal");
        $this->load->model("Mdls/MdlReport");
        $tr = new MdlTransaksi();
        $ju = new ComJurnal();

        $this->db->limit(1);
        $this->db->where("jenis in('" . $jenis_list . "')");
        $this->db->where("rekening in('penjualan','return penjualan')");
        // $ju->addFilter("rekening in('penjualan','return penjualan')");
        $ju->addFilter("r_jenis='0'");
        $ju->setSortBy(array("kolom" => "id", "mode" => "asc"));
        $scrJurnal = $ju->lookupAll()->result();
        cekMerah($this->db->last_query());
        sizeof($scrJurnal) < 1 ? matiHere(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // sizeof($scrJurnal) < 1 ? cekHitam(__METHOD__ . " source data sudah habis" . __LINE__) : "";
        // arrPrint($scrJurnal);
        $jTransaksi_id = $scrJurnal[0]->transaksi_id;


        // $ju->addFilter("rekening='penjualan'");
        $this->db->where("rekening in('penjualan','return penjualan')");
        $ju->addFilter("transaksi_id='" . $jTransaksi_id . "'");
        $jurnalPenjualans = $ju->lookupAll()->result();
        cekHere($this->db->last_query());
        if (sizeof($jurnalPenjualans) > 1) {
            $cekDatas = "trId: $jTransaksi_id";
            $cekDatas .= "<br>trId: $jTransaksi_id";
            matiHere(__METHOD__ . " ada kemungkinan doble jurnal, harap diperikasa $cekDatas");
        };

        $jurnalIds[] = $jurnalPenjualans[0]->id;


        // matiHere(__LINE__ . __METHOD__);

        // region membaca 1 data
        // $condite = "status = '1'";
        $tr->setFilters(array());
        $this->db->order_by("id");
        $this->db->limit(1);
        $tr->addFilter("id='" . $jTransaksi_id . "'");
        $tr->addFilter("id_master>'0'");
        // $tr->addFilter("jenis in('".$jenis_list."')");
        // $tr->addFilter("r_jenis='0'");
        $this->db->where("jenis in('" . $jenis_list . "')");
        // $scr_0 = $tr->lookupByCondition($condite)->result();
        $scr = $tr->lookupAll()->result();
        cekHijau($this->db->last_query());

        sizeof($scr) == 0 ? matiHere(__LINE__ . __METHOD__ . " source data sudah habis") : "";
        $scr_0 = $scr[0];

        $transaksi_id = $scr_0->id;
        $transaksi_nomer = $scr_0->nomer;
        $transaksi_jenis = $scr_0->jenis;
        $id_master = $scr_0->id_master;
        $dtime = $transaksi_dtime = $scr_0->dtime;
        $index_registry = $scr_0->indexing_registry;

        cekPink2("jenis master: $transaksi_jenis");

        $tanggal = formatTanggal($dtime, 'Y-m-d');
        $tg = formatTanggal($dtime, 'd') * 1;
        $mg = formatTanggal($dtime, 'W') * 1;
        $bl = formatTanggal($dtime, 'm') * 1;
        $th = formatTanggal($dtime, 'Y') * 1;

        // --------------- --------------- ---------------
        if ($transaksi_jenis == "982") {
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
//            $tr->addFilter("param ='main'");
            $tr->setJointSelectFields("main, transaksi_id");
            $reg = $tr->lookupDataRegistries()->result();
            $mains = blobDecode($reg[0]->main);

            $referenceID = $mains['referenceID'];

            $tr->setFilters(array());
            $tr->addFilter("id='$referenceID'");
            $trTmpx = $tr->lookupAll()->result();
            $id_master = $trTmpx[0]->id_master;
        }


        // region transaksi awal SO
        $scr_1Koloms = array(
            "oleh_nama",
            "oleh_id",
        );
        $tr->setFilters(array());
        $this->db->select($scr_1Koloms);
        $scr_1 = $tr->lookupByCondition("id = '$id_master'")->result()[0];
        cekMerah($this->db->last_query());
        foreach ($scr_1Koloms as $kolom) {
            $$kolom = $scr_1->$kolom;
        }
        // endregion transaksi awal SO

        // endregion membaca 1 data


        // region membaca data registry
        // main
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "jml",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
        );
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
//        $tr->addFilter("param ='main'");
        $tr->setJointSelectFields("main, transaksi_id");
        $regMains = $tr->lookupDataRegistries()->result();
        cekLime($this->db->last_query() . " ::@ " . __LINE__);
        $mainDatas = blobDecode($regMains[0]->main);
        // arrPrintWebs($mainDatas);
        if ((isset($mainDatas['shippingService'])) && ($mainDatas['shippingService'] == "ongkir_ppn_by_cust")) {
            $specMains['ongkir_net'] = $mainDatas['ongkir_net'];
        }
        else {
            $specMains['ongkir_net'] = 0;
        }

        // $datas[] = $specMains;
        // item
        $itemKoloms = array(
            "id",
            "nama",
            "produk_kode",
            "label",
            "jml",
            "sub_nett1",
            "sub_ppn",
            "sub_nett2",
            "pihakID",
            "pihakName",
            "placeID",
            "placeName",
            "gudangID",
            "gudangName",
            "olehID",
            "olehName",
            "ongkir_net",
        );
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id ='" . $transaksi_id . "'");
//        $tr->addFilter("param ='items'");
        $tr->setJointSelectFields("items, transaksi_id");
        $regs = $tr->lookupDataRegistries()->result();
        cekLime($this->db->last_query());
        // arrPrint($regs);
        $datas = array();
        $regDatas = array();
        $sumNett1 = 0;
        foreach ($regs as $reg) {
            foreach($reg as $key_reg => $val_reg){
                if($key_reg != "transaksi_id"){
                    $trId = $reg->transaksi_id;
                    // $refId = $reg->referenceID;
                    $regValues = blobDecode($val_reg);
                    // cekBiru("trId:: $trId");
//            arrPrintWebs($regValues);
                    foreach ($regValues as $regValue) {
                        foreach ($itemKoloms as $itemKolom) {
                            $$itemKolom = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;
                            // $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : 0;
//                    $specs[$itemKolom] = isset($regValue[$itemKolom]) ? $regValue[$itemKolom] : $specMains[$itemKolom];

                            if (isset($regValue[$itemKolom])) {

                                $specs[$itemKolom] = $regValue[$itemKolom];
                            }
                            else {

                                $specs[$itemKolom] = isset($specMains[$itemKolom]) ? $specMains[$itemKolom] : 0;
                            }
                        }
                        // isset($sumJml) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                        isset($sumNett1) ? $sumNett1 += $sub_nett1 : $sumNett1 = 0;
                        $datas[] = $specs;

                    }
                    // $regDatas[$trId]['nett1'] = $regValues['nett1'];
                    // $regDatas[$trId]['referenceID'] = isset($regValues['referenceID']) ? $regValues['referenceID'] : 0;
                    // arrPrint($regValues);
                }
            }
        }
        // endregion membaca 1 data


        $dbReport = new MdlReport();

        // membaca tabel __cli_laporan sudah ada atau belum
        $cek = $dbReport->lookUpCliReportID($transaksi_id);

        if ($cek == 1) {
            // region marking data transaksi yg sudah masuk database report
            $tr->setFilters(array());
            $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));
            cekHere($this->db->last_query());

            foreach ($jurnalIds as $jurnalId) {

                $ju->updateData("id = '$jurnalId'", array("r_jenis" => 1));
                cekBiru($this->db->last_query());
            }

            // endregion marking data transaksi yg sudah masuk database report

            mati_disini("transaksi $transaksi_id sudah masuk ke databse report.");
        }
        else {

        }

        $dbReport->setStartCommit();


        $cek = 0;
        foreach ($datas as $data) {
//            arrPrintWebs($data);

            $cek++;

            foreach ($itemKoloms as $itemKolom) {
                cekHere("$itemKolom => " . $data[$itemKolom]);
                $$itemKolom = $data[$itemKolom];
            }
//            mati_disini("====");
            $label = (($label == null) || ($label == 0)) ? "-" : $label;
            $pihakName = htmlspecialchars($pihakName);
            $nama = htmlspecialchars($nama);

            switch ($transaksi_jenis) {
                case "382spd":
                case "582spd":
                    $newDatas_00 = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        // "object_id"    => $id,
                        // "object_nama"  => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
//                        "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => ($sub_nett1),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_nett1),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_01 = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
                        "object_kode" => $produk_kode,
                        "object_label" => $label,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        "nilai_ot" => ($sub_nett1),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_nett1),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_02 = array(
                        // "subject_id"   => $id,
                        // "subject_nama" => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => 1,
                        "unit_in" => 0,
                        "unit_af" => 1,
                        // ------------------
                        "nilai_ot" => ($sub_nett1),
                        // "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        "nilai_af" => ($sub_nett1),
                        // "nilai_af" => $sub_nett1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        "object_label" => $label,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_03 = array(
//                    "subject_id"   => $oleh_id,
//                    "subject_nama" => $oleh_nama,
//                    "object_id"    => $id,
//                    "object_nama"  => $nama,
//                    "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );

                    $newDatas_CustomerSeller = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
                        "object_id" => $oleh_id,
                        "object_nama" => $oleh_nama,
//                        "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_CustomerProduk = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        "object_label" => $label,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_CustomerCabang = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
                        "object_id" => $placeID,
                        "object_nama" => $placeName,
//                        "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_Customer = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
//                        "object_id"    => $placeID,
//                        "object_nama"  => $placeName,
//                        "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_CustomerSellerCabang = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
                        "object_id" => $oleh_id,
                        "object_nama" => $oleh_nama,
//                        "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_CustomerProdukCabang = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        "object_label" => $label,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );

                    $newDatas_CabangSeller = array(
                        "subject_id" => $placeID,
                        "subject_nama" => $placeName,
                        "object_id" => $oleh_id,
                        "object_nama" => $oleh_nama,
//                        "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_CabangProduk = array(
                        "subject_id" => $placeID,
                        "subject_nama" => $placeName,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        "object_label" => $label,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_CabangCustomer = array(
                        "subject_id" => $placeID,
                        "subject_nama" => $placeName,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
//                        "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_Cabang = array(
                        "subject_id" => $placeID,
                        "subject_nama" => $placeName,
//                        "object_id"    => $placeID,
//                        "object_nama"  => $placeName,
//                        "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );

                    $newDatas_Produk = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
//                    "object_id" => $oleh_id,
//                    "object_nama" => $oleh_nama,
//                    "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_ProdukSeller = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
                        "object_id" => $oleh_id,
                        "object_nama" => $oleh_nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_ProdukCustomer = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_ProdukCabang = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
                        "object_id" => $placeID,
                        "object_nama" => $placeName,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_ProdukSellerCabang = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
                        "object_id" => $oleh_id,
                        "object_nama" => $oleh_nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_ProdukCustomerCabang = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );

                    $newDatas_SellerCabang = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $placeID,
                        "object_nama" => $placeName,
//                    "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_SellerCustomer = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
//                    "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_Seller = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
//                    "object_id" => $placeID,
//                    "object_nama" => $placeName,
//                    "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
//                    "cabang_id" => $placeID,
//                    "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_SellerProduk = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
//                    "cabang_id" => $placeID,
//                    "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_SellerCustomerCabang = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
//                    "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_SellerProdukCabang = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        // ------------------
                        "unit_ot" => $jml,
                        "unit_in" => 0,
                        "unit_af" => $jml,
                        // ------------------
                        // "nilai_ot"     => ($sub_nett1 + $ongkir_net),
                        "nilai_ot" => $sub_nett1,
                        "nilai_in" => 0,
                        // "nilai_af"     => ($sub_nett1 + $ongkir_net),
                        "nilai_af" => $sub_nett1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );

                    break;

                case "982":
                    $newDatas_00 = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => ($sub_nett1),
                        "nilai_af" => ($sub_nett1) * -1,
                        // ------------------
                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_01 = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
                        "object_kode" => $produk_kode,
                        "object_label" => $label,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => ($sub_nett1),
                        "nilai_af" => ($sub_nett1) * -1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_02 = array(
                        // "subject_id"   => $id,
                        // "subject_nama" => $nama,
                        // "object_kode"  => $produk_kode,
                        // ------------------
                        "unit_ot" => 0,
                        "unit_in" => 1,
                        "unit_af" => 1 * -1,
                        // ------------------
                        "nilai_ot" => 0,
                        "nilai_in" => ($sub_nett1),
                        "nilai_af" => ($sub_nett1) * -1,
                        // ------------------
                        // "cabang_id"    => $placeID,
                        // "cabang_nama"  => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        "object_label" => $label,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_03 = array(
//                        "subject_id" => $oleh_id,
//                        "subject_nama" => $oleh_nama,
//                        "object_id" => $id,
//                        "object_nama" => $nama,
//                        "object_kode" => $produk_kode,
//                        "object_label" => $label,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );

                    $newDatas_CustomerSeller = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
                        "object_id" => $oleh_id,
                        "object_nama" => $oleh_nama,
//                        "object_kode"  => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_CustomerProduk = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        "object_label" => $label,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_CustomerCabang = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
                        "object_id" => $placeID,
                        "object_nama" => $placeName,
//                        "object_kode"  => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_Customer = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
//                        "object_id"    => $placeID,
//                        "object_nama"  => $placeName,
//                        "object_kode"  => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_CustomerSellerCabang = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
                        "object_id" => $oleh_id,
                        "object_nama" => $oleh_nama,
//                        "object_kode"  => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_CustomerProdukCabang = array(
                        "subject_id" => $pihakID,
                        "subject_nama" => $pihakName,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        "object_label" => $label,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );

                    $newDatas_CabangSeller = array(
                        "subject_id" => $placeID,
                        "subject_nama" => $placeName,
                        "object_id" => $oleh_id,
                        "object_nama" => $oleh_nama,
//                        "object_kode"  => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_CabangProduk = array(
                        "subject_id" => $placeID,
                        "subject_nama" => $placeName,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,
                        "object_label" => $label,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_CabangCustomer = array(
                        "subject_id" => $placeID,
                        "subject_nama" => $placeName,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
//                        "object_kode"  => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_Cabang = array(
                        "subject_id" => $placeID,
                        "subject_nama" => $placeName,
//                        "object_id"    => $placeID,
//                        "object_nama"  => $placeName,
//                        "object_kode"  => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );

                    $newDatas_Produk = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
//                        "object_id" => $oleh_id,
//                        "object_nama" => $oleh_nama,
//                        "object_kode"  => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_ProdukSeller = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
                        "object_id" => $oleh_id,
                        "object_nama" => $oleh_nama,
                        "object_kode" => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_ProdukCustomer = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
                        "object_kode" => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_ProdukCabang = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
                        "object_id" => $placeID,
                        "object_nama" => $placeName,
                        "object_kode" => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_ProdukCustomerCabang = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
                        "object_kode" => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_ProdukSellerCabang = array(
                        "subject_id" => $id,
                        "subject_nama" => $nama,
                        "subject_kode" => $produk_kode,
                        "subject_label" => $label,
                        "object_id" => $oleh_id,
                        "object_nama" => $oleh_nama,
                        "object_kode" => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );

                    $newDatas_Seller = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
//                        "object_id" => $id,
//                        "object_nama" => $nama,
//                        "object_kode"  => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_SellerProduk = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_SellerCabang = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $placeID,
                        "object_nama" => $placeName,
//                        "object_kode"  => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_SellerCustomer = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
//                        "object_kode"  => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

//                        "cabang_id" => $placeID,
//                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_SellerCustomerCabang = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $pihakID,
                        "object_nama" => $pihakName,
//                        "object_kode"  => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    $newDatas_SellerProdukCabang = array(
                        "subject_id" => $oleh_id,
                        "subject_nama" => $oleh_nama,
                        "object_id" => $id,
                        "object_nama" => $nama,
                        "object_kode" => $produk_kode,

                        "unit_ot" => 0,
                        "unit_in" => $jml,
                        "unit_af" => $jml * -1,

                        "nilai_ot" => 0,
                        "nilai_in" => $sub_nett1,
                        "nilai_af" => $sub_nett1 * -1,

                        "cabang_id" => $placeID,
                        "cabang_nama" => $placeName,
                        "dtime" => $dtime,
                        "tanggal" => $tanggal,
                        "tg" => $tg,
                        "mg" => $mg,
                        "bl" => $bl,
                        "th" => $th,
                        // "counter"   => $counter,
                        "mongo" => 0,
                    );
                    break;
                default:
                    matiHere(__METHOD__ . " @" . __LINE__);
                    break;
            }

            $dbReport->setJenis($jenis);
            $dbReport->setOrder("id DESC");
            $dbReport->setLimit("1");

            $dbReport->setDebug(true);

            //region harian
            $periode = "harian";
            $dbReport->setPeriode($periode);
            $dbReport->setTanggal($tanggal);

            //region counter
//            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode($oleh_id)->result();
//            if (sizeof($tmp_c00) > 0) {
//                $tCounter_00 = $tmp_c00[0];
//                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
//                $lastTanggal_00 = $tCounter_00->tanggal;
//
//                if ($tanggal != $lastTanggal_00) {
//                    $counter_00 = $lastCounter_00 + 1;
//                }
//                else {
//                    $counter_00 = $lastCounter_00;
//                }
//            }
//            else {
//                $counter_00 = 1;
//                $lastCounter_00 = 0;
//                $lastTanggal_00 = "";
//            }
//            // -------------------------------------
//            $tmp_c = $dbReport->lastCounterPeriode()->result();
//            if (sizeof($tmp_c) > 0) {
//                $tCounter = $tmp_c[0];
//                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
//                $lastTanggal = $tCounter->tanggal;
//
//                if ($tanggal != $lastTanggal) {
//                    $counter = $lastCounter + 1;
//                }
//                else {
//                    $counter = $lastCounter;
//                }
//            }
//            else {
//                $counter = 1;
//                $lastCounter = 0;
//                $lastTanggal = "";
//            }
            //endregion


//            $counter_hr["counter"] = $counter;
//            $counter_hr_00["counter"] = $counter_00;
//
            // -------------------------------------
//            $newDatas_hr = $newDatas + $counter_hr;
//            $newDatas_00hr = $newDatas_00 + $counter_hr_00;
//
            // -------------------------------------
//            $dbReport->setDataGlundungs($newDatas_00hr);
//            $dbReport->setDataPp($newDatas_01);
            $dbReport->setDataP($newDatas_02);
//            $dbReport->setDatas($newDatas_hr);
//            $dbReport->setDataOriginal($newDatas_03);

            $dbReport->setDataCustomer($newDatas_Customer);
            $dbReport->setDataCustomerCabang($newDatas_CustomerCabang);
            $dbReport->setDataCustomerProduk($newDatas_CustomerProduk);
            $dbReport->setDataCustomerSeller($newDatas_CustomerSeller);
            $dbReport->setDataCustomerProdukCabang($newDatas_CustomerProdukCabang);
            $dbReport->setDataCustomerSellerCabang($newDatas_CustomerSellerCabang);

            $dbReport->setDataCabang($newDatas_Cabang);
            $dbReport->setDataCabangCustomer($newDatas_CabangCustomer);
            $dbReport->setDataCabangProduk($newDatas_CabangProduk);
            $dbReport->setDataCabangSeller($newDatas_CabangSeller);

            $dbReport->setDataProduk($newDatas_Produk);
            $dbReport->setDataProdukSeller($newDatas_ProdukSeller);
            $dbReport->setDataProdukCustomer($newDatas_ProdukCustomer);
            $dbReport->setDataProdukCabang($newDatas_ProdukCabang);
            $dbReport->setDataProdukSellerCabang($newDatas_ProdukSellerCabang);
            $dbReport->setDataProdukCustomerCabang($newDatas_ProdukCustomerCabang);

            $dbReport->setDataSeller($newDatas_Seller);
            $dbReport->setDataSellerProduk($newDatas_SellerProduk);
            $dbReport->setDataSellerCabang($newDatas_SellerCabang);
            $dbReport->setDataSellerCustomer($newDatas_SellerCustomer);
            $dbReport->setDataSellerProdukCabang($newDatas_SellerProdukCabang);
            $dbReport->setDataSellerCustomerCabang($newDatas_SellerCustomerCabang);
            // -------------------------------------

//            $dbReport->writePenjualanProdukCabang($id, $placeID);
            $dbReport->writePenjualan();
//            $dbReport->writePenjualanCabang($placeID);

            $dbReport->writePenjualanCustomer($placeID, $pihakID);// penjualan customer
            $dbReport->writePenjualanCustomerCabang($placeID, $pihakID, $placeID);// penjualan customer cabang
            $dbReport->writePenjualanCustomerSeller($placeID, $pihakID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanCustomerProduk($placeID, $pihakID, $id);// penjualan customer produk
            $dbReport->writePenjualanCustomerSellerCabang($placeID, $pihakID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanCustomerProdukCabang($placeID, $pihakID, $id);// penjualan customer produk

            // -------------------------------------
            $dbReport->writePenjualanCabang($placeID, $placeID); // penjualan cabang
            $dbReport->writePenjualanCabangSeller($placeID, $placeID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanCabangProduk($placeID, $placeID, $id);// penjualan customer produk
            $dbReport->writePenjualanCabangCustomer($placeID, $placeID, $pihakID);// penjualan customer cabang

            // -------------------------------------
            $dbReport->writePenjualanProduk($id);
            $dbReport->writePenjualanProdukSeller($placeID, $id, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanProdukCustomer($placeID, $id, $pihakID);// penjualan customer cabang
            $dbReport->writePenjualanProdukCabang($id, $placeID);// penjualan customer cabang
            $dbReport->writePenjualanProdukSellerCabang($placeID, $id, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanProdukCustomerCabang($placeID, $id, $pihakID);// penjualan customer cabang

            // -------------------------------------
            $dbReport->writePenjualanSeller($oleh_id);
            $dbReport->writePenjualanSellerProduk($placeID, $oleh_id, $id);
            $dbReport->writePenjualanSellerCabang($placeID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanSellerCustomer($placeID, $oleh_id, $pihakID);// penjualan customer cabang
            $dbReport->writePenjualanSellerProdukCabang($placeID, $oleh_id, $id);
            $dbReport->writePenjualanSellerCustomerCabang($placeID, $oleh_id, $pihakID);// penjualan customer cabang
            // endregion


            // region mingguan
            $periode = "mingguan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            $dbReport->setMinggu($mg);
            // $dbReport->setBulan($bl);
            $dbReport->setTahun($th);
            // $dbReport->setOrder("id DESC");
            // $dbReport->setLimit("1");
            // $tmp_c = $dbReport->lookupPenjualanSellerProduk($data['olehID'], $data['id'])->result()[0];

            //region counter
//            $tmp_c00 = $dbReport->lastCounterPeriode()->result();
//            if (sizeof($tmp_c00) > 0) {
//                $tCounter_00 = $tmp_c00[0];
//                // matiHere(__LINE__);
//                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
//                $lastMg_00 = $tCounter_00->mg;
//                $lastTh_00 = $tCounter_00->th;
//
//                if ($th . $mg != $lastTh_00 . $lastMg_00) {
//                    $counter_00 = $lastCounter_00 + 1;
//                }
//                else {
//                    $counter_00 = $lastCounter_00;
//                }
//            }
//            else {
//                $counter_00 = 1;
//                $lastCounter_00 = 0;
//                $lastMg_00 = "";
//                $lastTh_00 = "";
//            }
//            // ----------------------
//            $tmp_c = $dbReport->lastCounterPeriode()->result();
//            if (sizeof($tmp_c) > 0) {
//                $tCounter = $tmp_c[0];
//                // matiHere(__LINE__);
//                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
//                $lastMg = $tCounter->mg;
//                $lastTh = $tCounter->th;
//
//                if ($th . $mg != $lastTh . $lastMg) {
//                    $counter = $lastCounter + 1;
//                }
//                else {
//                    $counter = $lastCounter;
//                }
//            }
//            else {
//                $counter = 1;
//                $lastCounter = 0;
//                $lastMg = "";
//                $lastTh = "";
//            }
            //endregion
            // arrPrintWebs($tmp_c);
//            cekHitam("$periode:: $th.$mg != $lastTh.$lastMg :: new::$counter last::$lastCounter");

//            $counter_mg["counter"] = $counter;
//            $counter_mg00["counter"] = $counter_00;
            // ----------------------------------
//            $newDatas_mg = $newDatas + $counter_mg;
//            $newDatas_mg00 = $newDatas_00 + $counter_mg00;
            //--------------------------------
//            $dbReport->setDatas($newDatas_mg);
//            $dbReport->setDataGlundungs($newDatas_mg00);

//            $dbReport->writePenjualanProdukCabang($id, $placeID);
            $dbReport->writePenjualan();
//            $dbReport->writePenjualanCabang($placeID);

            $dbReport->writePenjualanCustomer($placeID, $pihakID);// penjualan customer
            $dbReport->writePenjualanCustomerCabang($placeID, $pihakID, $placeID);// penjualan customer cabang
            $dbReport->writePenjualanCustomerSeller($placeID, $pihakID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanCustomerProduk($placeID, $pihakID, $id);// penjualan customer produk
            $dbReport->writePenjualanCustomerSellerCabang($placeID, $pihakID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanCustomerProdukCabang($placeID, $pihakID, $id);// penjualan customer produk

            // -------------------------------------
            $dbReport->writePenjualanCabang($placeID, $placeID); // penjualan cabang
            $dbReport->writePenjualanCabangSeller($placeID, $placeID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanCabangProduk($placeID, $placeID, $id);// penjualan customer produk
            $dbReport->writePenjualanCabangCustomer($placeID, $placeID, $pihakID);// penjualan customer cabang

            // -------------------------------------
            $dbReport->writePenjualanProduk($id);
            $dbReport->writePenjualanProdukSeller($placeID, $id, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanProdukCustomer($placeID, $id, $pihakID);// penjualan customer cabang
            $dbReport->writePenjualanProdukCabang($id, $placeID);// penjualan customer cabang
            $dbReport->writePenjualanProdukSellerCabang($placeID, $id, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanProdukCustomerCabang($placeID, $id, $pihakID);// penjualan customer cabang

            // -------------------------------------
            $dbReport->writePenjualanSeller($oleh_id);
            $dbReport->writePenjualanSellerProduk($placeID, $oleh_id, $id);
            $dbReport->writePenjualanSellerCabang($placeID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanSellerCustomer($placeID, $oleh_id, $pihakID);// penjualan customer cabang
            $dbReport->writePenjualanSellerProdukCabang($placeID, $oleh_id, $id);
            $dbReport->writePenjualanSellerCustomerCabang($placeID, $oleh_id, $pihakID);// penjualan customer cabang
            // endregion mingguan


            // region bulanan
            $periode = "bulanan";
            // $dbReport->setDebug(true);
            // $dbReport->setJenis($jenis);
            $dbReport->setPeriode($periode);
            // $dbReport->setTanggal($tanggal);
            // $dbReport->setMinggu($mg);
            $dbReport->setBulan($bl);
            $dbReport->setTahun($th);

            //region counter
//            $tmp_c = $dbReport->lastCounterPeriode()->result();
//            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
//            if (sizeof($tmp_c) > 0) {
//                $tCounter = $tmp_c[0];
//                // matiHere(__LINE__);
//                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
//                $lastBl = $tCounter->bl;
//                $lastTh = $tCounter->th;
//
//                if ($th . $bl != $lastTh . $lastBl) {
//                    $counter = $lastCounter + 1;
//                }
//                else {
//                    $counter = $lastCounter;
//                }
//            }
//            else {
//                $counter = 1;
//                $lastCounter = 0;
//                $lastBl = "";
//                $lastTh = "";
//            }
//
//            if (sizeof($tmp_c00) > 0) {
//                $tCounter_00 = $tmp_c00[0];
//                // matiHere(__LINE__);
//                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
//                $lastBl_00 = $tCounter_00->bl;
//                $lastTh_00 = $tCounter_00->th;
//
//                if ($th . $bl != $lastTh_00 . $lastBl_00) {
//                    $counter_00 = $lastCounter_00 + 1;
//                }
//                else {
//                    $counter_00 = $lastCounter_00;
//                }
//            }
//            else {
//                $counter_00 = 1;
//                $lastCounter_00 = 0;
//                $lastBl_00 = "";
//                $lastTh_00 = "";
//            }
            //endregion
            // arrPrintWebs($tmp_c);
//            cekHitam("$periode:: $th.$bl != $lastTh.$lastBl :: new::$counter last::$lastCounter");

//            $counter_bl["counter"] = $counter;
//            $counter_bl00["counter"] = $counter_00;
            // --------------------------------
//            $newDatas_bl = $newDatas + $counter_bl;
//            $newDatas_bl00 = $newDatas_00 + $counter_bl00;
            // --------------------------------
//            $dbReport->setDatas($newDatas_bl);
//            $dbReport->setDataGlundungs($newDatas_bl00);
            // --------------------------------

//            $dbReport->writePenjualanProdukCabang($id, $placeID);
            $dbReport->writePenjualan();
//            $dbReport->writePenjualanCabang($placeID);

            $dbReport->writePenjualanCustomer($placeID, $pihakID);// penjualan customer
            $dbReport->writePenjualanCustomerCabang($placeID, $pihakID, $placeID);// penjualan customer cabang
            $dbReport->writePenjualanCustomerSeller($placeID, $pihakID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanCustomerProduk($placeID, $pihakID, $id);// penjualan customer produk
            $dbReport->writePenjualanCustomerSellerCabang($placeID, $pihakID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanCustomerProdukCabang($placeID, $pihakID, $id);// penjualan customer produk

            // -------------------------------------
            $dbReport->writePenjualanCabang($placeID, $placeID); // penjualan cabang
            $dbReport->writePenjualanCabangSeller($placeID, $placeID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanCabangProduk($placeID, $placeID, $id);// penjualan customer produk
            $dbReport->writePenjualanCabangCustomer($placeID, $placeID, $pihakID);// penjualan customer cabang

            // -------------------------------------
            $dbReport->writePenjualanProduk($id);
            $dbReport->writePenjualanProdukSeller($placeID, $id, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanProdukCustomer($placeID, $id, $pihakID);// penjualan customer cabang
            $dbReport->writePenjualanProdukCabang($id, $placeID);// penjualan customer cabang
            $dbReport->writePenjualanProdukSellerCabang($placeID, $id, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanProdukCustomerCabang($placeID, $id, $pihakID);// penjualan customer cabang

            // -------------------------------------
            $dbReport->writePenjualanSeller($oleh_id);
            $dbReport->writePenjualanSellerProduk($placeID, $oleh_id, $id);
            $dbReport->writePenjualanSellerCabang($placeID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanSellerCustomer($placeID, $oleh_id, $pihakID);// penjualan customer cabang
            $dbReport->writePenjualanSellerProdukCabang($placeID, $oleh_id, $id);
            $dbReport->writePenjualanSellerCustomerCabang($placeID, $oleh_id, $pihakID);// penjualan customer cabang
            // endregion bulanan

//            mati_disini("----MATI BULANAN----");

            // region tahunan
            $periode = "tahunan";
            $dbReport->setPeriode($periode);
            $dbReport->setTahun($th);

            //region counter
//            $tmp_c = $dbReport->lastCounterPeriode()->result();
//            if (sizeof($tmp_c) > 0) {
//                $tCounter = $tmp_c[0];
//                // matiHere(__LINE__);
//                $lastCounter = isset($tCounter->counter) ? $tCounter->counter : 0;
//                $lastTh = $tCounter->th;
//
//                if ($th != $lastTh) {
//                    $counter = $lastCounter + 1;
//                }
//                else {
//                    $counter = $lastCounter;
//                }
//            }
//            else {
//                $counter = 1;
//                $lastCounter = 0;
//                $lastTh = "";
//            }
//
//            $tmp_c00 = $dbReport->lastCounterPenjualanSellerPeriode()->result();
//            if (sizeof($tmp_c00) > 0) {
//                $tCounter_00 = $tmp_c00[0];
//                // matiHere(__LINE__);
//                $lastCounter_00 = isset($tCounter_00->counter) ? $tCounter_00->counter : 0;
//                $lastTh_00 = $tCounter_00->th;
//
//                if ($th != $lastTh_00) {
//                    $counter_00 = $lastCounter_00 + 1;
//                }
//                else {
//                    $counter_00 = $lastCounter_00;
//                }
//            }
//            else {
//                $counter_00 = 1;
//                $lastCounter_00 = 0;
//                $lastTh_00 = "";
//            }
            //endregion
            // arrPrintWebs($tmp_c);
//            cekHitam("$periode:: $th != $lastTh :: new::$counter last::$lastCounter");
//
//            $counter_th["counter"] = $counter;
//            $counter_th00["counter"] = $counter_00;
//            $newDatas_th = $newDatas + $counter_th;
//            $newDatas_th00 = $newDatas_00 + $counter_th00;
//            $dbReport->setDatas($newDatas_th);
//            $dbReport->setDataGlundungs($newDatas_th00);


//            $dbReport->writePenjualanProdukCabang($id, $placeID);
            $dbReport->writePenjualan();
//            $dbReport->writePenjualanCabang($placeID);

            $dbReport->writePenjualanCustomer($placeID, $pihakID);// penjualan customer
            $dbReport->writePenjualanCustomerCabang($placeID, $pihakID, $placeID);// penjualan customer cabang
            $dbReport->writePenjualanCustomerSeller($placeID, $pihakID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanCustomerProduk($placeID, $pihakID, $id);// penjualan customer produk
            $dbReport->writePenjualanCustomerSellerCabang($placeID, $pihakID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanCustomerProdukCabang($placeID, $pihakID, $id);// penjualan customer produk

            // -------------------------------------
            $dbReport->writePenjualanCabang($placeID, $placeID); // penjualan cabang
            $dbReport->writePenjualanCabangSeller($placeID, $placeID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanCabangProduk($placeID, $placeID, $id);// penjualan customer produk
            $dbReport->writePenjualanCabangCustomer($placeID, $placeID, $pihakID);// penjualan customer cabang

            // -------------------------------------
            $dbReport->writePenjualanProduk($id);
            $dbReport->writePenjualanProdukSeller($placeID, $id, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanProdukCustomer($placeID, $id, $pihakID);// penjualan customer cabang
            $dbReport->writePenjualanProdukCabang($id, $placeID);// penjualan customer cabang
            $dbReport->writePenjualanProdukSellerCabang($placeID, $id, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanProdukCustomerCabang($placeID, $id, $pihakID);// penjualan customer cabang

            // -------------------------------------
            $dbReport->writePenjualanSeller($oleh_id);
            $dbReport->writePenjualanSellerProduk($placeID, $oleh_id, $id);
            $dbReport->writePenjualanSellerCabang($placeID, $oleh_id);// penjualan customer seller
            $dbReport->writePenjualanSellerCustomer($placeID, $oleh_id, $pihakID);// penjualan customer cabang
            $dbReport->writePenjualanSellerProdukCabang($placeID, $oleh_id, $id);
            $dbReport->writePenjualanSellerCustomerCabang($placeID, $oleh_id, $pihakID);// penjualan customer cabang
            // endregion tahunan

//            mati_disini("...STOP TAHUNAN...");
        }


        $dbReport->setDebug(true);
        $tbl_cli = $dbReport->getTabelCli();
        $dbReport->setTableName($tbl_cli);
        $arrData = array(
            "transaksi_id" => $transaksi_id,
            "nomer" => $transaksi_nomer,
        );
        $dbReport->addData($arrData);


//        mati_disini();
        $dbReport->setEndCommit() or matiHere("gagal ocmit");


        // region marking data transaksi yg sudah dibaca
        $tr->setFilters(array());
        $tr->updateData("id = '$transaksi_id'", array("r_jenis" => 1));
        cekHere($this->db->last_query());

        foreach ($jurnalIds as $jurnalId) {

            $ju->updateData("id = '$jurnalId'", array("r_jenis" => 1));
            cekBiru($this->db->last_query());
        }
        // endregion marking data transaksi yg sudah dibaca

        cekHijau("<h1>- DONE -</h1>");

    }

    //-----UNTUK MINDAH KE MONGO-------------------------------------
    public function genPenjualanToMongo()
    {
//        header("refresh:15");

        cekHijau("START GENERATE TO MONGO");

        $this->load->model("Mdls/MdlReport");
        $this->load->model("Mdls/MdlMongoMother");

        $mg = New MdlMongoMother();
        $dbReport = new MdlReport();


        $table = $dbReport->getTableNames()['penjualan']['tableName'];


        $resultData = array();
        foreach ($table as $key => $tableName) {

            $where = array("mongo" => "0");
            $data = $dbReport->lookupData($tableName, $where)->result();


            if (sizeof($data) > 0) {
                $resultData[$key] = $data;


//                break;
            }
        }

//        cekHitam(sizeof($resultData));
//        mati_disini("COBA...");

        if (sizeof($resultData) > 0) {
            $updateMaster = array();
            foreach ($resultData as $key => $spec) {
                foreach ($spec as $subSpec) {
                    $id = $subSpec->id;
                    $tabel = $table[$key];


                    // cek di mongo tabel dan id
                    $mg->setFilters(array());
                    $mg->setTableName("$tabel");
                    $mg->addFilter(array("id" => "$id"));
                    $mgTmp = $mg->lookupAll();


                    if (sizeof($mgTmp) > 0) {
                        // ada di mongo, UPDATE sesuai id tabel
                        $subSpec_new = (array)$subSpec;
                        unset($subSpec_new['id']);
                        $mg->setFilters(array());
                        $mg->updateData(array("id" => "$id"), $subSpec_new);
                    }
                    else {
                        // tidak ada di mongo, INSERT baru
                        $mg->setTableName("$tabel");
                        $mg->addData((array)$subSpec);
                    }


                    $updateMaster[$tabel][] = $id;
//                    break;
                }
            }

            $dbReport->setStartCommit();

            if (sizeof($updateMaster) > 0) {
                foreach ($updateMaster as $tabel => $ids) {
                    foreach ($ids as $id) {

                        $where = array("id" => "$id");
                        $data = array("mongo" => "1");

                        $dbReport->setTableName("$tabel");
                        $dbReport->updateData($where, $data);
//                        cekMerah("update mongo 1 pada id $id");
                    }
                }
            }


//            mati_disini();
            $dbReport->setEndCommit();

            cekHijau("<h1>-- DONE LANJUT LAGI --</h1>");
        }
        else {
            cekHitam("<h1>SUDAH KOSONG</h1>");
        }


    }

    public function genPembelianProdukToMongo()
    {
//        header("refresh:5");

        cekHijau("START GENERATE TO MONGO");

        $this->load->model("Mdls/MdlReport");
        $this->load->model("Mdls/MdlMongoMother");

        $mg = New MdlMongoMother();
        $dbReport = new MdlReport();


        $table = $dbReport->getTableNames()['pembelian_produk']['tableName'];


        $resultData = array();
        foreach ($table as $key => $tableName) {

            $where = array("mongo" => "0");
            $dbReport->setLimit(100);
            $data = $dbReport->lookupData($tableName, $where)->result();
//            cekHere(sizeof($data));
//            mati_disini("COBA...");
            if (sizeof($data) > 0) {
                $resultData[$key] = $data;

//                break;
            }
        }
//
//        cekHitam(sizeof($resultData));
//        arrPrintWebs($resultData);
//        mati_disini("COBA...");
//
        if (sizeof($resultData) > 0) {
            $updateMaster = array();
            foreach ($resultData as $key => $spec) {
                foreach ($spec as $subSpec) {
                    $id = $subSpec->id;
                    $tabel = $table[$key];


                    // cek di mongo tabel dan id
                    $mg->setFilters(array());
                    $mg->setTableName("$tabel");
                    $mg->addFilter(array("id" => "$id"));
                    $mgTmp = $mg->lookupAll();


                    if (sizeof($mgTmp) > 0) {
                        // ada di mongo, UPDATE sesuai id tabel
                        $subSpec_new = (array)$subSpec;
                        unset($subSpec_new['id']);
                        $mg->setFilters(array());
                        $mg->updateData(array("id" => "$id"), $subSpec_new);
                    }
                    else {
                        // tidak ada di mongo, INSERT baru
                        $mg->setTableName("$tabel");
                        $mg->addData((array)$subSpec);
                    }


                    $updateMaster[$tabel][] = $id;
//                    break;
                }
            }

            $dbReport->setStartCommit();

            if (sizeof($updateMaster) > 0) {
                foreach ($updateMaster as $tabel => $ids) {
                    foreach ($ids as $id) {

                        $where = array("id" => "$id");
                        $data = array("mongo" => "1");

                        $dbReport->setTableName("$tabel");
                        $dbReport->updateData($where, $data);
//                        cekMerah("update mongo 1 pada id $id");
                    }
                }
            }


//            mati_disini();
            $dbReport->setEndCommit();

            cekHijau("<h1>-- DONE LANJUT LAGI --</h1>");
        }
        else {
            cekHitam("<h1>SUDAH KOSONG</h1>");
        }


    }


    //---------------------------------------------------------------

    public function genJurnalToMongo()
    {
        $this->load->model("Coms/ComJurnal");
        $this->load->model("Mdls/MdlMongoMother");

        $mg = New MdlMongoMother();

        $ju = New ComJurnal();
        $ju->setFilters(array());
        $ju->addFilter("mongo='0'");
        $juTmp = $ju->lookupAll()->result();

        $tabel = $ju->getTableName();

        $updateMaster = array();
        if (sizeof($juTmp) > 0) {
            foreach ($juTmp as $spec) {
                $id = $spec->id;
                $updateMaster[$tabel][] = $id;


                $mg->setTableName("$tabel");
                $mg->setFilters(array());
                $mg->addFilter(array("id" => "$id"));
                $mgTmp = $mg->lookupAll();
                if (sizeof($mgTmp) > 0) {
                    // ada di mongo, UPDATE sesuai id tabel
                    cekHijau("UPDATE id $id");
                    $spec_new = (array)$spec;
                    unset($spec_new['id']);
                    $mg->setFilters(array());
                    $mg->updateData(array("id" => "$id"), $spec_new);
                }
                else {
                    // tidak ada di mongo, INSERT baru
                    cekHijau("INSERT BARU id $id");
                    $mg->setTableName("$tabel");
                    $mg->addData((array)$spec);
                }
            }
        }


        $this->db->trans_start();

        if (sizeof($updateMaster) > 0) {
            foreach ($updateMaster as $tabel => $ids) {
                foreach ($ids as $id) {

                    $where = array("id" => "$id");
                    $data = array("mongo" => "1");

                    $ju->setTableName("$tabel");
                    $ju->updateData($where, $data);
                    cekMerah("update mongo 1 pada id $id");
                }
            }
        }


//            mati_disini();
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


//        mati_disini(__FUNCTION__);
    }

    public function genNeracaToMongo()
    {
        $this->load->model("Mdls/MdlNeraca");
        $this->load->model("Mdls/MdlMongoMother");

        $mg = New MdlMongoMother();

        $ju = New MdlNeraca();
        $ju->setFilters(array());
        $ju->addFilter("mongo='0'");
        $juTmp = $ju->lookupAll()->result();

        $tabel = $ju->getTableName();

        $updateMaster = array();
        if (sizeof($juTmp) > 0) {
            foreach ($juTmp as $spec) {
                $id = $spec->id;
                $updateMaster[$tabel][] = $id;


                $mg->setTableName("$tabel");
                $mg->setFilters(array());
                $mg->addFilter(array("id" => "$id"));
                $mgTmp = $mg->lookupAll();
                if (sizeof($mgTmp) > 0) {
                    // ada di mongo, UPDATE sesuai id tabel
                    cekHijau("UPDATE id $id");
                    $spec_new = (array)$spec;
                    unset($spec_new['id']);
                    $mg->setFilters(array());
                    $mg->updateData(array("id" => "$id"), $spec_new);
                }
                else {
                    // tidak ada di mongo, INSERT baru
                    cekHijau("INSERT BARU id $id");
                    $mg->setTableName("$tabel");
                    $mg->addData((array)$spec);
                }
            }
        }


        $this->db->trans_start();

        if (sizeof($updateMaster) > 0) {
            foreach ($updateMaster as $tabel => $ids) {
                foreach ($ids as $id) {

                    $where = array("id" => "$id");
                    $data = array("mongo" => "1");

                    $ju->setTableName("$tabel");
                    $ju->updateData($where, $data);
                    cekMerah("update mongo 1 pada id $id");
                }
            }
        }


//        mati_disini();
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


//        mati_disini(__FUNCTION__);
    }

    public function genRugilabaToMongo()
    {
        $this->load->model("Mdls/MdlRugilaba");
        $this->load->model("Mdls/MdlMongoMother");

        $mg = New MdlMongoMother();

        $ju = New MdlRugiLaba();
        $ju->setFilters(array());
        $ju->addFilter("mongo='0'");
        $juTmp = $ju->lookupAll()->result();

        $tabel = $ju->getTableName();

        $updateMaster = array();
        if (sizeof($juTmp) > 0) {
            foreach ($juTmp as $spec) {
                $id = $spec->id;
                $updateMaster[$tabel][] = $id;


                $mg->setTableName("$tabel");
                $mg->setFilters(array());
                $mg->addFilter(array("id" => "$id"));
                $mgTmp = $mg->lookupAll();
                if (sizeof($mgTmp) > 0) {
                    // ada di mongo, UPDATE sesuai id tabel
                    cekHijau("UPDATE id $id");
                    $spec_new = (array)$spec;
                    unset($spec_new['id']);
                    $mg->setFilters(array());
                    $mg->updateData(array("id" => "$id"), $spec_new);
                }
                else {
                    // tidak ada di mongo, INSERT baru
                    cekHijau("INSERT BARU id $id");
                    $mg->setTableName("$tabel");
                    $mg->addData((array)$spec);
                }
            }
        }


        $this->db->trans_start();

        if (sizeof($updateMaster) > 0) {
            foreach ($updateMaster as $tabel => $ids) {
                foreach ($ids as $id) {

                    $where = array("id" => "$id");
                    $data = array("mongo" => "1");

                    $ju->setTableName("$tabel");
                    $ju->updateData($where, $data);
                    cekMerah("update mongo 1 pada id $id");
                }
            }
        }


//        mati_disini();
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


//        mati_disini(__FUNCTION__);
    }

    public function genFinanceConfigToMongo()
    {
        $this->load->model("Mdls/MdlFinanceConfig");
        $this->load->model("Mdls/MdlMongoMother");

        $mg = New MdlMongoMother();

        $ju = New MdlFinanceConfig();
        $ju->setFilters(array());
        $ju->addFilter("mongo='0'");
        $juTmp = $ju->lookupAll()->result();

        $tabel = $ju->getTableName();

        $updateMaster = array();
        if (sizeof($juTmp) > 0) {
            foreach ($juTmp as $spec) {
                $id = $spec->id;
                $updateMaster[$tabel][] = $id;


                $mg->setTableName("$tabel");
                $mg->setFilters(array());
                $mg->addFilter(array("id" => "$id"));
                $mgTmp = $mg->lookupAll();
                if (sizeof($mgTmp) > 0) {
                    // ada di mongo, UPDATE sesuai id tabel
                    cekHijau("UPDATE id $id");
                    $spec_new = (array)$spec;
                    unset($spec_new['id']);
                    $mg->setFilters(array());
                    $mg->updateData(array("id" => "$id"), $spec_new);
                }
                else {
                    // tidak ada di mongo, INSERT baru
                    cekHijau("INSERT BARU id $id");
                    $mg->setTableName("$tabel");
                    $mg->addData((array)$spec);
                }
            }
        }


        $this->db->trans_start();

        if (sizeof($updateMaster) > 0) {
            foreach ($updateMaster as $tabel => $ids) {
                foreach ($ids as $id) {

                    $where = array("id" => "$id");
                    $data = array("mongo" => "1");

                    $ju->setTableName("$tabel");
                    $ju->updateData($where, $data);
                    cekMerah("update mongo 1 pada id $id");
                }
            }
        }


//        mati_disini();
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


//        mati_disini(__FUNCTION__);
    }

    public function generatePriceBranc()
    {
        $this->load->model("Mdls/MdlHargaProduk2");
        $p = new MdlHargaProduk2();
        $p->addFilter("cabang_id='-1'");
        $temp = $p->lookupAll()->result();
        $cabngNew = array("26", "27", "28", "29");
        $replacer = array();
        $this->db->trans_start();
        foreach ($cabngNew as $dbID) {
            foreach ($temp as $tmpData) {
                unset($tmpData->id);
                $tmpData->cabang_id = "$dbID";
                $tmpData->oleh_id = "-99";
                $tmpData->oleh_nama = "sys";
                $tmpData->dtime = date("Y-m-d H:i");
                $p->addData((array)$tmpData);
//            arrPrint($tmpData);

            }
        }
        matiHere("komplitt");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
//        arrPrint($temp);
    }

    public function validateClonerTrans()
    {

        $this->load->model("Mdls/MdlMongoMother");
        $defFilter = array("mongo" => "0");

        //region transaksi
        $rr = array();
        $m = new MdlMongoMother();
        $m->setFilters(array());
        $m->addFilter($defFilter);
        $m->setShortBy(array("_id" => "1"));
        $m->setLimit("1000");
        $m->setFields(array("id"));
        $m->setTableName("transaksi");
        $rr = $m->lookupAll();
        $listedDup = array();
        $inListed = array();
        if (sizeof($rr) > 0) {
            foreach ($rr as $k => $id) {
                $listedDup[$id['id']][] = $id['_id'];
            }
//            arrPrint($listedDup);
            $listedIDS = array_keys($listedDup);
            $m = new MdlMongoMother();
            $m->setTableName('transaksi');
            $m->setFilters(array());
            if (sizeof($listedDup) > 0) {
                $delListed = array();
                $jmlDel = 0;
                foreach ($listedDup as $iTrans => $listID) {
                    if (sizeof($listID) > 1) {
                        foreach ($listID as $k => $ii) {
//                            cekHitam($k);
                            if ($k > 0) {
                                $jmlDel++;
//                            $delListed[]="ObjectId("."'$ii'".")";//manual
                                $fil = array("_id" => $ii);
                                $m->addFilter($fil);
                                $m->deleteData();
                            }
                        }
                    }

                }
                echo("delete transaksi $jmlDel " . "<br>");
            }

            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($listedIDS);
            $m->updateDataMany(array("mongo" => "1"));
            echo ("update transaksi " . sizeof($listedIDS)) . "<br>";
        }
        else {
            echo "data habis transaksi" . "<br>";
        }
        $rr = array();
        //endregion

        //region update transaksi data
        $m = new MdlMongoMother();
        $m->setFilters(array());
        $m->addFilter($defFilter);
        $m->setShortBy(array("_id" => "1"));
        $m->setLimit("1000");
        $m->setFields(array("id"));
        $m->setTableName("transaksi_data");
        $rr = $m->lookupAll();
//        arrPrint($rr);
        $listedDup = array();
        $inListed = array();
        if (sizeof($rr) > 0) {
            foreach ($rr as $k => $id) {
                $listedDup[$id['id']][] = $id['_id'];
            }
//            arrPrint($listedDup);
            $listedIDS = array_keys($listedDup);
            $m = new MdlMongoMother();
            $m->setTableName('transaksi_data');
            $m->setFilters(array());
            if (sizeof($listedDup) > 0) {
                $delListed = array();
                $jmlDel = 0;
                foreach ($listedDup as $iTrans => $listID) {
                    if (sizeof($listID) > 1) {
                        foreach ($listID as $k => $ii) {
//                            cekHitam($k);
                            if ($k > 0) {
                                $jmlDel++;
//                            $delListed[]="ObjectId("."'$ii'".")";//manual
                                $fil = array("_id" => $ii);
                                $m->addFilter($fil);
                                $m->deleteData();
                            }
                        }
                    }

                }
                echo("delete transaksi_data $jmlDel " . "<br>");
            }

            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($listedIDS);
            $m->updateDataMany(array("mongo" => "1"));
            echo ("update listed data transaksi " . sizeof($listedIDS)) . "<br>";
        }
        else {
            echo "data habis transaksi_data " . "<br>";
        }
        $rr = array();

        //endregion

        //region update transaksi registry
        $m = new MdlMongoMother();
        $m->setFilters(array());
        $m->addFilter($defFilter);
        $m->setShortBy(array("_id" => "1"));
        $m->setLimit("1000");
        $m->setFields(array("id"));
        $m->setTableName("transaksi_registry");
        $rr = $m->lookupAll();
//        arrPrint($rr);
        $listedDup = array();
        $inListed = array();
        if (sizeof($rr) > 0) {
            foreach ($rr as $k => $id) {
                $listedDup[$id['id']][] = $id['_id'];
            }
//            arrPrint($listedDup);
            $listedIDS = array_keys($listedDup);
            $m = new MdlMongoMother();
            $m->setTableName('transaksi_registry');
            $m->setFilters(array());
            if (sizeof($listedDup) > 0) {
                $delListed = array();
                $jmlDel = 0;
                foreach ($listedDup as $iTrans => $listID) {
                    if (sizeof($listID) > 1) {
                        foreach ($listID as $k => $ii) {
//                            cekHitam($k);
                            if ($k > 0) {
                                $jmlDel++;
//                            $delListed[]="ObjectId("."'$ii'".")";//manual
                                $fil = array("_id" => $ii);
                                $m->addFilter($fil);
                                $m->deleteData();
                            }
                        }
                    }

                }
                echo("delete data $jmlDel " . "<br>");
            }

            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($listedIDS);
            $m->updateDataMany(array("mongo" => "1"));
            echo ("update transaksi_registry " . sizeof($listedIDS)) . "<br>";
        }
        else {
            echo "data habis transaksi_registry " . "<br>";
        }
//        $rr = array();
        //endregion


    }

    public function syncFolderKategori(){
        $this->load->model("Mdls/MdlFolderProdukRakitan");
        $this->load->model("Mdls/MdlFolderProduk");
        $this->load->model("Mdls/MdlProdukKategori");
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlProdukRakitan");
        // $fp = new MdlFolderProduk();
        // $fp = new MdlFolderProdukRakitan();
        $fp = new MdlProdukKategori();

        // $p = new MdlProduk();
        $p = new MdlProdukRakitan();

        $p->addFilter("kategori_id>0");
        // $p->addFilter("folders>0");

        $pTemp = $p->lookupAll()->result();
        cekLime($this->db->last_query());
        $te=array();
        foreach ($pTemp as $pTemp0){
            $te[$pTemp0->id]=$pTemp0->kategori_id;//kategori
            // $te[$pTemp0->id]=$pTemp0->folders;//folder
        }

        $foldersTmp = $fp->lookupAll()->result();
        $folder=array();
        foreach($foldersTmp as $foldersTmp_0){
            $folder[$foldersTmp_0->id]=$foldersTmp_0->nama;
        }
        $p->setFilters(array());
        // arrPrint($te);
        // arrPrint($folder);
        matiHEre("hoopppp");

        foreach($te as $pid =>$foldersID){
            if(isset($folder[$foldersID])){
                // $update = array("folders_nama"=>$folder[$foldersID]);
                $update = array("kategori_nama"=>$folder[$foldersID]);
                cekHitam($pid." folders ".$foldersID);
                arrPrint($update);
                $p->updateData(array("id"=>$pid),$update);
                cekLime($this->db->last_query());

            }else{
                cekMerah("cook");
            }
        }
        cekHitam(sizeof($te));
        arrPrint($folder);

    }

    public function syncPib()
    {
        // header("Refresh:2");
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("jenis='682'");
        $tr->addFilter("trash='0'");
        $tr->addFilter("trash_4='0'");
        $tr->addFilter("trash2='0'");
        $tr->addFilter("link_id='0'");
        $this->db->limit(1);
        $this->db->trans_start();
        $temp = $tr->lookUpAll()->result();
        if (sizeof($temp) > 0) {
            $id = $temp[0]->id;
            $nomer = $temp[0]->nomer;
            $jenis = $temp[0]->jenis;
            // cekHitam("ada yang ditulis ".$id);
            $tr->setfilters(array());
            $tr->addFilter("transaksi_id='$id'");
            $registry = $tr->lookupDataRegistries()->result();
            $mainReg = array();
            $main = array();
            $items = array();
            foreach ($registry[0] as $param => $paramData) {
                // arrprintWebs($paramData);
                // cekMerah($param);
                switch ($param) {
                    case "main":
                        $main = blobDecode($paramData);
                        if (is_array($main)) {
                            $mainReg = $main;
                        }
                        else {
                            $mainReg = blobDecode($main);
                        }
                        break;
                    case "items":
                        $items = blobDecode($paramData);
                        if (is_array($items)) {
                            $itemsReg = $items;
                        }
                        else {
                            $itemsReg = blobDecode($items);
                        }
                        break;
                }
            }

            //lihat sumber transaksi po aka po asal
            $nomer_po_pajak = "";
            $id_po_pajak = "";
            foreach ($itemsReg as $itesms) {
                $nomer_po_pajak = $itesms["name"];
                $id_po_pajak = $itesms["id"];
            }
            // arrPrint($itemsReg);
            $tr->setfilters(array());
            $tr->addFilter("id='" . $mainReg["pihakID"] . "'");
            $tmpTr = $tr->lookupMainTransaksi()->result();
            $supplierID = $tmpTr[0]->suppliers_id;
            $supplierNama = $tmpTr[0]->suppliers_nama;
            $nomerPO = $tmpTr[0]->nomer;
            $jenis_po = $tmpTr[0]->jenis;

            // //lihat paymentsource untuk ambil data po pajak
            // $tr->setFilters(array());
            // $tmpPaymentSrc = $tr->lookupPaymentSrcs("21716",$jenis);
            // cekHitam($this->db->last_query());


            // arrPrint($tmpPaymentSrc);
            // arrPrint($itemsReg);
            // matiHere($this->db->last_query());

            $tempdata = array(
                "transaksi_id"    => "$id",//transaksi pembayaran
                "nomer"           => "$nomer",//nomer pembayran
                "extern_id"       => $mainReg["pihakID"],//transkasi id po
                "extern_nama"     => $mainReg["pihakName"],//transkasi nomor po
                "cabang_nama"     => $mainReg["placeName"],
                "cabang_id"       => $mainReg["placeID"],
                "jenis"           => "682",//
                "target_jenis"    => "0000",
                "reference_jenis" => "$jenis_po",//jenis po
                "label"           => "pib",
                "tagihan"         => $mainReg["nilai_bayar"],//transaksi nilai aka tagihan
                "sisa"            => $mainReg["nilai_bayar"],
                "oleh_id"         => $mainReg["olehID"],
                "oleh_nama"       => $mainReg["olehName"],
                "dtime"           => $mainReg["dtime"],//tanggal pembayaran
                "fulldate"        => $mainReg["fulldate"],//fulldate pembayaran
                "extern_date2"    => $mainReg["fulldate"],//tgl pembayaran
                "extern2_id"      => $id_po_pajak,//id po pajak
                "extern2_nama"    => $nomer_po_pajak,//nomer po request pajak
                "suppliers_id"    => "$supplierID",//id vendor pembayaran
                "suppliers_nama"  => "$supplierNama",//nama vendor pembayaran
            );

            $insertID = $tr->writePaymentSrc($id, $tempdata);

            $updateMainTras = array("trash2" => "1");
            $where = array("id" => $id);
            $tr->setFilters(array());

            $tr->setTableName($tr->getTableNames()['main']);
            $dupl = $tr->updateData($where, $updateMainTras) or die(lgShowAlert("gagal menulis transaksi"));
cekMErah($this->db->last_query());

            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        }
        else {
            cekHitam("habisss broo");
        }

    }

    public function syncPpnKelextern2()
    {
        //nulis transksi id invoice
        header("Refresh:2");
        //update dulu _key => "11 untuk penanda nantinya jika sudah diambil upte ke 0
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("jenis='110'");
        $tr->addFilter("target_jenis='114'");
        $tr->addFilter("_key='11'");
        $this->db->limit(1);
        $tempPym = $tr->lookUpAllPaymentSrc()->result();
        // arrprint($tempPym);
        if(sizeof($tempPym)>0){
            $idInv = $tempPym[0]->transaksi_id;
            $pymID = $tempPym[0]->id;

            $tr->setFilters(array());
            $tr->addFilter("transaksi_id='$idInv'");
            $tempTrans = $tr->lookupDataRegistries()->result();
            $main = $tempTrans[0]->main;
            $mainReg = blobDecode($main);
            if(is_array($mainReg)){
                $valMain = $mainReg;
            }
            else{
                $valMain = blobDecode($mainReg);
            }
            $invoice = $valMain["efaktur_source"];
            $dateFactur = $valMain["dateFaktur"];
            $noFaktur = $valMain["eFaktur"];

            $tr->setFilters(array());
            $tr->addFilter("nomer='$invoice'");
            $temp = $tr->lookupMainTransaksi()->result();
            $idInv = $temp[0]->id;

            $update = array(
                "extern2_id"=>"$idInv",
                "extern2_nama"=>"$invoice",
                // "extern_label2"=>"$noFaktur",
                // "extern_date2"=>"$dateFactur",
                "_key"=>"0",
            );
            $where = array(
                "id"=>$pymID
            );

            $this->db->trans_start();
            $tr->setFilters(array());
            $tr->setTableName($tr->getTableNames()['paymentSrc']);
            $dupl = $tr->updateData($where, $update) or die(lgShowAlert("gagal menulis transaksi"));

            //
            //         arrprint(blobDecode($main));
            //
            cekMErah($this->db->last_query());
            // arrPrint($tempTrans);

            // matiHEre();
            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        }
        else{
            cekHitam("habisss");
        }

    }

    public function syncBendahara(){
        header("Refresh:2");
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("jenis='749'");
        $tr->addFilter("target_jenis='0000'");
        $tr->addFilter("_key='11'");
        $this->db->limit(1);
        $tempPym = $tr->lookUpAllPaymentSrc()->result();
        if(sizeof($tempPym)>0){
            $trid = $tempPym[0]->transaksi_id;
            $currID = $tempPym[0]->id;


            $tr->setFilters(array());
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id='$trid'");
            $tempTrans = $tr->lookupDataRegistries()->result();
            $main = $tempTrans[0]->main;
            $items = $tempTrans[0]->items;
            $mainReg = blobDecode($main);
            $itemsReg = blobDecode($items);

            if(is_array($mainReg)){
                $valMain = $mainReg;
                $valItems = $itemsReg;
            }
            else{
                $valMain = blobDecode($mainReg);
                $valItems = blobDecode($itemsReg);
            }
            $invID = $valMain["refID"];
            $invoicing = $valItems[$invID]["name"];

            //ambil data paymentsource ppn keluaran  aka 110

            $tr->setFilters(array());
            $tr->addFilter("extern2_id=$invID");
            $tr->addFilter("target_jenis='114'");
            $tempPaymentPajak = $tr->lookUpAllPaymentSrc()->result();

            if(sizeof($tempPaymentPajak)>0){
                $toUpdate = array(
                    "extern_label2"=>$tempPaymentPajak[0]->extern_label2,//no faktur
                    "extern2_id"=>$tempPaymentPajak[0]->extern2_id,//id invoicing
                    "extern2_nama"=>$tempPaymentPajak[0]->extern2_nama,//nomer invoicng
                    "extern_date2"=>$tempPaymentPajak[0]->extern_date2,//tgl faktur
                    "_key"=>"0",
                );
            }
            else{
                $toUpdate = array(
                    // "extern_label2"=>$tempPaymentPajak[0]->extern_label2,//no faktur
                    "extern2_id"=>$invID,//id invoicing
                    "extern2_nama"=>$invoicing,//nomer invoicng
                    "_key"=>"0",
                    // "extern_date2"=>$tempPaymentPajak[0]->extern_date2,//tgl faktur
                );
            }
            $where = array(
                "id"=>$currID,
            );
            $this->db->trans_start();
            $tr->setFilters(array());
            $tr->setTableName($tr->getTableNames()['paymentSrc']);
            $dupl = $tr->updateData($where, $toUpdate) or die(lgShowAlert("gagal menulis transaksi"));

            cekMerah($this->db->last_query());

            // arrPrint($toUpdate);
            // arrPrintWebs($valMain);
            // cekBiru("$trid");
            // cekMerah($invoicing);
            // matiHEre();
            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        }
        else{
            cekHitam("habiss");
        }


    }

    public function cekPPh23(){
        $this->load->model("Coms/ComRekeningPembantuPphMain");
        $p = new ComRekeningPembantuPphMain();
        // $p->addFilter("cabang_id='-1'");
        $allData = $p->fetchAllMove("2030030");
        $allDebet = array();
        $allKredit = array();
        $allSaldo = array();
        $tableValidasi = "";
        if(sizeof($allData)>0){
            $tableValidasi = "<table>";
            $tableValidasi .= "<tr>";
            $tableValidasi .= "<td>id</td>";
            $tableValidasi .= "<td>date</td>";
            $tableValidasi .= "<td>awal</td>";
            $tableValidasi .= "<td>debet</td>";
            $tableValidasi .= "<td>kredit</td>";
            $tableValidasi .= "<td>akhir</td>";
            $tableValidasi .= "</tr>";
            foreach($allData as $allData_0){
                if(!isset($allSaldo["debet"])){
                    $allSaldo["debet"] = 0;
                }
                if(!isset($allSaldo["kredit"])){
                    $allSaldo["kredit"] = 0;
                }
                $allSaldo["debet"] += $allData_0->debet;
                $allSaldo["kredit"] += $allData_0->kredit;
                $tableValidasi .= "<tr>";
                $tableValidasi .= "<td>".$allData_0->id."</td>";
                $tableValidasi .= "<td>".$allData_0->fulldate."</td>";
                $tableValidasi .= "<td>".$allData_0->debet_awal."</td>";
                $tableValidasi .= "<td>".$allData_0->debet."</td>";
                $tableValidasi .= "<td>".$allData_0->kredit."</td>";
                $tableValidasi .= "<td>".$allData_0->kredit - $allData_0->debet."</td>";
                $tableValidasi .= "</tr>";
            }
            $tableValidasi .= "</table>";
        }
        // cekMErah($allSaldo["kredit"] - $allSaldo["debet"]);
        // arrPrint($allSaldo);
        echo $tableValidasi;

cekMErah(__LINE__." || ".$this->db->last_query());
    }

    public function cekProdukDouble(){
        $this->load->model("Mdls/MdlProduk");
        $p = new MdlProduk();
        $p->addFilter("kategori_nama='unit'");
        $prrr = $p->lookUpAll()->result();
        cekHitam(count($prrr));
        $fff = array();
        if(count($prrr)>0){
            foreach($prrr as $prrr_0){
                $fff[$prrr_0->nama][]=$prrr_0->id;
            }
            foreach($fff as $produk_nama =>$Data){
                if(count($Data)>1){
                    $final[$produk_nama]=$Data;
                }
            }
        }
        arrprint($final);
    }
}