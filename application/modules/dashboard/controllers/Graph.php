<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 03/04/2019
 * Time: 13.50
 */

class Graph extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);
        $this->load->library("SmtpMailer");
        session_write_close();
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

    /*--pwnjualan---*/
    public function viewGraphSales()
    {
        $arrRekeningCoa = rekening_coa_he_accounting();
        $arrRekeningCoaFlip = array_flip($arrRekeningCoa);

        $this->load->model("Mdls/MdlRugilaba");
        $d = new MdlRugilaba();
        $thn_now = dtimeNow('Y');
        $thn_batas = $thn_now - 3;
        //============= B I A Y A =============
        $d->addFilter("thn>'$thn_batas'");
        $d->addFilter("kategori='biaya'");
        $d->addFilter("periode='bulanan'");
        //        $d->addFilter("rekening='penjualan'");
        $tmp1 = $d->lookupAll()->result();
        // cekMerah($this->db->last_query());

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
                    // bagian ini utnuk mendeteksi rekening coa, maka diganti ke alfabet
                    // diganti ke alfabet karena bagian bawah ada hitung2an dengan key rekening alfabet
                    if (is_numeric($row3->rekening)) {
                        $row3->rekening = $arrRekeningCoaFlip[$row3->rekening];
                    }
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
        // cekOrange($this->db->last_query());
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
                    // bagian ini utnuk mendeteksi rekening coa, maka diganti ke alfabet
                    // diganti ke alfabet karena bagian bawah ada hitung2an dengan key rekening alfabet
                    if (is_numeric($row3->rekening)) {
                        $row3->rekening = $arrRekeningCoaFlip[$row3->rekening];
                    }

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

            $this->load->config("heAccounting");
            $categoriRl = $this->config->item("categoryRL");
            // arrPrint($categoriRl);
            $arrKeyBiaya = $categoriRl[2];
            $arrKeyRll = $categoriRl[3];
            //             arrPrintPink($arrPenjualan["2022-11"]);
// arrPrintHijau($arrPenjualan);
// matiHere(__LINE__);
            foreach ($arrPenjualan as $periode => $data) {

                // ini bagian hitung2an, memakai rekening alfabet
                $penjualan = isset($data['penjualan']['total_kredit']) ? $data['penjualan']['total_kredit'] - $data['penjualan']['total_debet'] : 0;
                $penjualan_projek = isset($data['penjualan projek']['total_kredit']) ? $data['penjualan projek']['total_kredit'] - $data['penjualan projek']['total_debet'] : 0;
                $hpp_projek = isset($data['hpp projek']['total_debet']) ? $data['hpp projek']['total_debet'] : 0;
                $return_penjualan = isset($data['return penjualan']['total_debet']) ? $data['return penjualan']['total_debet'] : 0;
                $hpp = isset($data['hpp']['total_debet']) ? $data['hpp']['total_debet'] : 0;
                $efisiensi_biaya = isset($data['efisiensi biaya']['total_debet']) ? $data['efisiensi biaya']['total_debet'] - $data['efisiensi biaya']['total_kredit'] : 0;
                //-----

                $project = $penjualan_projek;

                $penjualan_net = ($penjualan - $return_penjualan) + $project;

                $total_biaya = 0;
                foreach ($arrKeyBiaya as $ke_rek => $item) {
                    $lr_nilai = isset($data[$ke_rek]['total_kredit']) ? $data[$ke_rek]['total_kredit'] - $data[$ke_rek]['total_debet'] : 0;
                    $total_biaya += $lr_nilai;
                }

                $laba_rugi_lain_lain = 0;
                foreach ($arrKeyRll as $key_rek => $item) {
                    $lr_nilai = isset($data[$key_rek]['total_kredit']) ? $data[$key_rek]['total_kredit'] - $data[$key_rek]['total_debet'] : 0;
                    $laba_rugi_lain_lain += $lr_nilai;
                }
                // cekHere($periode." ". $laba_rugi_lain_lain);
                // cekHere("$laba_rugi_lain_lain = $kerugian + $jasa_kirim + $laba_lain_lain + $laba_rugi_perubahan_grade_produk + $laba_rugi_selisih_adjusment + $quality + $delivery_cost + $direct_labor + $beban_lain_lain + $pendapatan;");
                /*
                 * hpp project belum dijumlah sebagai penjualan bruto
                 * penjualan project belum masuk sebagai penjualan
                 * jadi nilai bruto belum sesuai, laba rugi lain
                 * lk = penjualan - hpp &
                 * hpp =  penjualan - lk
                 * penjualan = hpp + lk
                 *  Tulislah "laba bersih" dari scr{pembersih} jika
                    value = src{penjualannetto} - src{Totalbiaya}
                    DAN value + src{Totalbiaya} = src{penjualannetto}
                 */

                /*new 11/10/2022*/
                $bruto = 0;
                $netto = 0;
                if ($total_biaya < 0) {
                    $total_biaya_2 = $total_biaya * -1;
                } else {
                    $total_biaya_2 = $total_biaya;
                }
                if ($laba_rugi_lain_lain < 0) {
                    $laba_rugi_lain_lain_2 = $laba_rugi_lain_lain * -1;
                    $biaya_tanpa_hpp = $total_biaya_2 + $laba_rugi_lain_lain_2;
                } else {
                    $laba_rugi_lain_lain_2 = $laba_rugi_lain_lain;
                    $biaya_tanpa_hpp = $total_biaya_2 - $laba_rugi_lain_lain_2;
                }

                // $biaya_tanpa_hpp = ($total_biaya < 0 ? ($total_biaya * -1) : $total_biaya) - ($laba_rugi_lain_lain < 0 ? ($laba_rugi_lain_lain * -1) : ($laba_rugi_lain_lain * 1));

                // $biaya_tanpa_hpp = $total_biaya + $laba_rugi_lain_lain;
                $total_hpp = $hpp + $hpp_projek + $efisiensi_biaya;
                // cekHijau("$total_hpp = $hpp + $hpp_projek;");
                $bruto = $penjualan_net - $total_hpp;
                $netto = $bruto - $biaya_tanpa_hpp;

                $arrLabel[] = $periode;
                $arrBruto[] = $bruto;
                $arrNetto[] = $netto;
                $arrHpp[] = $total_hpp;
                $arrBiaya[] = $biaya_tanpa_hpp;
                $arrBiayaHpp[] = $total_hpp + $biaya_tanpa_hpp;
                $arrPenj[] = $penjualan_net;
                $arrProj[] = $project;

                $result = array(
                    "label" => $arrLabel,
                    "bruto" => $arrBruto,
                    "netto" => $arrNetto,
                    "hpp" => $arrHpp,
                    "biaya" => $arrBiaya,
                    "biayahpp" => $arrBiayaHpp,
                    "penjualan" => $arrPenj,
                    "project" => $arrProj,
                );
            }
            // arrPrint($result);
        } else {
            // cekHere("data penjualan tidak ada " . __LINE__);
            $result = array(
                "label" => array(),
                "bruto" => array(),
                "netto" => array(),
                "hpp" => array(),
                "biaya" => array(),
                "biayahpp" => array(),
                "penjualan" => array(),
                "project" => array(),
            );
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
        $this->load->view("graph", $data);

    }

    public function viewCompareSales()
    {
        /*ngambil data neraca lajur pusat*/
        // $this->load->model("Coms/ComRekening");
        // $r = new ComRekening();
        $tmp = array();
        // $r->addFilter("cabang_id='" . $cabang_id . "'");
        // $tmp = $r->fetchAllBalances();
        // showLast_query("merah");
        // arrPrint($tmp);
        //         matiHere(__LINE__);

        $dlm_milyar = 1000000000;
        /*PENJUALAN*/

        //----------------
        $tgl_ini = dtimeNow('Y-m-d');
        $thn_ini = dtimeNow('Y');
        // $thn_ini = "2022";
        $thn_lalu = previousYear();
        // $thn_lalu = "2021";
        $tahun[] = $thn_lalu;

        $this->load->model("Coms/ComRekening");
        $cr = new ComRekening();
        /*penjualan yg lalu*/
        $akredit = $cr->callPenjualanNettoTahun($thn_lalu - 1);
        // showLast_query("biru");
        $nilai_penjualan_past_0 = $akredit / $dlm_milyar;
        $penjualan_past_0[] = $nilai_penjualan_past_0;

        /*penjualan yg lalu*/
        $akredit = $cr->callPenjualanNettoTahun($thn_lalu);
        // showLast_query("biru");
        $nilai_penjualan_past = $akredit / $dlm_milyar;
        $penjualan_past[] = $nilai_penjualan_past;

        /*penjualan now*/
        // $kredit = $cr->callPenjualanNettoNow();
        $kredit = $this->call_penjualan_netto_tahun($thn_ini);
        // showLast_query("hijau");
        $nilai_penjualan_now = $kredit / $dlm_milyar;
        $penjualan_now[] = $nilai_penjualan_now;
        // cekHere($akredit);

        // $tahun[] = dtimeNow('Y');
        $tahun[] = $thn_ini;

        /*persen penjualan now*/
        $persen_penjualan_now = $nilai_penjualan_past > 0 ? (($nilai_penjualan_now / $nilai_penjualan_past) * 100) : 0;
        $persen_penjualan_now_f = number_format($persen_penjualan_now, 0);

        /*--prediksi target penjualan tahun ini--*/
        // cekHijau(formatTanggal($tgl_ini, 'z') . " $thn_ini");
        $hari_dlm_setahun = 365;
        $hari_ke = formatTanggal($tgl_ini, 'z') + 1;
        $target_penjualan = ($hari_dlm_setahun / $hari_ke) * $nilai_penjualan_now;
        $target_penjualan_now[] = $target_penjualan;
        // cekHere($hari_ke);
        // cekHere(date('z',dtimeToSecond('2022-12-31')) + 1);

        /*--target ---------------*/
        // arrPrint($_GET['target_new']);
        if (isset($_GET['target_new'])) {
            $persen_target = $_GET['target_new'];
        } else {
            $dir = getcwd() . "/application/modules/dashboard/logs";
            $myfiles = fopen($dir . "/target_pertumbuhan.txt", "r") or die("Unable to open file!");
            $persen_target = fread($myfiles, filesize($dir . "/target_pertumbuhan.txt"));
            fclose($myfiles);
        }
        // arrPrintHijau(json_decode($persen_target, true));
        $target_persen = $persen_target * 1;

        $adj_target = $nilai_penjualan_past * ($target_persen / 100);
        $target_tahun_now[] = $nilai_penjualan_past + $adj_target;

        $var = "";

        $tahun[] = "target";
        // echo $var;
        $var["tahun"] = $tahun;
        $var["penjualan_past_0"] = $penjualan_past_0;
        $var["penjualan_past"] = $penjualan_past;
        // $var["penjualan_past_label"] = $thn_lalu;
        $var["penjualan_now"] = $penjualan_now;
        $var["target_penjualan_now"] = $target_penjualan_now;
        $var["target_tahun_now"] = $target_tahun_now;
        // $var["penjualan_now_label"] = dtimeNow('Y');;
        //
        // arrPrintHijau($var);
        // arrPrintHijau(json_encode($var));

        $data = array(
            "mode" => "viewGraphSales",
            "title" => "Graph Reports",
            "subTitle" => "",
            "content" => $var,
            "penjualan_now_label" => $thn_ini,
            "penjualan_past_label_0" => $thn_lalu - 1,
            "penjualan_past_label" => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            "persen_now" => $persen_penjualan_now_f,
            "target_persen" => $target_persen,
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
        $this->load->view("graph", $data);
    }

    public function viewCompareSales_1()
    {
        /*ngambil data neraca lajur pusat*/
        // $this->load->model("Coms/ComRekening");
        // $r = new ComRekening();
        $tmp = array();
        // $r->addFilter("cabang_id='" . $cabang_id . "'");
        // $tmp = $r->fetchAllBalances();
        // showLast_query("merah");
        // arrPrint($tmp);
        //         matiHere(__LINE__);

        $dlm_milyar = 1000000000;
        /*PENJUALAN*/

        //----------------
        $thn_lalu = previousYear();
        $tahun[] = $thn_lalu;

        $this->load->model("Coms/ComRekening");
        $cr = new ComRekening();

        /*penjualan yg lalu*/
        $akredit = $cr->callPenjualanNettoTahun($thn_lalu);
        $nilai_penjualan_past = $akredit / $dlm_milyar;
        $penjualan_past[] = $nilai_penjualan_past;

        /*penjualan now*/
        $kredit = $cr->callPenjualanNettoNow();
        $nilai_penjualan_now = $kredit / $dlm_milyar;
        $penjualan_now[] = $nilai_penjualan_now;
        // cekHere($akredit);

        $tahun[] = dtimeNow('Y');

        /*persen penjualan now*/
        $persen_penjualan_now = ($nilai_penjualan_now / $nilai_penjualan_past) * 100;
        $persen_penjualan_now_f = number_format($persen_penjualan_now, 0);

        /*--prediksi target penjualan tahun ini--*/
        $hari_dlm_setahun = 365;
        $hari_ke = dtimeNow('z') + 1;
        $target_penjualan = ($hari_dlm_setahun / $hari_ke) * $nilai_penjualan_now;
        $target_penjualan_now[] = $target_penjualan;
        // cekHere($hari_ke);
        // cekHere(date('z',dtimeToSecond('2022-12-31')) + 1);

        /*--target ---------------*/
        // arrPrint($_GET['target_new']);
        if (isset($_GET['target_new'])) {
            $persen_target = $_GET['target_new'];
        } else {
            $dir = getcwd() . "/application/modules/dashboard/logs";
            $myfiles = fopen($dir . "/target_pertumbuhan.txt", "r") or die("Unable to open file!");
            $persen_target = fread($myfiles, filesize($dir . "/target_pertumbuhan.txt"));
            fclose($myfiles);
        }
        // arrPrintHijau(json_decode($persen_target, true));
        $target_persen = $persen_target * 1;

        $adj_target = $nilai_penjualan_past * ($target_persen / 100);
        $target_tahun_now[] = $nilai_penjualan_past + $adj_target;

        $var = "";

        $tahun[] = "target";
        // echo $var;
        $var["tahun"] = $tahun;
        $var["penjualan_past"] = $penjualan_past;
        // $var["penjualan_past_label"] = $thn_lalu;
        $var["penjualan_now"] = $penjualan_now;
        $var["target_penjualan_now"] = $target_penjualan_now;
        $var["target_tahun_now"] = $target_tahun_now;
        // $var["penjualan_now_label"] = dtimeNow('Y');;
        //
        arrPrintHijau($var);

        $data = array(
            "mode" => "viewGraphSales",
            "title" => "Graph Reports",
            "subTitle" => "",
            "content" => $var,
            "penjualan_now_label" => dtimeNow('Y'),
            "penjualan_past_label" => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            "persen_now" => $persen_penjualan_now_f,
            "target_persen" => $target_persen,
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
        $this->load->view("graph", $data);
    }

    public function call_penjualan_netto_tahun($tahun)
    {
        $koloms = array(
            "rekening",
            // "extern_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit"
        );
        $this->db->select($koloms);
        $rekenings = array(
            // "return penjualan",
            // "4020010",
            // "penjualan",
            "4010",
            "4020",
            // "export",
            // "4010020",
            // "penjualan projek",
            // "4010030",
        );
        $this->db->where_in("rekening", $rekenings);
        $condites = array(
            // "rekening" => "4010",
            "thn" => $tahun,
            "periode" => "tahunan"
        );
        $this->db->where($condites);
        $tableName = "_rek_master_cache";
        $src_pls = $this->db->get($tableName)->row();
        // showLast_query("kuning");
        // arrPrintKuning($src_pls);

        // $rek_id = $src_pls->rek_id;
        // $rekening = $src_pls->rekening;
        $debet = $src_pls->debet;
        $kredit = $src_pls->kredit;

        $nilai_penjualan_now = $kredit - $debet;

        return $nilai_penjualan_now;
    }

    public function viewSales()
    {
        $dlm_milyar = 1000000000;

        $tableName = "_rek_master_cache";
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "thn" => dtimeNow('Y'),
            // "bln"     => dtimeNow('m'),
            "periode" => "tahunan"
        );
        $this->db->where($condites);
        $this->db->group_by("rekening");
        $conditeins = array(
            "penjualan",
            "return penjualan",
            "penjualan projek",

            // "return penjualan",
        );
        $this->db->where_in("rekening", $conditeins);
        $src_datas = "";
        $src_datas = $this->db->get($tableName)->result();
        // showLast_query("merah");
        // arrPrintPink($src_datas);
        foreach ($src_datas as $src_data) {
            $mDatas[$src_data->rekening] = $src_data;
        }

        // matiHere(__LINE__);
        $penjualan_projek = isset($mDatas["penjualan projek"]->kredit) ? $mDatas["penjualan projek"]->kredit : 0;
        $return_penjualan = isset($mDatas["return penjualan"]->debet) ? $mDatas["return penjualan"]->debet : 0;
        $penjualan_netto = $mDatas["penjualan"]->kredit - $return_penjualan - $return_penjualan;


        $var = "";

        // $tahun[] = "target";
        // // echo $var;
        // $var["tahun"] = $tahun;
        $var["penjualan"] = $penjualan_netto / $dlm_milyar;
        // $var["penjualan_past_label"] = $thn_lalu;
        $var["penjualan_projek"] = $penjualan_projek / $dlm_milyar;
        $var["penjualan_total"] = ($penjualan_projek + $penjualan_netto) / $dlm_milyar;
        // $var["target_penjualan_now"] = $target_penjualan_now;
        // $var["target_tahun_now"] = $target_tahun_now;
        // $var["penjualan_now_label"] = dtimeNow('Y');;
        //
        // arrPrintHijau($var);

        $data = array(
            "mode" => "viewSales",
            "title" => "Graph Reports",
            "subTitle" => "",
            "content" => $var,
            "penjualan_now_label" => dtimeNow('Y'),
            // "penjualan_past_label"       => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
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
        $this->load->view("graph", $data);
    }

    public function viewSalesD()
    {
        $dlm_milyar = 1000000000;

        /*best of sales*/
        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        $strThn = formatTanggal(previousMonth(), "Y");
        $strBln = formatTanggal(previousMonth(), "F");
        $date_1 = formatTanggal(previousMonth(), "Y-m-01");
        $date_2 = formatTanggal(previousMonth(), "Y-m-t");
        // $src_datas = $dt->callPerSeller(previousMonth(), $date_2);
        $src_datas = array();
        // $tableName = "_rek_master_cache";
        // $koloms = array(
        //     "rekening",
        //     "rek_id",
        //     "sum(kredit) as kredit",
        //     "sum(debet) as debet"
        // );
        // $this->db->select($koloms);
        // $condites = array(
        //     "thn"     => dtimeNow('Y'),
        //     // "bln"     => dtimeNow('m'),
        //     "periode" => "tahunan"
        // );
        // $this->db->where($condites);
        // $this->db->group_by("rekening");
        // $rekening_alias = $conditeins = array(
        //     "reguler" => "4010",
        //     "return"  => "4020",
        //     "projek"  => "4010030",
        //
        //     // "return penjualan",
        // );
        // $this->db->where_in("rekening", $conditeins);
        // $src_datas = "";
        // $src_datas = $this->db->get($tableName)->result();
        // showLast_query("merah");
        // arrPrintPink($src_datas);
        $kategoriGede = array();
        foreach ($src_datas as $src_data) {
            $mDatas[$src_data->rekening] = $src_data;

            // $kategoriGede[] = $src_data->rekening;
        }
        // arrPrintHijau($mDatas);
        // arrPrintHijau($kategoriGede);

        // $xPenjualan = $mDatas["4010"]->kredit - (isset($mDatas["return penjualan"]) ? $mDatas["return penjualan"]->debet : 0);
        // $xProjek = $mDatas["4010030"]->kredit;
        $dataGede = array();
        // $dataGede[] = $xPenjualan / $dlm_milyar;
        // $dataGede[] = $xProjek / $dlm_milyar;
        // $totalGede = ($xPenjualan + $xProjek) / $dlm_milyar;
        // arrPrintHijau($dataGede);

        $arrPenjualan = array(
            "regular" => array(
                "4010010", "4010020"
            ),
            "projek" => array("4010030")
        );
        // cekMerah("$xPenjualan $xProjek");
        // showLast_query("merah");
        // cekPink(json_encode($kategoriGede));
        $src_datas = array();
        // $tableName = "__rek_master__penjualan";
        $tableName = "_rek_pembantu_penjualan_cache";
        $koloms = array(
            "extern_id",
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet",
            // "debet",
            // "kredit",
        );
        $tgl_ini = dtimeNow('Y-m-d');
        // $tgl_ini = "2022-12-31";
        // $tgl_ini = dtimeNow('2022-m-d');
        $thn_ini = formatTanggal($tgl_ini, 'Y');
        // $thn_ini = 2022;
        $this->db->select($koloms);
        $condites = array(
            "thn" => $thn_ini,
            // "bln"     => dtimeNow('m'),
            "periode" => "tahunan"
        );
        $this->db->where($condites);
        $this->db->group_by("extern_id");
        $jenis_alias = $conditeins = array(
            // "local"  => "582spd",
            // "export" => "382spd",
            // "projek" => "7499",
            "local" => "4010010",
            "export" => "4010020",
            "projek" => "4010030"
        );
        $this->db->where_in("extern_id", $conditeins);
        $src_datas = "";
        $src_datas = $this->db->get($tableName)->result();
        // showLast_query("kuning");
        // arrPrintPink($src_datas);
        $def_datas[] = array(
            "extern_id" => 4010010,
            "rekening" => 4010,
            "rek_id" => 4010010000,
            "kredit" => 0,
            "debet" => 0,
        );
        $def_datas[] = array(
            "extern_id" => 4010020,
            "rekening" => 4010,
            "rek_id" => 4010020000,
            "kredit" => 0,
            "debet" => 0,
        );
        $def_datas[] = array(
            "extern_id" => 4010030,
            "rekening" => 4010,
            "rek_id" => 4010030000,
            "kredit" => 0,
            "debet" => 0,
        );
        // arrPrintKuning($def_datas);
        // arrPrint(array_replace_recursive($def_datas, $src_datas));
        // $kategoriGede[] = $src_data->rekening;

        $sumGede = array();
        $totalGede = 0;
        $dataCilik = array();
        foreach ($jenis_alias as $jenis_ext_id) {
            $dataCilik[$jenis_ext_id] = 0;
        }
        foreach ($src_datas as $item) {
            // arrPrintKuning($item);
            $ext_id = $item->extern_id;

            $kategoriCilik = array();
            foreach ($jenis_alias as $jenis_ext_id) {

                // cekHitam("$jenis_ext_id == $ext_id || " . $item->kredit);
                $kategoriCilik[] = $jenis_ext_id;

                if ($jenis_ext_id == $ext_id) {
                    $dataCilik[$jenis_ext_id] = $item->kredit;
                }
                //
                // if($jenis_ext_id = $ext_id){
                //     $dataCilik[$jenis_ext_id] = 0;
                // }
            }

            // if(in_array($ext_id,$jenis_alias)){
            //     $dataCilik[$jenis_ext_id] = $item->kredit;
            // }
            // else{
            //     $dataCilik[$jenis_ext_id] = 0;
            // }

            /*---membuat data gede dari cilikan--*/
            foreach ($arrPenjualan as $katGede => $itemRekens) {
                foreach ($itemRekens as $itemReken) {
                    if ($itemReken == $ext_id) {

                        if (!isset($sumGede[$katGede])) {
                            $sumGede[$katGede] = 0;
                        }
                        $sumGede[$katGede] += $item->kredit;
                    }
                }
            }
        }
        // $sumGede[0] =0;
        // arrPrintPink($sumGede);
        // arrPrintPink(array_values($dataCilik));
        // $kategoriGede = array("regular");
        foreach ($sumGede as $kiGede => $valGede) {
            $kategoriGede[] = $kiGede;
            $dataGede[] = $valGede / $dlm_milyar;

            $totalGede += $valGede / $dlm_milyar;
        }

        // arrPrint($datas);
        // $kirims = array();
        // $orders = array();
        // foreach ($datas["seller"] as $obj_id => $obj_nama) {
        //     $obj_data_kirim = $datas['kirim'][$obj_id];
        //     $kirims[] = array($obj_nama, $obj_data_kirim);
        //     $obj_data_order = $datas['order'][$obj_id];
        //     $orders[] = array($obj_nama, $obj_data_order);
        // }
        // arrPrintPink($kirims);
        // matiHere(__LINE__);
        // $penjualan_projek = isset($mDatas["penjualan projek"]->kredit) ? $mDatas["penjualan projek"]->kredit : 0;
        // $return_penjualan = isset($mDatas["return penjualan"]->debet) ? $mDatas["return penjualan"]->debet : 0;
        // $penjualan_netto = $mDatas["penjualan"]->kredit - $return_penjualan - $return_penjualan;


        $var = "";

        // $tahun[] = "target";
        // // echo $var;
        // $var["tahun"] = $tahun;
        // arrPrint($kategoriCilik);
        // arrPrint(array_keys($jenis_alias));
        $var["kategoriGede"] = $kategoriGede;
        $var["kategoriCilik"] = $kategoriCilik;
        $var["kategoriCilikAlias"] = array_flip($jenis_alias);
        $var["kategoriGedeAlias"] = ($kategoriGede);
        $var["dataGede"] = $dataGede;
        $var["dataCilik"] = array_values($dataCilik);
        $var["totalGede"] = $totalGede;
        // $var["penjualan"] = $kirims;
        // $var["order"] = $orders;
        // $var["penjualan_total"] = ($penjualan_projek + $penjualan_netto) / $dlm_milyar;
        // $var["target_penjualan_now"] = $target_penjualan_now;
        // $var["target_tahun_now"] = $target_tahun_now;
        // $var["penjualan_now_label"] = dtimeNow('Y');;
        //
        // arrPrintHijau($var);
        // $var = array();
        // if (ipadd() != "202.65.117.72") {
        //     $var = array();
        // }
        $data = array(
            "mode" => "viewSalesDonut",
            "title" => "Penjualan YTD",
            "subTitle" => "",
            "judul_graph" => "Penjualan YTD",
            "content" => $var,
            // "penjualan_now_label" => $thn_ini,
            "tgl_ini" => $tgl_ini,
            // "penjualan_past_label"       => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
            "container" => '0',
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
        $this->load->view("graph", $data);
    }

    public function viewSalesDPast()
    {
        $dlm_milyar = 1000000000;

        /*best of sales*/
        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        $strThn = formatTanggal(previousMonth(), "Y");
        $strBln = formatTanggal(previousMonth(), "F");
        $date_1 = formatTanggal(previousMonth(), "Y-m-01");
        $date_2 = formatTanggal(previousMonth(), "Y-m-t");
        // $src_datas = $dt->callPerSeller(previousMonth(), $date_2);
        $src_datas = array();
        // $tableName = "_rek_master_cache";
        // $koloms = array(
        //     "rekening",
        //     "rek_id",
        //     "sum(kredit) as kredit",
        //     "sum(debet) as debet"
        // );
        // $this->db->select($koloms);
        // $condites = array(
        //     "thn"     => dtimeNow('Y'),
        //     // "bln"     => dtimeNow('m'),
        //     "periode" => "tahunan"
        // );
        // $this->db->where($condites);
        // $this->db->group_by("rekening");
        // $rekening_alias = $conditeins = array(
        //     "reguler" => "4010",
        //     "return"  => "4020",
        //     "projek"  => "4010030",
        //
        //     // "return penjualan",
        // );
        // $this->db->where_in("rekening", $conditeins);
        // $src_datas = "";
        // $src_datas = $this->db->get($tableName)->result();
        // showLast_query("merah");
        // arrPrintPink($src_datas);
        $kategoriGede = array();
        foreach ($src_datas as $src_data) {
            $mDatas[$src_data->rekening] = $src_data;

            // $kategoriGede[] = $src_data->rekening;
        }
        // arrPrintHijau($mDatas);
        // arrPrintHijau($kategoriGede);

        // $xPenjualan = $mDatas["4010"]->kredit - (isset($mDatas["return penjualan"]) ? $mDatas["return penjualan"]->debet : 0);
        // $xProjek = $mDatas["4010030"]->kredit;
        $dataGede = array();
        // $dataGede[] = $xPenjualan / $dlm_milyar;
        // $dataGede[] = $xProjek / $dlm_milyar;
        // $totalGede = ($xPenjualan + $xProjek) / $dlm_milyar;
        // arrPrintHijau($dataGede);

        $arrPenjualan = array(
            "regular" => array(
                "4010010", "4010020"
            ),
            "projek" => array("4010030")
        );
        // cekMerah("$xPenjualan $xProjek");
        // showLast_query("merah");
        // cekPink(json_encode($kategoriGede));
        $src_datas = array();
        // $tableName = "__rek_master__penjualan";
        $tableName = "_rek_pembantu_penjualan_cache";
        $koloms = array(
            "extern_id",
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet",
            // "debet",
            // "kredit",
        );
        $tgl_ini = dtimeNow('Y-m-d');
        // $tgl_ini = "2022-12-31";
        // $tgl_ini = dtimeNow('2022-m-d');
        $thn_ini = formatTanggal($tgl_ini, 'Y') - 1;
        // $thn_ini = 2022;
        $this->db->select($koloms);
        $condites = array(
            "thn" => $thn_ini,
            // "bln"     => dtimeNow('m'),
            "periode" => "tahunan"
        );
        $this->db->where($condites);
        $this->db->group_by("extern_id");
        $jenis_alias = $conditeins = array(
            // "local"  => "582spd",
            // "export" => "382spd",
            // "projek" => "7499",
            "local" => "4010010",
            "export" => "4010020",
            "projek" => "4010030"
        );
        $this->db->where_in("extern_id", $conditeins);
        $src_datas = "";
        $src_datas = $this->db->get($tableName)->result();
        // showLast_query("kuning");
        // arrPrintPink($src_datas);

        // $kategoriGede[] = $src_data->rekening;

        $kategoriCilik = array();
        $dataCilik = array();
        $sumGede = array();
        $totalGede = 0;
        foreach ($src_datas as $item) {

            $kategoriCilik[] = $item->extern_id;
            $dataCilik[] = $item->kredit / $dlm_milyar;

            /*---membuat data gede dari cilikan--*/
            foreach ($arrPenjualan as $katGede => $itemRekens) {
                foreach ($itemRekens as $itemReken) {
                    if ($itemReken == $item->extern_id) {

                        if (!isset($sumGede[$katGede])) {
                            $sumGede[$katGede] = 0;
                        }
                        $sumGede[$katGede] += $item->kredit;
                    }
                }
            }
        }
        // $sumGede[0] =0;
        // arrPrint($sumGede);
        // $kategoriGede = array("regular");
        foreach ($sumGede as $kiGede => $valGede) {
            $kategoriGede[] = $kiGede;
            $dataGede[] = $valGede / $dlm_milyar;

            $totalGede += $valGede / $dlm_milyar;
        }

        // arrPrint($datas);
        // $kirims = array();
        // $orders = array();
        // foreach ($datas["seller"] as $obj_id => $obj_nama) {
        //     $obj_data_kirim = $datas['kirim'][$obj_id];
        //     $kirims[] = array($obj_nama, $obj_data_kirim);
        //     $obj_data_order = $datas['order'][$obj_id];
        //     $orders[] = array($obj_nama, $obj_data_order);
        // }
        // arrPrintPink($kirims);
        // matiHere(__LINE__);
        // $penjualan_projek = isset($mDatas["penjualan projek"]->kredit) ? $mDatas["penjualan projek"]->kredit : 0;
        // $return_penjualan = isset($mDatas["return penjualan"]->debet) ? $mDatas["return penjualan"]->debet : 0;
        // $penjualan_netto = $mDatas["penjualan"]->kredit - $return_penjualan - $return_penjualan;


        $var = "";

        // $tahun[] = "target";
        // // echo $var;
        // $var["tahun"] = $tahun;
        // arrPrint($kategoriCilik);
        // arrPrint(array_keys($jenis_alias));
        $var["kategoriGede"] = $kategoriGede;
        $var["kategoriCilik"] = $kategoriCilik;
        $var["kategoriCilikAlias"] = array_flip($jenis_alias);
        $var["kategoriGedeAlias"] = ($kategoriGede);
        $var["dataGede"] = $dataGede;
        $var["dataCilik"] = $dataCilik;
        $var["totalGede"] = $totalGede;
        // $var["penjualan"] = $kirims;
        // $var["order"] = $orders;
        // $var["penjualan_total"] = ($penjualan_projek + $penjualan_netto) / $dlm_milyar;
        // $var["target_penjualan_now"] = $target_penjualan_now;
        // $var["target_tahun_now"] = $target_tahun_now;
        // $var["penjualan_now_label"] = dtimeNow('Y');;
        //
        // arrPrintHijau($var);

        $data = array(
            "mode" => "viewSalesDonut",
            "title" => "Penjualan Tahun $thn_ini",
            "subTitle" => "",
            "content" => $var,
            // "penjualan_now_label" => $thn_ini,
            "tgl_ini" => $tgl_ini,
            // "penjualan_past_label"       => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
            "container" => 'past',
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
        $this->load->view("graph", $data);
    }

    public function viewSalesDttm()
    {
        $dlm_milyar = 1000000000;

        /*best of sales*/
        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        $strThn = formatTanggal(previousMonth(), "Y");
        $strBln = formatTanggal(previousMonth(), "F");
        $date_1 = formatTanggal(previousMonth(), "Y-m-01");
        $date_2 = formatTanggal(previousMonth(), "Y-m-t");
        // $src_datas = $dt->callPerSeller(previousMonth(), $date_2);
        $src_datas = array();
        // $tableName = "_rek_master_cache";
        // $koloms = array(
        //     "rekening",
        //     "rek_id",
        //     "sum(kredit) as kredit",
        //     "sum(debet) as debet"
        // );
        // $this->db->select($koloms);
        // $condites = array(
        //     "thn"     => dtimeNow('Y'),
        //     // "bln"     => dtimeNow('m'),
        //     "periode" => "tahunan"
        // );
        // $this->db->where($condites);
        // $this->db->group_by("rekening");
        // $rekening_alias = $conditeins = array(
        //     "reguler" => "4010",
        //     "return"  => "4020",
        //     "projek"  => "4010030",
        //
        //     // "return penjualan",
        // );
        // $this->db->where_in("rekening", $conditeins);
        // $src_datas = "";
        // $src_datas = $this->db->get($tableName)->result();
        // showLast_query("merah");
        // arrPrintPink($src_datas);
        $kategoriGede = array();
        foreach ($src_datas as $src_data) {
            $mDatas[$src_data->rekening] = $src_data;

            // $kategoriGede[] = $src_data->rekening;
        }
        // arrPrintHijau($mDatas);
        // arrPrintHijau($kategoriGede);

        // $xPenjualan = $mDatas["4010"]->kredit - (isset($mDatas["return penjualan"]) ? $mDatas["return penjualan"]->debet : 0);
        // $xProjek = $mDatas["4010030"]->kredit;
        $dataGede = array();
        // $dataGede[] = $xPenjualan / $dlm_milyar;
        // $dataGede[] = $xProjek / $dlm_milyar;
        // $totalGede = ($xPenjualan + $xProjek) / $dlm_milyar;
        // arrPrintHijau($dataGede);

        $arrPenjualan = array(
            "regular" => array(
                "4010010", "4010020"
            ),
            "projek" => array("4010030")
        );
        // cekMerah("$xPenjualan $xProjek");
        // showLast_query("merah");
        // cekPink(json_encode($kategoriGede));
        $src_datas = array();
        // $tableName = "__rek_master__penjualan";
        $tableName = "_rek_pembantu_penjualan_cache";
        $koloms = array(
            "extern_id",
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet",
            // "debet",
            // "kredit",
        );
        $tgl_ini = dtimeNow('Y-m-d');

        // cekHijau($bulan_lalu . " $tgl_terakhir_bulan_lalu");

        $ttm_bulan = backCustomMonths($tgl_ini, 12);
        $bulan_lalu = $ttm_bulan[0];
        $tgl_terakhir_bulan_lalu = formatTanggal($bulan_lalu, 't');
        $ttm_bulan_last = $bulan_lalu . "-$tgl_terakhir_bulan_lalu";
        $ttm_bulan_first = $ttm_bulan[11] . "-01";
        // arrPrintPink($ttm_bulan[11]);


        // $tgl_ini = dtimeNow('2022-m-d');
        $thn_ini = formatTanggal($tgl_ini, 'Y');
        // $thn_ini = 2022;
        $this->db->select($koloms);
        $condites = array(
            "date(dtime)>=" => $ttm_bulan_first,
            "date(dtime)<=" => $ttm_bulan_last,
            // "bln"     => dtimeNow('m'),
            "periode" => "bulanan"
        );
        $this->db->where($condites);
        $this->db->group_by("rek_id");
        $jenis_alias = $conditeins = array(
            // "local"  => "582spd",
            // "export" => "382spd",
            // "projek" => "7499",
            "local" => "4010010",
            "projek" => "4010030",
            "export" => "4010020",
        );
        $this->db->where_in("extern_id", $conditeins);
        $this->db->order_by('extern_id');
        $src_datas = "";
        $src_datas = $this->db->get($tableName)->result();
        // showLast_query("kuning");
        // arrPrintPink($src_datas);

        // $kategoriGede[] = $src_data->rekening;

        $kategoriCilik = array();
        $dataCilik = array();
        $sumGede = array();
        $totalGede = 0;
        foreach ($src_datas as $item) {

            $kategoriCilik[] = $item->extern_id;
            $dataCilik[] = $item->kredit / $dlm_milyar;

            /*---membuat data gede dari cilikan--*/
            foreach ($arrPenjualan as $katGede => $itemRekens) {
                foreach ($itemRekens as $itemReken) {
                    if ($itemReken == $item->extern_id) {

                        if (!isset($sumGede[$katGede])) {
                            $sumGede[$katGede] = 0;
                        }
                        $sumGede[$katGede] += $item->kredit;
                    }
                }
            }
        }
        // $sumGede[0] =0;
        // arrPrint($sumGede);
        // $kategoriGede = array("regular");
        foreach ($sumGede as $kiGede => $valGede) {
            $kategoriGede[] = $kiGede;
            $dataGede[] = $valGede / $dlm_milyar;

            $totalGede += $valGede / $dlm_milyar;
        }

        // arrPrint($datas);
        // $kirims = array();
        // $orders = array();
        // foreach ($datas["seller"] as $obj_id => $obj_nama) {
        //     $obj_data_kirim = $datas['kirim'][$obj_id];
        //     $kirims[] = array($obj_nama, $obj_data_kirim);
        //     $obj_data_order = $datas['order'][$obj_id];
        //     $orders[] = array($obj_nama, $obj_data_order);
        // }
        // arrPrintPink($kirims);
        // matiHere(__LINE__);
        // $penjualan_projek = isset($mDatas["penjualan projek"]->kredit) ? $mDatas["penjualan projek"]->kredit : 0;
        // $return_penjualan = isset($mDatas["return penjualan"]->debet) ? $mDatas["return penjualan"]->debet : 0;
        // $penjualan_netto = $mDatas["penjualan"]->kredit - $return_penjualan - $return_penjualan;


        $var = "";

        // $tahun[] = "target";
        // // echo $var;
        // $var["tahun"] = $tahun;
        // arrPrint($kategoriCilik);
        // arrPrint(array_keys($jenis_alias));
        $var["kategoriGede"] = $kategoriGede;
        $var["kategoriCilik"] = $kategoriCilik;
        $var["kategoriCilikAlias"] = array_flip($jenis_alias);
        $var["kategoriGedeAlias"] = ($kategoriGede);
        $var["dataGede"] = $dataGede;
        $var["dataCilik"] = $dataCilik;
        $var["totalGede"] = $totalGede;
        // $var["penjualan"] = $kirims;
        // $var["order"] = $orders;
        // $var["penjualan_total"] = ($penjualan_projek + $penjualan_netto) / $dlm_milyar;
        // $var["target_penjualan_now"] = $target_penjualan_now;
        // $var["target_tahun_now"] = $target_tahun_now;
        // $var["penjualan_now_label"] = dtimeNow('Y');;
        //
        // arrPrintHijau($var);

        $data = array(
            "mode" => "viewSalesDonut",
            "title" => "Penjualan TTM",
            "subTitle" => "",
            "content" => $var,
            "periode" => formatTanggal($ttm_bulan_first, 'd M Y') . " s/d " . formatTanggal($ttm_bulan_last, 'd M Y'),
            // "penjualan_now_label" => $thn_ini,
            "tgl_ini" => $tgl_ini,
            // "penjualan_past_label"       => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
            "container" => 'ttm',
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

//        $file = fopen(__DIR__ . "/static/viewSalesDttm_.txt", "w");
//        fwrite($file, json_encode(array("datetime" => date("Y-m-d H:i:s"), "data" =>$data)));
//        fclose($file);

        $this->load->view("graph", $data);
    }

    public function viewSeller()
    {
        $dlm_milyar = 1000000000;

        /*best of sales*/
        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        $strBln = formatTanggal(previousMonth(), "F");
        $date_1 = formatTanggal(previousMonth(), "Y-m-01");
        $date_2 = formatTanggal(previousMonth(), "Y-m-t");
        // $src_datas = $dt->callPerSeller(previousMonth(), $date_2);
        $src_datas = array();
        // showLast_query("merah");
        // arrPrintPink($src_datas);
        foreach ($src_datas as $item) {
            $seller_id = $item["seller_id"];
            $order = $item["now_saldo_order_all"];
            $reject = $item["now_saldo_reject_all"];
            $closed = $item["now_saldo_closed_all"];
            $kirim = $item["now_saldo_kirim_all"];

            $datas["order"][$seller_id] = $order - $reject - $closed;
            $datas["kirim"][$seller_id] = $kirim;
            $datas["seller"][$seller_id] = $item["seller_nama"];
            $datas["date"] = previousMonth();
        }

        // arrPrint($datas);
        $kirims = array();
        $orders = array();
        foreach ($datas["seller"] as $obj_id => $obj_nama) {
            $obj_data_kirim = $datas['kirim'][$obj_id];
            $kirims[] = array($obj_nama, $obj_data_kirim);
            $obj_data_order = $datas['order'][$obj_id];
            $orders[] = array($obj_nama, $obj_data_order);
        }
        // arrPrintPink($kirims);
        // matiHere(__LINE__);
        $penjualan_projek = isset($mDatas["penjualan projek"]->kredit) ? $mDatas["penjualan projek"]->kredit : 0;
        $return_penjualan = isset($mDatas["return penjualan"]->debet) ? $mDatas["return penjualan"]->debet : 0;
        $penjualan_netto = $mDatas["penjualan"]->kredit - $return_penjualan - $return_penjualan;


        $var = "";

        // $tahun[] = "target";
        // // echo $var;
        // $var["tahun"] = $tahun;
        $var["penjualan"] = $kirims;
        $var["order"] = $orders;
        // $var["penjualan_total"] = ($penjualan_projek + $penjualan_netto) / $dlm_milyar;
        // $var["target_penjualan_now"] = $target_penjualan_now;
        // $var["target_tahun_now"] = $target_tahun_now;
        // $var["penjualan_now_label"] = dtimeNow('Y');;
        //
        // arrPrintHijau($var);

        $data = array(
            "mode" => "viewSellerDonut",
            "title" => "Graph Reports",
            "subTitle" => "",
            "content" => $var,
            "penjualan_now_label" => dtimeNow('Y'),
            // "penjualan_past_label"       => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
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
        $this->load->view("graph", $data);
    }

    public function viewSummary()
    {
        $cabang_id = my_cabang_id();
        switch ($cabang_id) {
            case "-1":
                $boards = array(
                    "1" => array(
                        "rekening" => "kas",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $kas,
                        "label" => "saldo kas",
                        "icon" => "fa-money",
                        "bg" => "bg-green",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                    "5" => array(
                        "rekening" => "penjualan",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "penjualan",
                        "icon" => "fa-money",
                        "bg" => "bg-teal",
                        // "link"            => "Neraca/viewBalanceSheet",
                        // "link_label"      => "Balance Sheet",
                    ),
                    "6" => array(
                        "rekening" => "piutang dagang",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $hutangKepusat,
                        "label" => "piutang dagang",
                        "icon" => "fa-money",
                        "bg" => "bg-purple",
                        // "link"            => "Neraca/viewBalanceSheet",
                        // "link_label"      => "Balance Sheet",
                    ),
                    "2" => array(
                        "rekening" => "piutang cabang",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $kas,
                        "label" => "piutang cabang",
                        "icon" => "fa-money",
                        "bg" => "bg-red",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                    "3" => array(
                        "rekening" => "hutang dagang",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $piutang,
                        "label" => "hutang dagang",
                        "icon" => "fa-money",
                        "bg" => "bg-aqua",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                    "4" => array(
                        "rekening" => "persediaan produk",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $hutangKepusat,
                        "label" => "persediaan produk",
                        "icon" => "fa-money",
                        "bg" => "bg-olive",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),

                );
                break;
            default:
                $boards = array(
                    "1" => array(
                        "rekening" => "kas",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $kas,
                        "label" => "Saldo Kas (IDR)",
                        "icon" => "fa-money",
                        "bg" => "bg-green",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                    "2" => array(
                        "rekening" => "piutang dagang",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $piutang,
                        "label" => "piutang konsumen",
                        "icon" => "fa-money",
                        "bg" => "bg-red",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                    "3" => array(
                        "rekening" => "hutang ke pusat",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "hutang ke pusat",
                        "icon" => "fa-money",
                        "bg" => "bg-aqua",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                    "4" => array(
                        "rekening" => "hutang ke konsumen",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "hutang ke konsumen",
                        "icon" => "fa-money",
                        "bg" => "bg-yellow",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                );
                break;
        }

        /*ngambil data neraca lajur pusat*/
        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();

        $r->addFilter("cabang_id='" . $cabang_id . "'");
        $tmp = $r->fetchAllBalances();
        // showLast_query("merah");
        // arrPrint($tmp);
        //         matiHere(__LINE__);

        /*PENJUALAN - data == yg dipakai pada graph comparasi penjualan*/
        //<editor-fold desc="hanya mengambil total penjualan dari seluruh cabang">
        $this->load->model("Coms/ComRekening");
        $cr = new ComRekening();
        $penjualan_netto_kredit = $cr->callPenjualanNettoNow();

        $penjualan = array();
        $penjualan["penjualan"]["kredit"] = $penjualan_netto_kredit;
        $penjualan["penjualan"]["debet"] = 0;
        $penjualan["penjualan"]["rek_id"] = 0;

        //</editor-fold>

        /*PIUTANG DAGANG*/
        $piutang_dagang = array();
        $koloms = array(
            "rekening",
            "rek_id",
            "kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "piutang dagang",
            "periode" => "forever"
        );
        $this->db->where($condites);
        $tableName = "_rek_master_cache";
        $src_pls = $this->db->get($tableName)->result();

        foreach ($src_pls as $items) {
            $rek_id = $items->rek_id;
            $rekening = $items->rekening;
            $debet = $items->debet;
            $kredit = $items->kredit;

            $penjualan[$rekening]["debet"] = $debet;
            $penjualan[$rekening]["kredit"] = $kredit;
            $penjualan[$rekening]["rek_id"] = $rek_id;
        }

        $this->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();
        /*MTD sales*/
        $condite_rekening = array(
            "582so",
            "582spd",
            "982",
            "382so",
            "382spd",
            "9912",
        );
        $transaksi_tipes = array(
            "reguler", "rejected", "closed", "batal"
        );
        /* -------------------------------------------------------
         * data bulan ini MTD
         * -------------------------------------------------------*/
        // $this->db->where_in("rekening", $condite_rekening);
        // $condites = array(
        //     "date(dtime)>=" => $get_date1,
        //     "date(dtime)<=" => $get_date2,
        //     // "seller_id"    => "65",
        //     // "seller_id"    => "69",
        //     // "seller_id"     => "663",
        //     // "master_id"     => "130358",
        // );
        // $condites_main = array(
        //         // "qty_debet>" => 0,
        //         // "master_id" => "100788",
        //     ) + $seller_id + $master_id;
        // $this->db->where($condites + $condites_main);
        // $this->db->order_by("id", "asc");
        // $srcs_0 = $ps->fetchMovement(true);
        /*MTD order*/
        /*outstanding*/
        /*outstanding AR*/
        /*stok available*/

        $tmps = $tmp + $penjualan;
        // arrPrint($tmps);
        // $kas = $tmp['kas']['debet'];
        // $piutang = $tmp['piutang dagang']['debet'];
        // $hutangKepusat = $tmp['hutang ke pusat']['kredit'];
        $jml = sizeof($boards);
        $var = "";
        $var .= "<style type='text/css'>
                    .small-box .icon {
                        top: -25px;
                    }
                </style>";
        foreach ($boards as $board) {

            $nilai = $tmps[$board["rekening"]][$board["rekening_posisi"]];
            // $nilai = $board["nilai"];
            $nilai_f = formatField2("debet", $nilai);
            $label = $board["label"];
            $box_warna = $board["bg"];
            $link_lager = isset($board["link"]) ? $board["link"] : "";
            $link_label = isset($board["link_label"]) ? $board["link_label"] : "";
            $fa_icon = $board["icon"];
            $lebar_col = 12 / $jml;
            $lebar = $lebar_col > 4 ? "4" : $lebar_col;
            $var .= "<div class='col-lg-$lebar col-xs-6'>
                            <div class='small-box $box_warna'>
                                <div class='inner'>
                                    <h3 style='font-size: 1.5em;'>$nilai_f</h3>     
                                    <p class='text-uppercase' style='font-size: .9em;'>$label</p>
                                </div>
                                <div class='icon'>
                                    <i class='fa $fa_icon ' style='font-size: .5em;' aria-hidden='true'></i>
                                </div>
                                <a href='$link_lager' class='small-box-footer' target='_blank'>$link_label <i class='fa fa-hand-o-right'></i></a>
                            </div>
                        </div>";
        }

        echo $var;
    }

    public function viewSummary_2_ori()
    {
        $cabang_id = my_cabang_id();
        $mb = new MobileDetect();
        $mobil = $mb->isMobile();
        // cekBiru($mob);
        /*ngambil data neraca lajur pusat*/
        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();

        $r->addFilter("cabang_id='" . $cabang_id . "'");

        $tmp = $r->fetchAllBalances();
        //        showLast_query("merah");
        // arrPrint($tmp);
        //         matiHere(__LINE__);

        /*KAS*/
        $kas = array();
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "1010010010",
            "periode" => "forever"
        );
        $this->db->where($condites);
        $tableName = "_rek_master_cache";
        $src_pls = $this->db->get($tableName)->result();
        foreach ($src_pls as $items) {
            $rek_id = $items->rek_id;
            $rekening = $items->rekening;
            $debet = $items->debet;
            $kredit = $items->kredit;

            $kas[$rekening]["debet"] = $debet;
            $kas[$rekening]["kredit"] = $kredit;
            $kas[$rekening]["rek_id"] = $rek_id;
        }

        /*PERSEDIAAN PRODUK*/
        $persediaan_produk = array();
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "1010030030",
            "periode" => "forever"
        );
        $this->db->where($condites);
        $tableName = "_rek_master_cache";
        $src_pls = $this->db->get($tableName)->result();
        foreach ($src_pls as $items) {
            $rek_id = $items->rek_id;
            $rekening = $items->rekening;
            $debet = $items->debet;
            $kredit = $items->kredit;

            $persediaan_produk[$rekening]["debet"] = $debet;
            $persediaan_produk[$rekening]["kredit"] = $kredit;
            $persediaan_produk[$rekening]["rek_id"] = $rek_id;
        }


        /*PENJUALAN - data == yg dipakai pada graph comparasi penjualan*/
        //<editor-fold desc="hanya mengambil total penjualan dari seluruh cabang">
        $this->load->model("Coms/ComRekening");
        $cr = new ComRekening();
        $penjualan_netto_kredit = $cr->callPenjualanNettoNow();
        // showLast_query("here");
        $penjualan = array();
        $penjualan["penjualan"]["kredit"] = $penjualan_netto_kredit;
        $penjualan["penjualan"]["debet"] = 0;
        $penjualan["penjualan"]["rek_id"] = 0;

        //</editor-fold>

        /*PIUTANG DAGANG*/
        $piutang_dagang = array();
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "1010020010",
            "periode" => "forever"
        );
        $this->db->where($condites);
        $tableName = "_rek_master_cache";
        $src_pls = $this->db->get($tableName)->result();
        foreach ($src_pls as $items) {
            $rek_id = $items->rek_id;
            $rekening = $items->rekening;
            $debet = $items->debet;
            $kredit = $items->kredit;

            $penjualan[$rekening]["debet"] = $debet;
            $penjualan[$rekening]["kredit"] = $kredit;
            $penjualan[$rekening]["rek_id"] = $rek_id;
        }


        /*MTD sales*/
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "thn" => dtimeNow('Y'),
            "bln" => dtimeNow('m'),
            "periode" => "bulanan",
            "rekening" => "4010"
        );
        $this->db->where($condites);
        // $conditeins = array(
        //     "penjualan",
        //     "return penjualan",
        //     "penjualan projek",
        // );
        // $this->db->where_in("rekening", $conditeins);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->row();
        // showLast_query("kuning");
        // arrPrintHijau($src_datas);
        $penjualan['penjualan_mtd']["debet"] = $src_datas->debet * 1;
        $penjualan['penjualan_mtd']["kredit"] = ($src_datas->kredit - $src_datas->debet);
        $penjualan['penjualan_mtd']["rek_id"] = $src_datas->rek_id;

        /*MTD order neto local-export-project*/
        $src_datas = $this->get_z_sales_cache();
        // showLast_query("kuning");
        // arrPrintHijau($src_datas);
        $penjualan['order_mtd']["debet"] = $src_datas->netto * 1;
        $penjualan['order_mtd']["kredit"] = 0 * 1;
        $penjualan['order_mtd']["rek_id"] = "";

        /*outstanding*/
        $penjualan['outstanding']["debet"] = 0 * 1;
        $penjualan['outstanding']["kredit"] = $src_datas->outstanding;
        $penjualan['outstanding']["rek_id"] = 0;

        /*sales_ytd*/
        $penjualan['sales_ytd']["debet"] = 0 * 1;
        $penjualan['sales_ytd']["kredit"] = $src_datas->kirim;
        $penjualan['sales_ytd']["rek_id"] = 0;

        /*outstanding AR = piutang dagang*/
        /*stok available = persediaan*/

        /*best of sales*/
        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        $strBln = formatTanggal(previousMonth(), "F");
        $date_1 = formatTanggal(previousMonth(), "Y-m-01");
        $date_2 = formatTanggal(previousMonth(), "Y-m-t");
        $src = $dt->callPerSeller(previousMonth(), $date_2);
        // return (object)$xx;
        //        cekHere("$date_1 -- $date_2");
        // arrPrint($src);
        $datas = array();
        foreach ($src as $item) {
            //            arrPrint($item);
            $seller_id = $item["seller_id"];
            $order = $item["now_saldo_order_all"];
            $reject = $item["now_saldo_reject_all"];
            $closed = $item["now_saldo_closed_all"];
            $kirim = $item["now_saldo_kirim_all"];
            $batal = $item["now_saldo_batal_kirim_all"];
            $return = $item["now_saldo_return_kirim_all"];

            $datas["order"][$seller_id] = $order - $reject - $closed - $batal - $return;
            $datas["kirim"][$seller_id] = $kirim - $batal - $return;
            $datas["seller"][$seller_id] = $item["seller_nama"];
            $datas["date"] = previousMonth();
        }

        // array_multisort($datas);
        //         arrPrint($datas["order"]);
        $sellers = $datas["seller"];
        $orders = $datas["order"];
        $kirims = $datas["kirim"];
        asort($kirims);
        asort($orders);
        // arrPrintHijau($kirims);
        // arrPrintHijau($orders);
        // cekHijau(end($kirims));
        // arrPrintKuning(key($kirims));

        $nilai_kirim = end($kirims);
        $seller_kirim = $sellers[key($kirims)];
        $nilai_order = end($orders);
        $seller_order = $sellers[key($orders)];

        $best_of_salesman["order"]["kredit"] = $nilai_order;
        $best_of_salesman["sales"]["kredit"] = $nilai_kirim;

        //        $tmps = $tmp + $penjualan + $best_of_salesman;
        $tmps = $penjualan + $best_of_salesman + $kas + $persediaan_produk;

        switch ($cabang_id) {
            case "-1":
                $boards = array(
                    "1" => array(
                        "rekening" => "1010010010", // kas
                        "rekening_posisi" => "debet",
                        // "nilai"      => $kas,
                        "label" => "saldo kas <br>" . dtimeNow('d M Y H:i') . "&nbsp;",
                        "icon" => "fa-money",
                        "bg" => "bg-olive",
                        //                        "link" => "Neraca/viewBalanceSheet",
                        //                        "link_label" => "Balance Sheet",
                        "link" => "Neraca/viewNeracaYearToDate_consolidated/1010010010",
                        "link_label" => "NERACA YTD",
                    ),
                    // "5" => array(
                    //     "rekening"        => "penjualan",
                    //     "rekening_posisi" => "kredit",
                    //     // "nilai"      => $hutangKepusat,
                    //     "label"           => "penjualan",
                    //     "icon"            => "fa-money",
                    //     "bg"              => "bg-teal",
                    //     // "link"            => "Neraca/viewBalanceSheet",
                    //     // "link_label"      => "Balance Sheet",
                    // ),
                    "6" => array(
                        "rekening" => "1010020010", //piutang dagang
                        "rekening_posisi" => "debet",
                        // "nilai"      => $hutangKepusat,
                        // "label"           => "piutang dagang",
                        "label" => "outstanding AR <br>" . dtimeNow('d M Y H:i') . "&nbsp;",
                        "icon" => "fa-money",
                        "bg" => "bg-green",
                        // "link"            => "Neraca/viewBalanceSheet",
                        // "link_label"      => "Balance Sheet",
                        "link" => "Neraca/viewNeracaYearToDate_consolidated/1010020010",
                        "link_label" => "NERACA YTD",
                    ),
                    "4" => array(
                        "rekening" => "1010030030",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $hutangKepusat,
                        "label" => "persediaan produk <br>" . dtimeNow('d M Y H:i') . "&nbsp;",
                        "icon" => "fa-money",
                        "bg" => "bg-yellow",
                        //                        "link" => "Neraca/viewBalanceSheet",
                        //                        "link_label" => "Balance Sheet",
                        "link" => "Neraca/viewNeracaYearToDate_consolidated/1010030030",
                        "link_label" => "NERACA YTD",
                    ),
                    "7" => array(
                        "rekening" => "penjualan_mtd",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "penjualan MTD <br>" . dtimeNow('d M') . "&nbsp;",
                        "icon" => "fa-money",
                        // "bg"              => "bg-teal",
                        "bg" => "bg-fuchsia",
                        "link" => "Rugilaba/viewPLConsolidated/mtd?gr=czo1MToibGFwb3JhbmtldWFuZ2Fua29uc29saWRhc2lpbnRlcm5hbC1ybGNvbmludGVybmFsbXRkIjs=",
                        "link_label" => "MTD (CONSOLIDATED)",
                    ),
                    "8" => array(
                        "rekening" => "order_mtd",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $hutangKepusat,
                        "label" => "order MTD " . dtimeNow('d M') . "<br>&nbsp;",
                        "icon" => "fa-money",
                        "bg" => "bg-red",
                        // "link"            => "Neraca/viewBalanceSheet",
                        "link" => "laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==",
                        "link_label" => "Order MTD ",
                    ),
                    "12" => array(
                        "rekening" => "sales_ytd",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "Sales <br>(" . dtimeNow('d M Y') . ")&nbsp;",
                        "satuan" => $strBln,
                        "icon" => " fa-thumbs-o-up",
                        // "bg"              => "bg-teal",
                        "bg" => "bg-blue",
                        "link" => "laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==",
                        "link_label" => "outstanding",
                    ),
                    "9" => array(
                        "rekening" => "outstanding",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "order outstanding<br>(versi packinglist)",
                        "icon" => "fa-money",
                        "bg" => "bg-aqua",
                        // "link"            => "laporan/Crm/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==",
                        "link" => "laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==",
                        "link_label" => "outstanding",
                    ),
                    // "2" => array(
                    //     "rekening"        => "piutang cabang",
                    //     "rekening_posisi" => "debet",
                    //     // "nilai"      => $kas,
                    //     "label"           => "piutang cabang",
                    //     "icon"            => "fa-money",
                    //     "bg"              => "bg-red",
                    //     "link"            => "Neraca/viewBalanceSheet",
                    //     "link_label"      => "Balance Sheet",
                    // ),
                    // "3" => array(
                    //     "rekening"        => "hutang dagang",
                    //     "rekening_posisi" => "kredit",
                    //     // "nilai"      => $piutang,
                    //     "label"           => "hutang dagang",
                    //     "icon"            => "fa-money",
                    //     "bg"              => "bg-aqua",
                    //     "link"            => "Neraca/viewBalanceSheet",
                    //     "link_label"      => "Balance Sheet",
                    // ),
                    "10" => array(
                        "rekening" => "order",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $kas,
                        "label" => "$seller_order <br>(" . formatTanggal(previousMonth(), 'M Y') . ")",
                        "satuan" => $strBln,
                        "icon" => " fa-thumbs-o-up",
                        "bg" => "bg-light-blue",
                        // https://san.mayagrahakencana.com/laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==
                        "link" => "laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==&date1=$date_1&date2=$date_2",
                        "link_label" => "Best Order of The Month",
                    ),
                    "11" => array(
                        "rekening" => "sales",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "$seller_kirim <br>(" . formatTanggal(previousMonth(), 'M Y') . ")&nbsp;",
                        "satuan" => $strBln,
                        "icon" => " fa-thumbs-o-up",
                        // "bg"              => "bg-teal",
                        "bg" => "bg-blue",
                        "link" => "laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==&date1=$date_1&date2=$date_2",
                        "link_label" => "Best Sales of The Month",
                    ),
                );
                break;
            default:
                $boards = array(
                    "1" => array(
                        "rekening" => "kas",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $kas,
                        "label" => "Saldo Kas (IDR)",
                        "icon" => "fa-money",
                        "bg" => "bg-green",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                    "2" => array(
                        "rekening" => "piutang dagang",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $piutang,
                        "label" => "piutang konsumen",
                        "icon" => "fa-money",
                        "bg" => "bg-red",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                    "3" => array(
                        "rekening" => "hutang ke pusat",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "hutang ke pusat",
                        "icon" => "fa-money",
                        "bg" => "bg-aqua",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                    "4" => array(
                        "rekening" => "hutang ke konsumen",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "hutang ke konsumen",
                        "icon" => "fa-money",
                        "bg" => "bg-yellow",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                );
                break;
        }
        // arrPrint($tmps);
        // mati_disini(__LINE__);
        // $kas = $tmp['kas']['debet'];
        // $piutang = $tmp['piutang dagang']['debet'];
        // $hutangKepusat = $tmp['hutang ke pusat']['kredit'];
        $jml = sizeof($boards);
        $var = "";
        $var .= "<style type='text/css'>
                    .small-box .icon {
                        top: -25px;
                    }                    
                    // .col-lg-1-5{
                    //     width: 12.5%;
                    //     padding-right: 0 !important;
                    // }
                    @media (min-width: 1200px) {
                    .col-lg-1-5{
                        // width: 12.5%;
                        width: 11.11%;
                        padding-right: 0 !important;
                    }
                    }
                </style>";
        $count = 0;
        foreach ($boards as $board) {
            $count++;
            $gaya = $mobil == 0 && $count == $jml ? "style='padding-right: 10px !important;'" : "";
            $nilai = $tmps[$board["rekening"]][$board["rekening_posisi"]];
            // $nilai = $board["nilai"];
            $nilai_f = formatField2("debet", $nilai);
            $label = $board["label"];
            $box_warna = $board["bg"];
            $link_lager = isset($board["link"]) ? $board["link"] : "";
            $link_label = isset($board["link_label"]) ? $board["link_label"] : "";
            $fa_icon = $board["icon"];
            $lebar_col = 12 / $jml;
            // cekHijau($lebar_col);
            $lebar = $lebar_col > 4 ? "4" : $lebar_col;
            $lebar = "1-5";
            $var .= "<div class='col-lg-$lebar col-xs-6' $gaya>
                            <div class='small-box $box_warna'>
                                <div class='inner'>
                                    <h3 style='font-size: 1.2em;'>$nilai_f</h3>     
                                    <p class='text-uppercase' style='font-size: .8em;'>$label</p>
                                </div>
                                <div class='icon'>
                                    <i class='fa $fa_icon ' style='font-size: .5em;' aria-hidden='true'></i>
                                </div>
                                <a href='$link_lager' class='small-box-footer' target='_blank'>$link_label <i class='fa fa-hand-o-right'></i></a>
                            </div>
                        </div>";
        }

        echo $var;
    }

    public function viewSummary_2()
    {
        $cabang_id = my_cabang_id();
        $mb = new MobileDetect();
        $mobil = $mb->isMobile();


        // --------------------------LAST-TRANSAKSI-ID-----------------------------------------------
        // $this->load->model("MdlTransaksi");
        // $tr = new MdlTransaksi();
        // $condites = array(
        //   "link_id" => 0,
        // );
        // $this->db->limit(1);
        // $this->db->order_by("id","desc");
        // $this->db->select("id");
        // $src_tr = $tr->lookupByCondition($condites)->row();
        // $last_tr_id = $src_tr->id;
        $last_tr_id = $this->cekLastTransaksiId();
        // $last_tr_id = "194305";
        // showLast_query("biru");
        // arrPrintPink($src_tr);
        // cekBiru(info_debuger());
        // ---------------------------CONFIG-WEB--------------------------------------------------
        // $this->load->config("heWebs");
        // $con_webs = $this->config->item("mongo");
        // // arrPrintKuning($con_webs);
        // $mdb_connection = isset($con_webs["connection"]) ? $con_webs["connection"] : false;
        $mdb_connection = $this->MdbConnection();

        // ---------------------------MONGO-DB------------------------------------------------
        if ($mdb_connection == true) {

            $this->load->library("Mongo_db");
            $mg = new Mongo_db();
            $coll_nama = "dashboard_summary";
            // ---------------------------------------------------------------------------
            $mg->limit(1);
            $mg->order_by(array("_id" => "desc"));
            // $mg->
            $src_mdb = $mg->get($coll_nama);
            $jml_data_mdb = sizeof($src_mdb);
            $tmps = $src_mdb[0];
        }
        $key_mdb = "last_trid";
        $mdb_last_trid = key_exists($key_mdb, $tmps) ? $tmps[$key_mdb] : 0;


        // cekBiru("id terakhir $last_tr_id vs $mdb_last_trid (mdb)");
        // ---------------------------------------------------------------------------
        // arrPrintPink($tmps);

        // mati_disini(__LINE__ . " || $jml_data_mdb");
        $jml_data_mdb = 0;
        if (($jml_data_mdb == 1) && ($mdb_last_trid == $last_tr_id)) {
            cekHere(__LINE__ . " tidak ada perbaruan data");
            $tmps = $src_mdb[0];
        } else {
            // mati_disini(__LINE__ . " perbaruan data");
            cekHere(__LINE__ . " perbaruan data");
            cekHitam("pembaharuan data");
            $tmps = $this->callSummary($cabang_id, $last_tr_id);

            // -----------------------------------------------------------------
            /*NYIMPEN DATA KE TABLE DASHBOARD BIAR LOADNYA CEPAT*/
            // $this->load->library("Mongo_db");
            // $mg = new Mongo_db();
            // $coll_nama = "dashboard_summary";
            if ($mdb_connection == true) {
                $mg->insert($coll_nama, $tmps);
            }
            // ---------------------------------------------------------------
        }
        // cekBiru($mob);

        // arrPrintKuning($tmps);
        $sub_param = isset($tmps["sub_param"]) ? $tmps["sub_param"] : array();
        $dtime_data = $tmps["dtime"];
        $dtime_stamp_data = $tmps["dtime_stamp"];
        $strBln = isset($sub_param["str_bln"]) ? $sub_param["str_bln"] : "-";
        $date_1 = isset($sub_param["date_1"]) ? $sub_param["date_1"] : "-";
        $date_2 = isset($sub_param["date_2"]) ? $sub_param["date_2"] : "-";
        $previous_month = isset($sub_param["previous_month"]) ? $sub_param["previous_month"] : "-";

        $seller_order = "{seller_nama}";
        $seller_kirim = "{seller_nama}";
        switch ($cabang_id) {
            case "-1":
                $boards = array(
                    "1" => array(
                        "rekening" => "1010010010", // kas
                        "rekening_posisi" => "debet",
                        // "nilai"      => $kas,
                        "label" => "saldo kas <br>" . formatTanggal($dtime_data, 'd M Y H:i') . "&nbsp;",
                        "icon" => "fa-money",
                        "bg" => "bg-olive",
                        //                        "link" => "Neraca/viewBalanceSheet",
                        //                        "link_label" => "Balance Sheet",
                        "link" => "Neraca/viewNeracaYearToDate_consolidated/1010010010",
                        "link_label" => "NERACA YTD",
                    ),
                    // "5" => array(
                    //     "rekening"        => "penjualan",
                    //     "rekening_posisi" => "kredit",
                    //     // "nilai"      => $hutangKepusat,
                    //     "label"           => "penjualan",
                    //     "icon"            => "fa-money",
                    //     "bg"              => "bg-teal",
                    //     // "link"            => "Neraca/viewBalanceSheet",
                    //     // "link_label"      => "Balance Sheet",
                    // ),
                    "6" => array(
                        "rekening" => "1010020010", //piutang dagang
                        "rekening_posisi" => "debet",
                        // "nilai"      => $hutangKepusat,
                        // "label"           => "piutang dagang",
                        "label" => "outstanding AR <br>" . formatTanggal($dtime_stamp_data, 'd M Y H:i') . "&nbsp;",
                        "icon" => "fa-money",
                        "bg" => "bg-green",
                        // "link"            => "Neraca/viewBalanceSheet",
                        // "link_label"      => "Balance Sheet",
                        "link" => "Neraca/viewNeracaYearToDate_consolidated/1010020010",
                        "link_label" => "NERACA YTD",
                    ),
                    "4" => array(
                        "rekening" => "1010030030",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $hutangKepusat,
                        "label" => "persediaan produk <br>" . formatTanggal($dtime_stamp_data, 'd M Y H:i') . "&nbsp;",
                        "icon" => "fa-money",
                        "bg" => "bg-yellow",
                        //                        "link" => "Neraca/viewBalanceSheet",
                        //                        "link_label" => "Balance Sheet",
                        "link" => "Neraca/viewNeracaYearToDate_consolidated/1010030030",
                        "link_label" => "NERACA YTD",
                    ),
                    "7" => array(
                        "rekening" => "penjualan_mtd",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "penjualan MTD <br>" . formatTanggal($dtime_stamp_data, 'd M') . "&nbsp;",
                        "icon" => "fa-money",
                        // "bg"              => "bg-teal",
                        "bg" => "bg-fuchsia",
                        "link" => "Rugilaba/viewPLConsolidated/mtd?gr=czo1MToibGFwb3JhbmtldWFuZ2Fua29uc29saWRhc2lpbnRlcm5hbC1ybGNvbmludGVybmFsbXRkIjs=",
                        "link_label" => "MTD (CONSOLIDATED)",
                    ),
                    "8" => array(
                        "rekening" => "order_mtd",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $hutangKepusat,
                        "label" => "order MTD " . formatTanggal($dtime_stamp_data, 'd M') . "<br>&nbsp;",
                        "icon" => "fa-money",
                        "bg" => "bg-red",
                        // "link"            => "Neraca/viewBalanceSheet",
                        "link" => "laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==",
                        "link_label" => "Order MTD ",
                    ),
                    "12" => array(
                        "rekening" => "sales_ytd",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "Sales <br>(" . formatTanggal($dtime_stamp_data, 'd M Y') . ")&nbsp;",
                        "satuan" => $strBln,
                        "icon" => " fa-thumbs-o-up",
                        // "bg"              => "bg-teal",
                        "bg" => "bg-blue",
                        "link" => "laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==",
                        "link_label" => "outstanding",
                    ),
                    "9" => array(
                        "rekening" => "outstanding",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "order outstanding<br>(versi packinglist)",
                        "icon" => "fa-money",
                        "bg" => "bg-aqua",
                        // "link"            => "laporan/Crm/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==",
                        "link" => "laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==",
                        "link_label" => "outstanding",
                    ),
                    // "2" => array(
                    //     "rekening"        => "piutang cabang",
                    //     "rekening_posisi" => "debet",
                    //     // "nilai"      => $kas,
                    //     "label"           => "piutang cabang",
                    //     "icon"            => "fa-money",
                    //     "bg"              => "bg-red",
                    //     "link"            => "Neraca/viewBalanceSheet",
                    //     "link_label"      => "Balance Sheet",
                    // ),
                    // "3" => array(
                    //     "rekening"        => "hutang dagang",
                    //     "rekening_posisi" => "kredit",
                    //     // "nilai"      => $piutang,
                    //     "label"           => "hutang dagang",
                    //     "icon"            => "fa-money",
                    //     "bg"              => "bg-aqua",
                    //     "link"            => "Neraca/viewBalanceSheet",
                    //     "link_label"      => "Balance Sheet",
                    // ),
                    "10" => array(
                        "rekening" => "order",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $kas,
                        "label" => "$seller_order <br>(" . formatTanggal($previous_month, 'M Y') . ")",
                        "satuan" => $strBln,
                        "icon" => " fa-thumbs-o-up",
                        "bg" => "bg-light-blue",
                        // https://san.mayagrahakencana.com/laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==
                        "link" => "laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==&date1=$date_1&date2=$date_2",
                        "link_label" => "Best Order of The Month",
                    ),
                    "11" => array(
                        "rekening" => "sales",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "$seller_kirim <br>(" . formatTanggal($previous_month, 'M Y') . ")&nbsp;",
                        "satuan" => $strBln,
                        "icon" => " fa-thumbs-o-up",
                        // "bg"              => "bg-teal",
                        "bg" => "bg-blue",
                        "link" => "laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==&date1=$date_1&date2=$date_2",
                        "link_label" => "Best Sales of The Month",
                    ),
                );
                break;
            default:
                $boards = array(
                    "1" => array(
                        "rekening" => "kas",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $kas,
                        "label" => "Saldo Kas (IDR)",
                        "icon" => "fa-money",
                        "bg" => "bg-green",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                    "2" => array(
                        "rekening" => "piutang dagang",
                        "rekening_posisi" => "debet",
                        // "nilai"      => $piutang,
                        "label" => "piutang konsumen",
                        "icon" => "fa-money",
                        "bg" => "bg-red",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                    "3" => array(
                        "rekening" => "hutang ke pusat",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "hutang ke pusat",
                        "icon" => "fa-money",
                        "bg" => "bg-aqua",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                    "4" => array(
                        "rekening" => "hutang ke konsumen",
                        "rekening_posisi" => "kredit",
                        // "nilai"      => $hutangKepusat,
                        "label" => "hutang ke konsumen",
                        "icon" => "fa-money",
                        "bg" => "bg-yellow",
                        "link" => "Neraca/viewBalanceSheet",
                        "link_label" => "Balance Sheet",
                    ),
                );
                break;
        }
        // arrPrint($tmps);
        // mati_disini(__LINE__);
        // $kas = $tmp['kas']['debet'];
        // $piutang = $tmp['piutang dagang']['debet'];
        // $hutangKepusat = $tmp['hutang ke pusat']['kredit'];
        $jml = sizeof($boards);
        $var = "";
        $var .= "<style type='text/css'>
                    .small-box .icon {
                        top: -25px;
                    }                    
                    // .col-lg-1-5{
                    //     width: 12.5%;
                    //     padding-right: 0 !important;
                    // }
                    @media (min-width: 1200px) {
                    .col-lg-1-5{
                        // width: 12.5%;
                        width: 11.11%;
                        padding-right: 0 !important;
                    }
                    }
                </style>";
        $count = 0;
        foreach ($boards as $board) {
            $count++;
            $gaya = $mobil == 0 && $count == $jml ? "style='padding-right: 10px !important;'" : "";
            $board_rekening = $board["rekening"];
            // cekMerah();
            if (isset($tmps[$board_rekening]["oleh_nama"])) {
                $nama = $tmps[$board_rekening]["oleh_nama"];
                // cekHijau($board_rekening);
                $label = str_replace("{seller_nama}", "$nama", $board["label"]);
            } else {
                $label = $board["label"];
            }
            $nilai = $tmps[$board["rekening"]][$board["rekening_posisi"]];
            // $nilai = $board["nilai"];
            $nilai_f = formatField2("debet", $nilai);
            // $label = $board["label"];
            $box_warna = $board["bg"];
            $link_lager = isset($board["link"]) ? $board["link"] : "";
            $link_label = isset($board["link_label"]) ? $board["link_label"] : "";
            $fa_icon = $board["icon"];
            $lebar_col = 12 / $jml;
            // cekHijau($lebar_col);
            $lebar = $lebar_col > 4 ? "4" : $lebar_col;
            $lebar = "1-5";
            $var .= "<div class='col-lg-$lebar col-xs-6' $gaya>
                            <div class='small-box $box_warna'>
                                <div class='inner'>
                                    <h3 style='font-size: 1.2em;'>$nilai_f</h3>     
                                    <p class='text-uppercase' style='font-size: .8em;'>$label</p>
                                </div>
                                <div class='icon'>
                                    <i class='fa $fa_icon ' style='font-size: .5em;' aria-hidden='true'></i>
                                </div>
                                <a href='$link_lager' class='small-box-footer' target='_blank'>$link_label <i class='fa fa-hand-o-right'></i></a>
                            </div>
                        </div>";
        }

        echo $var;
    }

    /*---*/
    public function viewJmlNotaBulanan()
    {
        $pembagi = $dlm_juta = 1000000;

        /* --------------------------------------
         * Pilihan tahu dan bulan yg akan dilihat
         * --------------------------------------*/
        $get_thn = dtimeNow("Y");
        $get_bln = dtimeNow('m');
        $get_tgl_last = dtimeNow('t');
        $get_tgl = dtimeNow('d');
        $get_tgl_now = dtimeNow('Y-m-d');
        $get_bln_now = dtimeNow('Y-m');
        $get_thn_bln = "$get_thn-$get_bln";

        // $condites = array(
        //     "thn>=" => $get_thn,
        //     "bln>=" => "05",
        // );
        // $this->db->where($condites);

        //tes nambah cabang
        if (my_cabang_id() != -1) {
            $this->db->where("cabang_id", my_cabang_id());
        }

        $dipakai = false;
        if ($dipakai == true) {
            // cekOrange();
            $this->load->model("Mdls/MdlTransaksiConsolidasi");
            $rp = new MdlTransaksiConsolidasi();
            $sumHarian = $dd = $rp->callSummaryJmlNotaBulanan();
        } else {

            $this->load->model("Mdls/MdlMasterCache");
            $rp = new MdlMasterCache();
            $sumHarian = $dd = $rp->callPenjulanPeriode("harian");
        }
        // showLast_query("kuning");
        // cekHere(count($sumHarian));

        $jual_hari_ini = 0;
        foreach ($sumHarian as $params) {
            $thn = $params->thn;
            $bln = $params->bln;
            $tgl = $params->tgl;
            $thn_bln = "$thn-$bln";
            $thn_bln_tgl = "$thn-$bln-$tgl";
            $id = $params->id;
            $tr_kredit = $params->kredit;
            $tr_debet = $params->debet;
            $tr_saldo_kredit = $params->saldo_kredit;
            $tr_saldo_debet = $params->saldo_debet;
            $ccc = $tr_saldo_kredit - $tr_saldo_debet;

            $tr_nilai = $tr_kredit;
// cekHere("$tr_kredit $tr_saldo_kredit - $tr_saldo_debet = $ccc");
            if (!isset($data_transaksi[$thn_bln])) {
                $data_transaksi[$thn_bln] = 0;
            }
            $data_transaksi[$thn_bln] += $tr_nilai;
            $data_nota[$thn_bln][] = $id;

            if ($thn_bln == $get_bln_now) {
                if (!isset($cekPerhari[$thn_bln_tgl])) {
                    $cekPerhari[$thn_bln_tgl] = 0;
                }
                $cekPerhari[$thn_bln_tgl] += $tr_nilai;
            }

            if ($thn_bln_tgl == $get_tgl_now) {
                // cekHijau("$thn_bln_tgl");
                $tgl_kemarin = $tgl - 1;
                $jual_hari_ini += $tr_nilai;
            }
        }

        // cekPink("bln_ini: " . $hrBlnIni . " |");
        // cekHijau("bln_ini: " . $data_transaksi["2023-07"] . " | tgl_ini: " . $jual_hari_ini . " | bln_ini - tgl_ini: " . ($data_transaksi["2023-07"] - $jual_hari_ini));

        foreach ($data_nota as $label_bln => $params) {
            $jml_nota[$label_bln] = count($params);
        }

        ksort($jml_nota);
        // arrPrintKuning($data_transaksi);
        // arrPrintPink($jml_nota);
        // matiDisini(__LINE__);
        $jual = array();
        $hpp = array();
        $rl = array();
        $label = array();
        $jual_full_month = array();
        $bln_nama = "";
        $total_jual = 0;
        if (count($jml_nota) > 0) {
            foreach ($jml_nota as $tanggal => $xx_0) {
                $anggal_f = formatTanggal("$tanggal-01", "Y F");
                $anggal_last = formatTanggal("$tanggal-01", "t");
                // cekMerah($xx_0);
                $jual_nilai = $data_transaksi[$tanggal] / $dlm_juta;

                $jual_avg = $tanggal != $get_thn_bln ? ($jual_nilai / $anggal_last) : ($jual_nilai / $get_tgl);

                $nota[] = $xx_0;
                // $jual[] = $jual_nilai;
                $hpp[] = 0;
                $rl[] = 0;
                // $tgl = $xx_0->tgl;
                // $bln = $xx_0->bln;
                // $thn = $xx_0->thn;
                // $tanggal = "$thn-$bln-$tgl";

                // cekMerah("$tanggal | $anggal_last");
                $label[] = $anggal_f;

                if ($tanggal != $get_thn_bln) {
                    $jual_harian[] = $jual_avg;
                    $jual[] = $jual_nilai;
                    $total_jual += $jual_nilai;
                    $jual_full_month[] = $jual_nilai;
                } else {
                    /* -------------------------------------------------------------------------------
                     * penjualan bulan ini sampai tgl kemarin
                     * untuk ngitung rata2 harian harus hari penuh (hari ini tidak diperhitungkan)
                     * -------------------------------------------------------------------------------*/
                    $jual_kemarin = $jual_nilai - ($jual_hari_ini / $dlm_juta);
                    // $jual_nilai_prediksi = $jual_nilai * ($get_tgl_last / $get_tgl);
                    $jual_nilai_prediksi = $jual_kemarin * ($get_tgl_last / ($get_tgl - 1));

                    // cekBiru("$jual_kemarin = $jual_nilai - ($jual_hari_ini / $dlm_juta);");
                    // cekHere("(H-1) $jual_nilai_prediksi = $jual_kemarin * ($get_tgl_last / ($get_tgl - 1))");

                    $jual_harian[] = $jual_kemarin / ($get_tgl - 1);
                    // $jual[] = array("y" => $jual_kemarin, "color" => "#808080b3");
                    $jual[] = array("y" => $jual_nilai, "color" => "#808080b3");
                    $jual[] = array("y" => $jual_nilai_prediksi, "color" => "#7abcf98c");

                    // cekHijau($get_tgl_last);
                }
            }
            $label[] = "prediksi $anggal_f";
            // $bln_nama = formatTanggal($tanggal, "F Y");
            // cekHere("$total_jual");
            // arrPrintPink($jual_full_month);
            // arrPrintPink($jual_harian);
        }
        $var = "";

        // $var["jml_nota"] = $nota;
        $var["nota_avg"] = ($nota) > 0 ? round((array_sum($nota) / count($nota)), 2) : 0;
        $var["penjualan"] = $jual;
        $var["penjualan_avg_bulan"] = $jual_harian;
        $var["hpp"] = $hpp;
        $var["labarugi"] = $rl;
        $var["label"] = $label;
        $var["penjualan_avg"] = (($jual > 0) && (count($jual_full_month) > 0)) ? round((array_sum($jual_full_month) / count($jual_full_month)), 2) : 0;

        $data = array(
            "mode" => "viewJmlNotaBulanan",
            "title" => "Grafik Penjualan Bulanan (juta) x Jumlah Nota",
            "subTitle" => "",
            "content" => $var,
            "penjualan_now_label" => dtimeNow('Y'),
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            "periode" => '',
            "pembagi" => $pembagi,
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
        $this->load->view("graph", $data);
    }

    /*--Grafik Penjualan Harian-*/
    public function viewPenjualanHarian()
    {
        $judul = "Grafik Penjualan Harian";
        $dlm_juta = 1000000;
        // cekHere();
        $this->load->library("JoinRekening/MasterCache");
        $xx = new MasterCache();
        // $dd = $xx->CallSaldoPenjualanHarian();
        // showLast_query("hijau");
        arrPrintKuning($_GET);
        /* --------------------------------------
         * Pilihan tahu dan bulan yg akan dilihat
         * --------------------------------------*/
        $get_thn = dtimeNow("Y");
        $get_bln = dtimeNow('m');

        $this->load->model("Mdls/MdlSettUx");
        $ux = new MdlSettUx();
        $v_param = isset($_GET['v']) ? $_GET['v'] : '';

        // Jika parameter v kosong, coba load state terakhir dari database set_ux
        if ($v_param === '') {
            $uxCondites = array(
                "employee_id" => my_id(),
                "menu" => "dahboard",
                "judul" => "Grafik Penjualan Harian",
            );
            $uxsatas = $ux->lookupByCondition($uxCondites)->row();
            if (count($uxsatas) > 0) {
                $v_param = $uxsatas->default_key;
            } else {
                $v_param = '32'; // Default awal jika belum ada data di database
            }
        }

        if ($v_param !== 'all') {
            if (strpos($v_param, 'm') !== false) {
                // Filter berbasis bulan (misal: 3m, 6m, 12m)
                $months_back = intval(str_replace('m', '', $v_param));
                // Ambil tanggal 1 dari N bulan yang lalu agar datanya utuh sebulan penuh
                $get_date = date('Y-m-01', strtotime("-$months_back month"));
            } else {
                // Filter berbasis hari (misal: 8, 15, 32)
                $v_num = intval($v_param);
                $xdate = after_x_Date("", ($v_num * -1));
                $get_date = $xdate;
            }

            /*-----menyinpan last UX-----*/
            $ux->setCondites(
                array(
                    "menu" => "dahboard",
                    "judul" => "Grafik Penjualan Harian",
                    "employee_nama" => my_name(),
                )
            );
            // Simpan v_param asli (misal: 3m, 6m, 32, dll) agar mudah dibaca kembali saat reload
            $ux->simpanUx(my_id(), $v_param, $get_date);
        } else {
            $get_date = "";

            // Simpan juga state 'all' ke database agar konsisten saat direload
            $ux->setCondites(
                array(
                    "menu" => "dahboard",
                    "judul" => "Grafik Penjualan Harian",
                    "employee_nama" => my_name(),
                )
            );
            $ux->simpanUx(my_id(), 'all', '');
        }
        $fillBtnChart = "#ffffff";

        $is_bulanan = false;
        if ($get_date !== "") {
            $tgl_sekarang = dtimeNow('Y-m-d');
            $selisih_detik = strtotime($tgl_sekarang) - strtotime($get_date);
            $selisih_hari = round($selisih_detik / 86400);
            if ($selisih_hari > 60 || strpos($v_param, 'm') !== false) {
                $is_bulanan = true;
            }
        } else {
            // Jika memilih seluruh data, otomatis menjadi tampilan bulanan
            $is_bulanan = true;
        }

        $judul = $is_bulanan ? "Grafik Penjualan Bulanan" : "Grafik Penjualan Harian";

        arrPrintWebs($get_date);
        $dipakai = false;
        if ($dipakai == true) {
            $this->load->model("Mdls/MdlRawPenjualan");
            $rp = new MdlRawPenjualan();
            $condites = array(
                // "thn>=" => $get_thn,
                // "bln>=" => "05",
                "fulldate>=" => $get_date,
                // "tgl>=" => "29",
            );
            $this->db->where($condites);

            //tes nambah cabang
            if (my_cabang_id() != -1) {
                $this->db->where("cabang_id", my_cabang_id());
            }

            $sumHarian = $dd = $rp->callSummaryHarian();
//            showLast_query("hijau");
        } else {
            $this->load->model("Mdls/MdlMasterCache");
            $rp = new MdlMasterCache();

            if (my_cabang_id() != -1) {
                $this->db->where("cabang_id", my_cabang_id());
            }
            if ($get_date !== "") {
                $this->db->where("date(dtime) >=", $get_date);
            }

            $this->db->order_by("dtime ASC");
            $sumHarian_00 = $dd = $rp->callPenjulanPeriode("harian");

            foreach ($sumHarian_00 as $xx => $item_cb) {
                $arr_data = (array)$item_cb;
                $arr_data_new["sum_debet"] = $item_cb->saldo_debet;
                $arr_data_new["sum_kredit"] = $item_cb->saldo_kredit;
                $arr_data_new["sum_hpp"] = 0;
                $sumHarian[] = (object)($arr_data + $arr_data_new);
                // $sumHarianCabang[$cb_id][] =  $arr_data_new;
            }
        }

//        showLast_query("hijau");
// arrPrintPink($sumHarian);


        /*
         * cabang
         * */
        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $condite_cbs = array(
            "id>" => 0,
            "jenis" => "cabang"
        );
        $src_cbs = $cb->lookupByCondition($condite_cbs)->result();
        // showLast_query("biru");
        // arrPrintHijau($src_cbs);
        foreach ($src_cbs as $src_cb) {
            $cb_id = $src_cb->id;
            $cabangs[$cb_id] = $src_cb->nama;
            $condites["cabang_id"] = $cb_id;
            $this->db->where($condites);

            if ($dipakai == true) {
                $sumHarianCabang[$cb_id] = $rp->callSummaryHarian();
            } else {
                if ($get_date !== "") {
                    $this->db->where("date(dtime) >=", $get_date);
                }
                $this->db->order_by("dtime ASC");
                $sumHarianCabang_00 = $rp->callPenjulanPeriode("harian");
                foreach ($sumHarianCabang_00 as $xx => $item_cb) {
                    $arr_data = (array)$item_cb;
                    $arr_data_new["sum_debet"] = $item_cb->saldo_debet;
                    $arr_data_new["sum_kredit"] = $item_cb->saldo_kredit;
                    $arr_data_new["sum_hpp"] = 0;
                    $sumHarianCabang[$cb_id][] = (object)($arr_data + $arr_data_new);
                    // $sumHarianCabang[$cb_id][] =  $arr_data_new;
                }
            }

            // showLast_query("kuning");

        }
        // arrPrint($sumHarianCabang);

        // Tukar posisi urutan Cabang Surabaya (33) dan Cabang Gading Serpong (34)
        $ordered_cabangs = array();
        if (isset($cabangs[1])) {
            $ordered_cabangs[1] = $cabangs[1];
        }
        if (isset($cabangs[34])) {
            $ordered_cabangs[34] = $cabangs[34];
        }
        if (isset($cabangs[33])) {
            $ordered_cabangs[33] = $cabangs[33];
        }
        foreach ($cabangs as $k => $v) {
            if ($k != 1 && $k != 33 && $k != 34) {
                $ordered_cabangs[$k] = $v;
            }
        }
        $cabangs = $ordered_cabangs;

        // Gabungkan data berdasarkan tanggal unik (mencegah duplikasi tanggal pada holding/multi-cabang)
        $sumHarian_unique = array();
        foreach ($sumHarian as $item) {
            $tanggal = $item->thn . "-" . $item->bln . "-" . $item->tgl;
            if (!isset($sumHarian_unique[$tanggal])) {
                $sumHarian_unique[$tanggal] = array(
                    "thn" => $item->thn,
                    "bln" => $item->bln,
                    "tgl" => $item->tgl,
                    "sum_debet" => 0,
                    "sum_kredit" => 0,
                    "sum_hpp" => 0
                );
            }
            $sumHarian_unique[$tanggal]["sum_debet"] += $item->sum_debet;
            $sumHarian_unique[$tanggal]["sum_kredit"] += $item->sum_kredit;
            $sumHarian_unique[$tanggal]["sum_hpp"] += $item->sum_hpp;
        }

        $sumHarian = array();
        foreach ($sumHarian_unique as $k => $v) {
            $sumHarian[] = (object)array(
                "thn" => $v["thn"],
                "bln" => $v["bln"],
                "tgl" => $v["tgl"],
                "sum_debet" => $v["sum_debet"],
                "sum_kredit" => $v["sum_kredit"],
                "sum_hpp" => $v["sum_hpp"]
            );
        }

        if ($is_bulanan) {
            // Agregasi bulanan untuk sumHarian
            $sumHarian_aggregated = array();
            foreach ($sumHarian as $item) {
                $thn_bln = $item->thn . "-" . $item->bln;
                if (!isset($sumHarian_aggregated[$thn_bln])) {
                    $sumHarian_aggregated[$thn_bln] = array(
                        "thn" => $item->thn,
                        "bln" => $item->bln,
                        "sum_debet" => 0,
                        "sum_kredit" => 0,
                        "sum_hpp" => 0
                    );
                }
                $sumHarian_aggregated[$thn_bln]["sum_debet"] += $item->sum_debet;
                $sumHarian_aggregated[$thn_bln]["sum_kredit"] += $item->sum_kredit;
                $sumHarian_aggregated[$thn_bln]["sum_hpp"] += $item->sum_hpp;
            }
            $sumHarian = array();
            foreach ($sumHarian_aggregated as $k => $v) {
                $sumHarian[] = (object)array(
                    "thn" => $v["thn"],
                    "bln" => $v["bln"],
                    "tgl" => "01",
                    "sum_debet" => $v["sum_debet"],
                    "sum_kredit" => $v["sum_kredit"],
                    "sum_hpp" => $v["sum_hpp"]
                );
            }

            // Agregasi bulanan untuk sumHarianCabang
            $sumHarianCabang_final = array();
            foreach ($sumHarianCabang as $cb_id => $data_cb) {
                $cb_aggregated = array();
                foreach ($data_cb as $item) {
                    $thn_bln = $item->thn . "-" . $item->bln;
                    if (!isset($cb_aggregated[$thn_bln])) {
                        $cb_aggregated[$thn_bln] = array(
                            "thn" => $item->thn,
                            "bln" => $item->bln,
                            "sum_debet" => 0,
                            "sum_kredit" => 0,
                            "sum_hpp" => 0
                        );
                    }
                    $cb_aggregated[$thn_bln]["sum_debet"] += $item->sum_debet;
                    $cb_aggregated[$thn_bln]["sum_kredit"] += $item->sum_kredit;
                    $cb_aggregated[$thn_bln]["sum_hpp"] += $item->sum_hpp;
                }
                foreach ($cb_aggregated as $k => $v) {
                    $sumHarianCabang_final[$cb_id][] = (object)array(
                        "thn" => $v["thn"],
                        "bln" => $v["bln"],
                        "tgl" => "01",
                        "sum_debet" => $v["sum_debet"],
                        "sum_kredit" => $v["sum_kredit"],
                        "sum_hpp" => $v["sum_hpp"]
                    );
                }
            }
            $sumHarianCabang = $sumHarianCabang_final;
        }

        if (count($cabangs) > 1) {
            // foreach ($cabangs as $cabang) {

            foreach ($sumHarianCabang as $cab_id => $par_0) {

                // cekHitam("$cab_id " . count($par_0));
                // cekBiru(array_slice($par_0,0,1));
                foreach ($par_0 as $xx_0) {
                    // $jualan = "jual_$cab_id";
                    // $total_penjualan = penjualan_lokal + penjualan_pos + penjualan_export
                    // $biaya_operasional = biaya_marketing  + biaya_kirim
                    // $biaya_non_operasional = biaya_administrasi (gudang, cs)
                    // $total_biaya_lain = biaya_operasional + biaya_non_operasional
                    // $total_biaya = hpp + total_biaya_lain
                    // $penjualan_netto = (total_penjualan - total_retun) - total_biaya
                    //
                    // $hpp[] = $xx_0->sum_hpp / $dlm_juta;
                    // $rl[] = (($xx_0->sum_kredit - $xx_0->sum_debet) - $xx_0->sum_hpp) / $dlm_juta;
                    //
                    $tgl = $xx_0->tgl;
                    $bln = $xx_0->bln;
                    $thn = $xx_0->thn;
                    $tanggal = "$thn-$bln-$tgl";

                    $jualans[$cab_id][$tanggal] = ($xx_0->sum_kredit - $xx_0->sum_debet) / $dlm_juta;
                    // $anggal_f = formatTanggal($tanggal, "D, d M");
                    //
                    // $label[] = $anggal_f;
                }
            }
            // }
        }

        // cekBiru($jualans);
        // showLast_query("orange");
        // cekOrange(sizeof($sumHarian));
        // arrPrintPink($sumHarian);
        // arrPrintPink($xx);
        // arrPrintKuning($dd);
        $jual = array();
        $hpp = array();
        $rl = array();
        $tgl_key = array();
        $label = array();
        $bln_nama = "";

        if (count($sumHarian) > 0) {
            // 1. Buat daftar tanggal/bulan kontinu tanpa bolong
            $tgl_keys_kontinu = array();
            $labels_kontinu = array();

            if ($is_bulanan) {
                // Rentang bulanan kontinu
                $start_date = ($get_date !== "") ? date('Y-m-01', strtotime($get_date)) : date('Y-m-01', strtotime($sumHarian[0]->thn . "-" . $sumHarian[0]->bln . "-01"));
                $end_date = date('Y-m-01', strtotime(dtimeNow('Y-m-d')));

                $current_ts = strtotime($start_date);
                $end_ts = strtotime($end_date);
                while ($current_ts <= $end_ts) {
                    $tanggal = date('Y-m-01', $current_ts);
                    $tgl_keys_kontinu[] = $tanggal;
                    $labels_kontinu[] = formatTanggal($tanggal, "M Y");
                    $current_ts = strtotime("+1 month", $current_ts);
                }
            } else {
                // Rentang harian kontinu
                $start_ts = strtotime($get_date);
                $end_ts = strtotime(dtimeNow('Y-m-d'));
                for ($current_ts = $start_ts; $current_ts <= $end_ts; $current_ts += 86400) {
                    $tanggal = date('Y-m-d', $current_ts);
                    $tgl_keys_kontinu[] = $tanggal;
                    $labels_kontinu[] = formatTanggal($tanggal, "D, d M");
                }
            }

            // 2. Map data database ke array asosiatif berdasarkan tanggal/bulan
            $jual_map = array();
            $hpp_map = array();
            $rl_map = array();
            foreach ($sumHarian as $xx_0) {
                $tanggal = $xx_0->thn . "-" . $xx_0->bln . "-" . $xx_0->tgl;
                if ($is_bulanan) {
                    $tanggal = $xx_0->thn . "-" . $xx_0->bln . "-01";
                }
                $jual_map[$tanggal] = ($xx_0->sum_kredit - $xx_0->sum_debet) / $dlm_juta;
                $hpp_map[$tanggal] = $xx_0->sum_hpp / $dlm_juta;
                $rl_map[$tanggal] = (($xx_0->sum_kredit - $xx_0->sum_debet) - $xx_0->sum_hpp) / $dlm_juta;
            }

            // 3. Masukkan data ke array grafik secara kontinu (jika tidak ada data, isi dengan 0)
            foreach ($tgl_keys_kontinu as $idx => $tgl_c) {
                $jual[] = isset($jual_map[$tgl_c]) ? $jual_map[$tgl_c] : 0;
                $hpp[] = isset($hpp_map[$tgl_c]) ? $hpp_map[$tgl_c] : 0;
                $rl[] = isset($rl_map[$tgl_c]) ? $rl_map[$tgl_c] : 0;

                $tgl_key[] = $tgl_c;
                $label[] = $labels_kontinu[$idx];
            }
            $bln_nama = count($labels_kontinu) > 0 ? $labels_kontinu[count($labels_kontinu) - 1] : "";
        }

        /* ----------------------
         * jumlah nota
         * -----------------------------*/
        if ($dipakai == true) {
            $this->load->model("Mdls/MdlTransaksiConsolidasi");
            $rp = new MdlTransaksiConsolidasi();
            // $condites = array(
            //     "thn>=" => $get_thn,
            //     "bln>=" => "05",
            // );
            // $this->db->where($condites);

            //tes nambah cabang
            if (my_cabang_id() != -1) {
                $this->db->where("cabang_id", my_cabang_id());
            }
            $consoli_condites = array(
                "date(dtime)>=" => $get_date,
            );
            $this->db->where($consoli_condites);

            $sumHarian_0 = $dd = $rp->callSummaryPenjualanHarian();
        } else {
            $sumHarian_0 = array();
        }


        $jml_notas = array();
        $jml_nota_total = array();
        foreach ($sumHarian_0 as $consoli_items) {
            $year_month_day = $consoli_items->year_month_day;
            $cabang_id = $consoli_items->cabang_id;
            $jml_nota = $consoli_items->jml_nota;

            $key_tgl = $year_month_day;
            if ($is_bulanan) {
                $key_tgl = substr($year_month_day, 0, 7) . "-01";
            }

            if (!isset($jml_notas[$cabang_id][$key_tgl])) {
                $jml_notas[$cabang_id][$key_tgl] = 0;
            }
            $jml_notas[$cabang_id][$key_tgl] += $jml_nota;

            if (!isset($jml_nota_total[$key_tgl])) {
                $jml_nota_total[$key_tgl] = 0;
            }
            $jml_nota_total[$key_tgl] += $jml_nota;
        }
        // showLast_query("kuning");
        // cekHere(count($sumHarian));
        // arrPrint($sumHarian);
        // arrPrint($jml_notas);
        // arrPrint($jml_nota_total);

        /* -------------------------------------------------
         * variable untuk ke view
         * -------------------------------------------------*/
        $var = "";
        // arrPrintKuning($tgl_key);
        // arrPrintKuning(count($jualans));
        // arrPrintKuning(($jualans));

        $spek_notas = array();
        $branch_names = array();
        $branch_index = 0;
        foreach ($cabangs as $cba_id => $cba_nama) {
            $speks = array();
            $spek_notas = array();

            $jualan_tgls = isset($jualans[$cba_id]) ? $jualans[$cba_id] : array();

            foreach ($tgl_key as $increment_key => $tgl) {
                $jual_nilai = isset($jualan_tgls[$tgl]) ? $jualan_tgls[$tgl] : 0;
                $speks[$increment_key] = $jual_nilai;

                $jml_nota_cb = isset($jml_notas[$cba_id][$tgl]) ? $jml_notas[$cba_id][$tgl] : 0;
                $spek_notas[$increment_key] = $jml_nota_cb;

                $jml_nota_all[$increment_key] = isset($jml_nota_total[$tgl]) ? $jml_nota_total[$tgl] : 0;
            }
            $penjualan_cb[$cba_id] = $speks;

            $var["jml_nota_$cba_id"] = $spek_notas;
            $var["penjualan_$cba_id"] = $speks;

            // Simpan nama asli cabang
            $branch_names[$branch_index] = $cba_nama;

            // Petakan secara dinamis ke 100, 101, dan 102 agar terbaca oleh view
            $mapped_id = 100 + $branch_index;
            $var["jml_nota_$mapped_id"] = $spek_notas;
            $var["penjualan_$mapped_id"] = $speks;

            $branch_index++;
        }
        // arrPrintPink($penjualan_cb);
        // $tahun[] = "target";
        // // echo $var;
        // $var["tahun"] = $tahun;
        $var["penjualan"] = $jual;
        $var["jml_nota"] = $jml_nota_all;
        $var["hpp"] = $hpp;
        $var["labarugi"] = $rl;
        $var["label"] = $label;
        $var["penjualan_avg"] = ($jual) > 0 ? round((array_sum($jual) / count($jual)), 2) : 0;
        // $var["penjualan_past_label"] = $thn_lalu;
        // $var["penjualan_projek"] = $penjualan_projek / $dlm_milyar;
        // $var["penjualan_total"] = ($penjualan_projek + $penjualan_netto) / $dlm_milyar;
        // arrPrintKuning($var);

        $mode_view = "viewPenjualanHarian";
        if (my_cabang_id() == CB_ID_PUSAT) {
            $mode_view = "viewPenjualanHarianHolding";
        }

        $data = array(
            "mode" => $mode_view,
            // "title"                      => "Graph Penjualan Harian $bln_nama",
            "title" => $judul,
            "v_param" => $v_param, // State aktif tombol navigasi
            "subTitle" => "Angka Dalam Juta ",
            "content" => $var,
            "branch_names" => $branch_names,
            "penjualan_now_label" => dtimeNow('Y'),
            // "penjualan_past_label"       => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
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
            // "fillBtnChart"                   => $fillBtnChart,
        );
        $this->load->view("graph", $data);
    }

    /* -------------------------------------------------------------------------
     * mengambilin nilai dari beberapa model data (live)
     * -------------------------------------------------------------------------*/
    private function callSummary($cabang_id, $last_tr_id)
    {
        /*ngambil data neraca lajur pusat*/
        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();

        $r->addFilter("cabang_id='" . $cabang_id . "'");

        $tmp = $r->fetchAllBalances();
        //        showLast_query("merah");
        // arrPrint($tmp);
        //         matiHere(__LINE__);

        /*KAS*/
        $kas = array();
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "1010010010",
            "periode" => "forever"
        );
        $this->db->where($condites);
        $tableName = "_rek_master_cache";
        $src_pls = $this->db->get($tableName)->result();
        foreach ($src_pls as $items) {
            $rek_id = $items->rek_id;
            $rekening = $items->rekening;
            $debet = $items->debet;
            $kredit = $items->kredit;

            $kas[$rekening]["debet"] = $debet;
            $kas[$rekening]["kredit"] = $kredit;
            $kas[$rekening]["rek_id"] = $rek_id;
        }

        /*PERSEDIAAN PRODUK*/
        $persediaan_produk = array();
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "1010030030",
            "periode" => "forever"
        );
        $this->db->where($condites);
        $tableName = "_rek_master_cache";
        $src_pls = $this->db->get($tableName)->result();
        foreach ($src_pls as $items) {
            $rek_id = $items->rek_id;
            $rekening = $items->rekening;
            $debet = $items->debet;
            $kredit = $items->kredit;

            $persediaan_produk[$rekening]["debet"] = $debet;
            $persediaan_produk[$rekening]["kredit"] = $kredit;
            $persediaan_produk[$rekening]["rek_id"] = $rek_id;
        }


        /*PENJUALAN - data == yg dipakai pada graph comparasi penjualan*/
        //<editor-fold desc="hanya mengambil total penjualan dari seluruh cabang">
        $this->load->model("Coms/ComRekening");
        $cr = new ComRekening();
        $penjualan_netto_kredit = $cr->callPenjualanNettoNow();
        // showLast_query("here");
        $penjualan = array();
        $penjualan["penjualan"]["kredit"] = $penjualan_netto_kredit;
        $penjualan["penjualan"]["debet"] = 0;
        $penjualan["penjualan"]["rek_id"] = 0;

        //</editor-fold>

        /*PIUTANG DAGANG*/
        $piutang_dagang = array();
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "rekening" => "1010020010",
            "periode" => "forever"
        );
        $this->db->where($condites);
        $tableName = "_rek_master_cache";
        $src_pls = $this->db->get($tableName)->result();
        foreach ($src_pls as $items) {
            $rek_id = $items->rek_id;
            $rekening = $items->rekening;
            $debet = $items->debet;
            $kredit = $items->kredit;

            $penjualan[$rekening]["debet"] = $debet;
            $penjualan[$rekening]["kredit"] = $kredit;
            $penjualan[$rekening]["rek_id"] = $rek_id;
        }


        /*MTD sales*/
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "thn" => dtimeNow('Y'),
            "bln" => dtimeNow('m'),
            "periode" => "bulanan",
            "rekening" => "4010"
        );
        $this->db->where($condites);
        // $conditeins = array(
        //     "penjualan",
        //     "return penjualan",
        //     "penjualan projek",
        // );
        // $this->db->where_in("rekening", $conditeins);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->row();
        // showLast_query("kuning");
        // arrPrintHijau($src_datas);
        $penjualan['penjualan_mtd']["debet"] = $src_datas->debet * 1;
        $penjualan['penjualan_mtd']["kredit"] = ($src_datas->kredit - $src_datas->debet);
        $penjualan['penjualan_mtd']["rek_id"] = $src_datas->rek_id;

        /*MTD order neto local-export-project*/
        $src_datas = $this->get_z_sales_cache();
        // showLast_query("kuning");
        //  arrPrintHijau($src_datas);
        $penjualan['order_mtd']["debet"] = $src_datas->netto * 1;
        $penjualan['order_mtd']["kredit"] = 0 * 1;
        $penjualan['order_mtd']["rek_id"] = "";

        /*outstanding*/
        $penjualan['outstanding']["debet"] = 0 * 1;
        $penjualan['outstanding']["kredit"] = $src_datas->outstanding;
        $penjualan['outstanding']["rek_id"] = 0;

        /*sales_ytd*/
        $penjualan['sales_ytd']["debet"] = 0 * 1;
        $penjualan['sales_ytd']["kredit"] = $src_datas->kirim;
        $penjualan['sales_ytd']["rek_id"] = 0;

        /*outstanding AR = piutang dagang*/
        /*stok available = persediaan*/

        /*best of sales*/
        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        $strBln = formatTanggal(previousMonth(), "F");
        $date_1 = formatTanggal(previousMonth(), "Y-m-01");
        $date_2 = formatTanggal(previousMonth(), "Y-m-t");
        $src = $dt->callPerSeller(previousMonth(), $date_2);
        // return (object)$xx;
        //        cekHere("$date_1 -- $date_2");
//        arrPrint($src);
        $datas = array();
        foreach ($src as $item) {
            //            arrPrint($item);
            $seller_id = $item["seller_id"];
            $order = $item["now_saldo_order_all"];
            $reject = $item["now_saldo_reject_all"];
            $closed = $item["now_saldo_closed_all"];
            $kirim = $item["now_saldo_kirim_all"];
            $batal = $item["now_saldo_batal_kirim_all"];
            $return = $item["now_saldo_return_kirim_all"];

            $datas["order"][$seller_id] = $order - $reject - $closed - $batal - $return;
            $datas["kirim"][$seller_id] = $kirim - $batal - $return;
            $datas["seller"][$seller_id] = $item["seller_nama"];
            $datas["date"] = previousMonth();
        }

//         array_multisort($datas);
//                 arrPrint($datas);
        $sellers = $datas["seller"];
        $orders = $datas["order"];
        $kirims = $datas["kirim"];
        asort($kirims);
        asort($orders);
        // arrPrintHijau($kirims);
        // arrPrintHijau($orders);
        // cekHijau(end($kirims));
        // arrPrintKuning(key($kirims));

        $nilai_kirim = end($kirims);
        $seller_kirim = $sellers[key($kirims)];
        $nilai_order = end($orders);
        $seller_order = $sellers[key($orders)];

        $best_of_salesman["order"]["kredit"] = $nilai_order;
        $best_of_salesman["order"]["oleh_nama"] = $seller_order;
        $best_of_salesman["sales"]["kredit"] = $nilai_kirim;
        $best_of_salesman["sales"]["oleh_nama"] = $seller_kirim;

        $data_umum["dtime"] = dtimeNow();
        $data_umum["dtime_stamp"] = strtotime(dtimeNow());
        $data_umum["last_trid"] = $last_tr_id;
        // -----------------------------------------------------
        $data_umum["sub_param"]["date_1"] = $date_1;
        $data_umum["sub_param"]["date_2"] = $date_2;
        $data_umum["sub_param"]["str_bln"] = $strBln;
        $data_umum["sub_param"]["previous_month"] = previousMonth();

        //        $tmps = $tmp + $penjualan + $best_of_salesman;
        $tmps = $penjualan + $best_of_salesman + $kas + $persediaan_produk + $data_umum;

        return $tmps;
    }

    public function doSaveTarget()
    {
        arrPrint($_GET);
        // cekHere(realpath() . dirname('f') . __DIR__);
        $nilai = $_GET['nilai'];
        $dir = getcwd() . "/application/modules/dashboard/logs";

        // $myfile = fopen($dir."/testfile.txt", "w");
        $str_log = dtimeNow() . " $nilai dimainkan by " . my_name() . "/" . my_id();
        if (isset($_GET['save']) && $_GET['save'] == 1) {
            $str_log = dtimeNow() . " $nilai saved by " . my_name() . "/" . my_id();
            $myfile = file_put_contents($dir . '/target_pertumbuhan.txt', $nilai . PHP_EOL);
            cekHitam("nulis target $nilai");
        }
        $myfile = file_put_contents($dir . '/log_target_pertumbuhan.txt', $str_log . PHP_EOL, FILE_APPEND | LOCK_EX);
        // mati_disini(__LINE__);
        // die();
        $link_graph = base_url() . "dashboard/Graph/viewCompareSales?target_new=$nilai";
        echo "<script>
// alert('cek');
                $('#graph_penjualan').load('$link_graph');
            </script>";

    }

    public function viewComparePerSales()
    {

        //  __rek_pembantu_penjualan_seller_cache
        $dlm_milyar = 1000000;
        $bln_ini = dtimeNow('m');
        // $bln_ini = '07';
        $thn_ini = dtimeNow('Y');
        $koloms = array(
            "rekening",
            "rek_id",
            "extern2_id",
            "extern2_nama",
            "debet"
        , "kredit"
        , "bln"
        , "thn"
        , "periode"
        );
        $this->db->select($koloms);
        $condites = array(
            "bln" => $bln_ini,
            "thn" => $thn_ini,
            "periode" => "bulanan"
        );
        $this->db->where($condites);
        $tableName = "_rek_pembantu_penjualan_seller_cache";
        $src_pls = $this->db->get($tableName)->result();
        // arrPrint($src_pls);
        foreach ($src_pls as $src_pl) {
            $seller_id = $src_pl->extern2_id;
            $seller_nama = $src_pl->extern2_nama;
            $kredit = $src_pl->kredit;
            $seller_ids[$seller_id] = $seller_nama;
            $seller_id_nows[$seller_id] = $seller_nama;

            $penjualan_now[$seller_id] = $kredit;
        }

        /*--------penjualan yg lalu-------------*/
        $this->db->select($koloms);
        $condites = array(
            "bln>" => "01",
            "bln<" => $bln_ini,
            "thn" => $thn_ini,
            "periode" => "bulanan"
        );
        $this->db->where($condites);
        $src_yglalu = $this->db->get($tableName)->result();
        // showLast_query("here");
        // arrPrint($src_yglalu);
        $nilai_total_penjualan_yglalu = 0;
        foreach ($src_yglalu as $item_yglalu) {
            $seller_id = $item_yglalu->extern2_id;
            $seller_nama = $item_yglalu->extern2_nama;
            $kredit = $item_yglalu->kredit;

            if (!isset($nilai_penjualan_yglalu[$seller_id])) {
                $nilai_penjualan_yglalu[$seller_id] = 0;
            }
            $nilai_penjualan_yglalu[$seller_id] += $kredit;

            $nilai_total_penjualan_yglalu += $kredit;
            $seller_ids[$seller_id] = $seller_nama;
        }
        // cekHere(sizeof($seller_ids));
        $jml_seller = sizeof($nilai_penjualan_yglalu);
        $avg_seller = $nilai_total_penjualan_yglalu / $jml_seller;
        $bulan_lalu = $bln_ini - 1;
        // cekHere("$nilai_total_penjualan_yglalu $jml_seller avg: $avg_seller || $bulan_lalu");

        arsort($nilai_penjualan_yglalu);
        // arrPrintHijau($seller_id_nows);
        // arrPrintKuning($nilai_penjualan_yglalu);
        $seller_id_dsc = $nilai_penjualan_yglalu + $seller_id_nows;
        // arrPrintPink($nilai_penjualan_yglalu + $seller_id_nows);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($seller_ids);

        /*-----------------penjualan now--------------*/

        // foreach ($seller_ids as $seller_id => $seller_nama) {
        foreach ($seller_id_dsc as $seller_id => $odgj) {
            $seller_nama = $seller_ids[$seller_id];
            $nilai_penjualan_now = isset($penjualan_now[$seller_id]) ? $penjualan_now[$seller_id] * 1 : 0;
            // cekHere($nilai_penjualan_now);
            $penjualan_sekarang[] = array($seller_nama, $nilai_penjualan_now);
            // $penjualan_now[][$seller_nama] = $kredit;

            /*persen penjualan now*/
            // $persen_penjualan_now = ($nilai_penjualan_now / $nilai_penjualan_past) * 100;
            // $persen_penjualan_now_f = number_format($persen_penjualan_now, 0);

            /*--prediksi target penjualan tahun ini--*/
            $hari_dlm_setahun = dtimeNow('t');
            $hari_ke = dtimeNow('d');
            $target_penjualan = ($hari_dlm_setahun / $hari_ke) * $nilai_penjualan_now;
            $target_penjualan_now[] = array($seller_nama, $target_penjualan);

            /*----------penjualan yg lalu ---------*/
            $nilai_yglalu = isset($nilai_penjualan_yglalu[$seller_id]) ? $nilai_penjualan_yglalu[$seller_id] / $bulan_lalu : 0;
            // $nilai_yglalu = 100000000;
            $penjualan_past[] = array($seller_nama, $nilai_yglalu);


            /*--target ---------------*/
            // arrPrint($_GET['target_new']);
            if (isset($_GET['target_new'])) {
                $persen_target = $_GET['target_new'];
            } else {
                $dir = getcwd() . "/application/modules/dashboard/logs";
                $myfiles = fopen($dir . "/target_pertumbuhan.txt", "r") or die("Unable to open file!");
                $persen_target = fread($myfiles, filesize($dir . "/target_pertumbuhan.txt"));
                fclose($myfiles);
            }
            // arrPrintHijau(json_decode($persen_target, true));
            $target_persen = $persen_target * 1;

            $adj_target = $nilai_yglalu * ($target_persen / 100);
            $target_tahun_now[] = array($seller_nama, ($nilai_yglalu + $adj_target));
        }

        $var = "";

        $tahun[] = "target";
        // echo $var;
        $var["tahun"] = $tahun;
        $var["penjualan_past"] = $penjualan_past;
        // $var["penjualan_past_label"] = $thn_lalu;
        $var["penjualan_now"] = $penjualan_sekarang;
        $var["target_penjualan_now"] = $target_penjualan_now;
        $var["target_tahun_now"] = $target_tahun_now;
        // $var["penjualan_now_label"] = dtimeNow('Y');;
        //
        // arrPrintHijau($var);

        $data = array(
            "mode" => "viewComparePerSales",
            "title" => "Graph Reports",
            "subTitle" => "",
            "content" => $var,
            "penjualan_now_label" => $bln_ini,
            "penjualan_past_label" => "'yg lalu'",
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            "target_persen" => $target_persen,
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
        $this->load->view("graph", $data);

    }

    /*------*/
    private function get_z_sales_cache()
    {
        // $tableName = "z_sales_batal_cache";
        // $tableName = "z_sales_return_cache";
        // $koloms = array(
        //     "rekening",
        //     "rek_id",
        //     "sum(kredit) as kredit",
        //     "sum(debet) as debet"
        // );
        // // $this->db->select($koloms);
        // $condites = array(
        //     "thn"     => dtimeNow('Y'),
        //     "bln"     => dtimeNow('m'),
        //     "periode" => "bulanan"
        // );
        // $this->db->where($condites);
        // $conditeins = array(
        //     "582spd",
        //     "7499",
        //     "382sspd",
        //     // "return penjualan",
        // );
        // $this->db->where_in("rekening", $conditeins);
        // $src_datas = "";
        // $src_datas = $this->db->get($tableName)->result();
        // showLast_query("biru");
        // arrPrint($src_datas);

        /*ORDER-------------------------------------------*/
        $tableName = "z_sales_cache";
        $koloms = array(
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet"
        );
        // $this->db->select($koloms);
        $condites = array(
            "thn" => dtimeNow('Y'),
            "bln" => dtimeNow('m'),
            "periode" => "bulanan"
        );
        $this->db->where($condites);
        $conditeins = array(
            "582so",
            "588so",
            "382so",
            // "return penjualan",
        );
        $this->db->where_in("rekening", $conditeins);
        $src_datas = "";
        $src_datas = $this->db->get($tableName)->result();
        $sum_saldo_order = 0;
        // arrPrintPink($src_datas);
        foreach ($src_datas as $src_data) {
            $rekening = $src_data->rekening;
            // $saldo_reject = $src_data->saldo_reject;
            // $saldo_closed = $src_data->saldo_closed;
            $saldo_order = $src_data->saldo_order;

            // $saldo_netto += ($saldo_order - $saldo_reject - $saldo_closed);
            // $reject = "319256690.0000000000";
            $sum_saldo_order += ($saldo_order);

        }
        // -----------------------

        $condites = array(
            "thn" => dtimeNow('Y'),
            "bln" => dtimeNow('m'),
            "periode" => "bulanan"
        );
        $this->db->where($condites);
        $conditeins = array(
            "582spd",
            "7499",
            "382spd",
            // "return penjualan",
        );
        $this->db->where_in("rekening", $conditeins);
        $src_datas = "";
        $src_datas = $this->db->get($tableName)->result();
        $sum_saldo_gagal = 0;
        $sum_saldo_kirim = 0;
        foreach ($src_datas as $src_data) {
            $rekening = $src_data->rekening;
            $saldo_reject = $src_data->saldo_reject;
            $saldo_closed = $src_data->saldo_closed;
            $saldo_kirim = $src_data->saldo_kirim;
            // $saldo_order = $src_data->saldo_order;

            $sum_saldo_gagal += ($saldo_reject + $saldo_closed);
            $sum_saldo_kirim += $saldo_kirim;

        }
        // --------------------------
        $condites = array(
            "thn" => dtimeNow('Y'),
            "bln<" => dtimeNow('m'),
            // "date(dtime)<"    => dtimeNow('Y-m-d'),
            "periode" => "bulanan"
        );
        $this->db->where($condites);
        $conditeins = array(
            "582spd",
            "7499",
            "382spd",
            // "return penjualan",
        );
        $this->db->where_in("rekening", $conditeins);
        $src_datas = "";
        $src_datas = $this->db->get($tableName)->result();
//         showLast_query("biru");
        // cekMerah(sizeof($src_datas));

        $kredits = array();
        foreach ($src_datas as $src_data) {
            $rekening = $src_data->rekening;
            //     $saldo_reject = $src_data->saldo_reject;
            $kredit = $src_data->kredit;
            // cekHere("$kredit");
            //     $saldo_closed = $src_data->saldo_closed;
            //     $saldo_kirim = $src_data->saldo_kirim;
            //     // $saldo_order = $src_data->saldo_order;
            //
            //     // $sum_saldo_gagal += ($saldo_reject + $saldo_closed);
            $kredits[$rekening][] = $kredit;
            //
        }
//         arrPrintPink($kredits);
        $kredits_2 = array();
        $sum_kredit = 0;
        foreach ($kredits as $rekening => $kredit) {
            $kredits_2[$rekening] = end($kredit);
            $sum_kredit += end($kredit);
        }
//         arrPrintHijau($kredits_2);
        // --------------------------
        // outstanding th sebelumnya--------------------// belum berhasil
        // $condites = array(
        //     "thn<"     => dtimeNow('Y'),
        //     // "bln<"    => dtimeNow('m'),
        //     // "date(dtime)<"    => dtimeNow('Y-m-d'),
        //     "periode" => "bulanan"
        // );
        // $this->db->where($condites);
        // $conditeins = array(
        //     "582spd",
        //     "7499",
        //     "382spd",
        //     // "return penjualan",
        // );
        // $this->db->where_in("rekening", $conditeins);
        // $src_datas = "";
        // $src_datas = $this->db->get($tableName)->result();
        // showLast_query("biru");
        // cekMerah(sizeof($src_datas));
        //
        // $kredits = array();
        // foreach ($src_datas as $src_data) {
        //     $rekening = $src_data->rekening;
        //     //     $saldo_reject = $src_data->saldo_reject;
        //     $kredit = $src_data->kredit;
        //     // cekHere("$kredit");
        //     //     $saldo_closed = $src_data->saldo_closed;
        //     //     $saldo_kirim = $src_data->saldo_kirim;
        //     //     // $saldo_order = $src_data->saldo_order;
        //     //
        //     //     // $sum_saldo_gagal += ($saldo_reject + $saldo_closed);
        //     $kredits[$rekening][] = $kredit;
        //     //
        // }
        // // arrPrint($kredits);
        // $kredits_2 = array();
        // $sum_kredit_yglalu = 0;
        // foreach ($kredits as $rekening => $kredit) {
        //     // $kredits_2[$rekening] = end($kredit);
        //     $sum_kredit_yglalu += end($kredit);
        // }
        // cekKuning("$sum_kredit_yglalu");
        // ---------------------------------------------

//         cekHijau("(($sum_kredit + $sum_saldo_order) - $sum_saldo_gagal - $sum_saldo_kirim)");

        $xx["prev"] = $sum_kredit;
        $xx["order"] = $sum_saldo_order;
        $xx["gagal"] = $sum_saldo_gagal;
        $xx["netto"] = $sum_saldo_order - $sum_saldo_gagal;
        $xx["kirim"] = $sum_saldo_kirim;
        $xx["outstanding"] = (($sum_kredit + $sum_saldo_order) - $sum_saldo_gagal - $sum_saldo_kirim);
        // showLast_query("merah");
        // arrPrintHijau((object)$xx);
        // arrPrintKuning("$sum_saldo_order - $sum_saldo_gagal");
        return (object)$xx;
    }

    public function call_top_sales_salesman()
    {
        $tableName = "z_sales_salesman_cache";
        $koloms = array(
            "seller_id",
            "seller_nama",
            // "sum(kredit) as kredit",
            // "sum(debet) as debet"
            // "sum(saldo_order) as 'sum_saldo_order'",
            "sum(saldo_kirim) as 'sum_saldo_kirim'"

        );
        $this->db->select($koloms);
        $condites = array(
            "thn" => dtimeNow('Y'),
            "bln" => dtimeNow('m'),
            "periode" => "bulanan"
        );
        $this->db->where($condites);
        $conditeins = array(
            "582spd",
            "7499",
            "382spd",
            // "return penjualan",
        );
        $this->db->where_in("rekening", $conditeins);
        $this->db->group_by("seller_id");
        $this->db->order_by("sum_saldo_kirim", "desc");
        $src_datas = "";
        $src_datas = $this->db->get($tableName)->result();
        $saldo_netto = 0;
        foreach ($src_datas as $src_data) {
            $rekening = $src_data->rekening;
            $saldo_reject = $src_data->saldo_reject;
            $saldo_closed = $src_data->saldo_closed;
            $saldo_order = $src_data->saldo_order;

            $saldo_netto += ($saldo_order - $saldo_reject - $saldo_closed);

        }
        $xx["netto"] = $saldo_netto;
        showLast_query("kuning");
        arrPrintHijau($src_datas);

        // return (object)$xx;
    }

    public function call_top_salesman()
    {
        // $tableName = "z_sales_salesman_cache";
        // $koloms = array(
        //     "seller_id",
        //     "seller_nama",
        //     "rekening",
        //     // "sum(kredit) as kredit",
        //     // "sum(debet) as debet"
        //     "sum(saldo_order) as 'sum_saldo_order'",
        //     "sum(saldo_kirim) as 'sum_saldo_kirim'"
        //
        // );
        // // $this->db->select($koloms);
        // $condites = array(
        //     "thn"     => dtimeNow('Y'),
        //     "bln"     => dtimeNow('m'),
        //     "periode" => "bulanan"
        // );
        // $this->db->where($condites);
        // $conditeins = array(
        //     "582so",
        //     "588so",
        //     "382s0",
        //     "582spd",
        //     "7499",
        //     "382spd",
        //     // "return penjualan",
        // );
        // $this->db->where_in("rekening", $conditeins);
        // // $this->db->group_by("seller_id,rekening");
        // // $this->db->order_by("sum_saldo_kirim", "desc");
        // $src_datas = "";
        // $src_datas = $this->db->get($tableName)->result();
        // $saldo_netto = array();
        // foreach ($src_datas as $src_data) {
        //     $rekening = $src_data->rekening;
        //     $seller_id = $src_data->seller_id;
        //     $saldo_reject = $src_data->saldo_reject;
        //     $saldo_closed = $src_data->saldo_closed;
        //     $saldo_order = $src_data->saldo_order;
        //
        //     $saldo_netto[$seller_id][$rekening] = $src_data;
        //     // $saldo_netto[$seller_id] = ($saldo_order - $saldo_reject - $saldo_closed);
        //
        // }
        // // $xx["netto"] = $saldo_netto;
        // showLast_query("kuning");
        // arrPrintHijau($saldo_netto);

        $this->load->library("laporan/DataOutstanding");
        $dt = new DataOutstanding();

        $strBln = formatTanggal(previousMonth(), "F");
        $date_1 = formatTanggal(previousMonth(), "Y-m-01");
        $date_2 = formatTanggal(previousMonth(), "Y-m-t");
        $src = $dt->callPerSeller(previousMonth(), $date_2);
        // return (object)$xx;
        // arrPrint($src);
        $datas = array();
        foreach ($src as $item) {
            $seller_id = $item["seller_id"];
            $order = $item["now_saldo_order_all"];
            $reject = $item["now_saldo_reject_all"];
            $closed = $item["now_saldo_closed_all"];
            $kirim = $item["now_saldo_kirim_all"];

            $datas["order"][$seller_id] = $order - $reject - $closed;
            $datas["kirim"][$seller_id] = $kirim;
            $datas["seller"][$seller_id] = $item["seller_nama"];
            $datas["date"] = previousMonth();
        }

        // array_multisort($datas);
        // arrPrint($datas);
        $sellers = $datas["seller"];
        $orders = $datas["order"];
        $kirims = $datas["kirim"];
        asort($kirims);
        asort($orders);
        // arrPrintHijau($kirims);
        // cekHijau(end($kirims));
        // arrPrintKuning(key($kirims));

        $nilai_kirim = end($kirims);
        $seller_kirim = $sellers[key($kirims)];
        $nilai_order = end($orders);
        $seller_order = $sellers[key($orders)];
        $tmps = array();
        $tmps["order"] = $nilai_order;
        $tmps["sales"] = $nilai_kirim;
        $boards = array(
            "1" => array(
                "rekening" => "order",
                // "rekening_posisi" => "debet",
                // "nilai"      => $kas,
                "label" => $seller_order,
                "satuan" => $strBln,
                "icon" => " fa-thumbs-o-up",
                "bg" => "bg-primary",
                // https://san.mayagrahakencana.com/laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==
                "link" => "laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==&date1=$date_1&date2=$date_2",
                "link_label" => "Best Order of The Month",
            ),
            "2" => array(
                "rekening" => "sales",
                // "rekening_posisi" => "kredit",
                // "nilai"      => $hutangKepusat,
                "label" => $seller_kirim,
                "satuan" => $strBln,
                "icon" => " fa-thumbs-o-up",
                // "bg"              => "bg-teal",
                "bg" => "bg-fuchsia",
                "link" => "laporan/outstanding/vieweoutstanding?gr=czoyOToicmVwb3J0aW5nLWNybV9vdXRzdGFuZGluZ19ibG4iOw==&date1=$date_1&date2=$date_2",
                "link_label" => "Best Sales of The Month",
            ),
        );

        $jml = sizeof($boards);
        $var = "";
        $var .= "<style type='text/css'>
                    .small-box .icon {
                        top: -25px;
                    }
                    small {
                    color: #f0f0c0 !important;
                    }
                    
                </style>";
        foreach ($boards as $board) {
            //arrPrintHijau($board["rekening"]);
            $nilai = $tmps[$board["rekening"]];
            // $nilai = $board["nilai"];
            $nilai_f = isset($board["rekening"]) ? formatField2("debet", $nilai) : 0;
            $label = $board["label"];
            $box_warna = $board["bg"];
            $link_lager = isset($board["link"]) ? $board["link"] : "";
            $link_label = isset($board["link_label"]) ? $board["link_label"] : "";
            $fa_icon = $board["icon"];
            $fa_icon_link = isset($board["link_label"]) ? "fa-hand-o-right" : "";
            $satuan = isset($board["satuan"]) ? "(" . $board["satuan"] . ")" : "";
            $lebar_col = 12 / $jml;
            // $lebar = $lebar_col > 4 ? "4" : $lebar_col;
            $lebar = $lebar_col;
            $var .= "<div class='col-lg-$lebar col-xs-6'>
                            <div class='small-box $box_warna'>
                                <div class='inner'>
                                    <h3 style='font-size: 2.5em;'>$nilai_f <small> $satuan</small></h3>     
                                    <p class='text-uppercase' style='font-size: 1.5em;'>$label</p>
                                </div>
                                <div class='icon'>
                                    <i class='fa $fa_icon ' style='font-size: .5em;' aria-hidden='true'></i>
                                </div>
                                <a href='$link_lager' class='small-box-footer text-capitalize' target='_blank'>$link_label <i class='fa $fa_icon_link'></i></a>
                            </div>
                        </div>";
        }


        echo $var;
    }

    /*--EFISIENSI BOM---*/
    public function call_efisiensi_bom_bulanan($tahun = "", $bulan = "")
    {
        $tahun = $tahun == "" ? dtimeNow('Y') : $tahun;
        $bulan = $bulan == "" ? dtimeNow('m') : $bulan;
        $dlm_milyar = 1000000;
        $koloms = array(
            "thn",
            "bln",
            "rekening",
            "rek_id",
            "saldo_kredit",
            "saldo_debet",
            "saldo_kredit_periode",
            "saldo_debet_periode"
        );
        $this->db->select($koloms);
        $condites = array(
            "thn" => $tahun,
            "bln>=" => "01",
            "bln<=" => $bulan,
            "periode" => "bulanan"
        );
        $this->db->where($condites);
        $conditeins = array(
            "3020010",
            // "return penjualan",
            // "penjualan projek",
        );
        $this->db->where_in("rekening", $conditeins);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->result();
        // showLast_query("biru");
        // arrPrint($src_datas);
        // $kredit = $src_datas->kredit * 1;
        // $debet = $src_datas->debet * 1;
        // $efisiensi = $kredit - $debet;
        //
        // $vars = array();
        // $vars["bom"] = $kredit / $dlm_milyar;
        // $vars["realisasi"] = $debet / $dlm_milyar;
        // $vars["efisiensi"] = $efisiensi / $dlm_milyar;
        // $vars["efisiensi_persen"] = $efisiensi / $kredit;
        // $vars["prediksi"] = 10;

        return $src_datas;
    }

    public function call_efisiensi_bom_pembantu_bulanan($tahun = "", $bulan = "")
    {
        $tahun = $tahun == "" ? dtimeNow('Y') : $tahun;
        $bulan = $bulan == "" ? dtimeNow('m') : $bulan;
        $dlm_milyar = 1000000;
        $koloms = array(
            "thn",
            "bln",
            "rekening",
            "extern_id",
            "extern_nama",
            "rek_id",
            "saldo_kredit",
            "saldo_debet",
            "saldo_kredit_periode",
            "saldo_debet_periode"
        );
        $this->db->select($koloms);
        $condites = array(
            "thn" => $tahun,
            "bln>=" => "01",
            "bln<=" => $bulan,
            "periode" => "bulanan"
        );
        $this->db->where($condites);
        $conditeins = array(
            "3020010",
            // "return penjualan",
            // "penjualan projek",
        );
        $this->db->where_in("rekening", $conditeins);
        $tableName = "_rek_pembantu_efisiensi_cache";
        $src_datas = $this->db->get($tableName)->result();
        // showLast_query("merah");
        // arrPrint($src_datas);
        // $kredit = $src_datas->kredit * 1;
        // $debet = $src_datas->debet * 1;
        // $efisiensi = $kredit - $debet;
        //
        // $vars = array();
        // $vars["bom"] = $kredit / $dlm_milyar;
        // $vars["realisasi"] = $debet / $dlm_milyar;
        // $vars["efisiensi"] = $efisiensi / $dlm_milyar;
        // $vars["efisiensi_persen"] = $efisiensi / $kredit;
        // $vars["prediksi"] = 10;

        return $src_datas;
    }

    public function viewEfisiensiBomThn()
    {
        // $dlm_milyar = 1000000000;
        /*PENJUALAN*/

        //----------------
        $thn_lalu = previousYear();
        $tahun[] = $thn_lalu;

        // $this->load->model("Coms/ComRekening");
        // $cr = new ComRekening();
        $tahun = dtimeNow('Y');
        // $bulan = dtimeNow('m') - 3;
        // $date_req = "$tahun-$bulan";

        $var = $this->call_efisiensi_bom_tahun($tahun);
        // $var["prediksi"] = 9999;
        // arrPrint($var);
        // cekHere($akredit);

        // $tahun[] = dtimeNow('Y');

        /*persen penjualan now*/
        // $persen_penjualan_now = ($nilai_penjualan_now / $nilai_penjualan_past) * 100;
        // $persen_penjualan_now_f = number_format($persen_penjualan_now, 0);
        //
        // /*--prediksi target penjualan tahun ini--*/
        // $hari_dlm_setahun = 365;
        // $hari_ke = dtimeNow('z') + 1;
        // $target_penjualan = ($hari_dlm_setahun / $hari_ke) * $nilai_penjualan_now;
        // $target_penjualan_now[] = $target_penjualan;
        // // cekHere($hari_ke);
        // // cekHere(date('z',dtimeToSecond('2022-12-31')) + 1);
        //
        // /*--target ---------------*/
        // // arrPrint($_GET['target_new']);
        // if (isset($_GET['target_new'])) {
        //     $persen_target = $_GET['target_new'];
        // }
        // else {
        //     $dir = getcwd() . "/application/modules/dashboard/logs";
        //     $myfiles = fopen($dir . "/target_pertumbuhan.txt", "r") or die("Unable to open file!");
        //     $persen_target = fread($myfiles, filesize($dir . "/target_pertumbuhan.txt"));
        //     fclose($myfiles);
        // }
        // // arrPrintHijau(json_decode($persen_target, true));
        // $target_persen = $persen_target * 1;
        //
        // $adj_target = $nilai_penjualan_past * ($target_persen / 100);
        // $target_tahun_now[] = $nilai_penjualan_past + $adj_target;

        // $var = "";
        //
        // $tahun[] = "target";
        // // echo $var;
        // $var["tahun"] = $tahun;
        // $var["penjualan_past"] = $penjualan_past;
        // // $var["penjualan_past_label"] = $thn_lalu;
        // $var["penjualan_now"] = $penjualan_now;
        // $var["target_penjualan_now"] = $target_penjualan_now;
        // $var["target_tahun_now"] = $target_tahun_now;
        // // $var["penjualan_now_label"] = dtimeNow('Y');;
        //
        // arrPrintHijau($var);
        $tipe = "TH. $tahun";
        $stamp = "";
        if ($tahun == dtimeNow('Y')) {
            $tipe = "YTD $tahun";
            // $stamp = formatTanggal($date_req, 'Y M');
            $stamp = dtimeNow('Y-M-d');
        }
        $data = array(
            "mode" => "viewEfisiensiBomThn",
            "title" => "Produksi $tipe",
            "subTitle" => "Efisiensi BOM $stamp",
            "keyBiaya" => "",
            "content" => $var,
            "penjualan_now_label" => dtimeNow('Y'),
            "penjualan_past_label" => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
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
        $this->load->view("graph", $data);
    }

    public function call_efisiensi_bom_tahun($tahun)
    {
        $dlm_milyar = 1000000;
        $koloms = array(
            "rekening",
            "rek_id",
            //            "sum(saldo_kredit) as kredit",
            //            "sum(saldo_debet) as debet"
            "sum(saldo_kredit_periode) as kredit",
            "sum(saldo_debet_periode) as debet"
        );
        $this->db->select($koloms);
        $condites = array(
            "thn" => $tahun,
            // "bln"     => $bulan,
            // "bln"     => 10,
            "periode" => "tahunan"
        );
        $this->db->where($condites);
        $conditeins = array(
            "3020010",
            // "efisiensi biaya",
            // "return penjualan",
            // "penjualan projek",
        );
        $this->db->where_in("rekening", $conditeins);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->row();
        //        showLast_query("merah");
        // arrPrint($src_datas);
        $kredit = $src_datas->kredit * 1;
        $debet = $src_datas->debet * 1;
        $efisiensi = $kredit - $debet;

        $hari_dlm_setahun = 365;
        $hari_ke = dtimeNow('z') + 1;
        $prediksi = ($hari_dlm_setahun / $hari_ke) * $efisiensi;

        $vars = array();
        $vars["bom"] = $kredit / $dlm_milyar;
        $vars["realisasi"] = $debet / $dlm_milyar;
        $vars["efisiensi"] = $efisiensi / $dlm_milyar;
        $vars["efisiensi_persen"] = $efisiensi / $kredit;
        $vars["prediksi"] = $prediksi / $dlm_milyar;

        return $vars;
    }

    public function call_efisiensi_bom_pembantu_tahun($tahun)
    {
        $dlm_milyar = 1000000;
        $koloms = array(
            "rekening",
            "rek_id",
            "extern_id",
            "extern_nama",
            "rek_id",
            "saldo_kredit",
            "saldo_debet",
            "saldo_kredit_periode",
            "saldo_debet_periode"
        );
        $this->db->select($koloms);
        $condites = array(
            "thn" => $tahun,
            // "bln"     => $bulan,
            // "bln"     => 10,
            "periode" => "tahunan"
        );
        $this->db->where($condites);
        $conditeins = array(
            "3020010",
            // "efisiensi biaya",
            // "return penjualan",
            // "penjualan projek",
        );
        $this->db->where_in("rekening", $conditeins);
        $tableName = "_rek_pembantu_efisiensi_cache";
        $src_datas = $this->db->get($tableName)->result();
        // showLast_query("merah");
        // arrPrint($src_datas);
        $vars = array();
        foreach ($src_datas as $src_data) {

            $extern_nama = $src_data->extern_nama;
            $extern_id = $src_data->extern_id;
            //            $kredit = $src_data->saldo_kredit * 1;
            //            $debet = $src_data->saldo_debet * 1;
            $kredit = $src_data->saldo_kredit_periode * 1;
            $debet = $src_data->saldo_debet_periode * 1;
            $efisiensi = $kredit - $debet;

            $hari_dlm_setahun = 365;
            $hari_ke = dtimeNow('z') + 1;
            $prediksi = ($hari_dlm_setahun / $hari_ke) * $efisiensi;

            $subRekening[$extern_id] = $extern_nama;

            $vars[$extern_id]["bom"] = $kredit / $dlm_milyar;
            $vars[$extern_id]["realisasi"] = $debet / $dlm_milyar;
            $vars[$extern_id]["efisiensi"] = $efisiensi / $dlm_milyar;
            $vars[$extern_id]["efisiensi_persen"] = $kredit != 0 ? $efisiensi / $kredit : 0;
            $vars[$extern_id]["prediksi"] = $prediksi / $dlm_milyar;
            $vars[$extern_id]["nama"] = $extern_nama;
        }

        return $vars;
    }

    public function viewEfisiensiBomBlnan()
    {
        // $this->load->model("Mdls/MdlRugilaba");
        // $d = new MdlRugilaba();
        // $thn_now = dtimeNow('Y');
        // $thn_batas = $thn_now - 3;
        // //============= B I A Y A =============
        // $d->addFilter("thn>'$thn_batas'");
        // $d->addFilter("kategori='biaya'");
        // $d->addFilter("periode='bulanan'");
        // //        $d->addFilter("rekening='penjualan'");
        // $tmp1 = $d->lookupAll()->result();
        // // cekMerah($this->db->last_query());
        //
        // $rTemp1 = array();
        // if (sizeof($tmp1) > 0) {
        //     foreach ($tmp1 as $row) {
        //         $bl = $row->bln;
        //         $yr = $row->thn;
        //         $rTemp1["$yr-$bl"][] = $row;
        //     }
        // }
        // $arrBiaya = array();
        // if (sizeof($rTemp1) > 0) {
        //     $tmpResult = array();
        //     foreach ($rTemp1 as $title => $row2) {
        //         $kredit = 0;
        //         $debet = 0;
        //         $totalKredit = array();
        //         $totalDebet = array();
        //         $tmps = array();
        //         $total = 0;
        //         foreach ($row2 as $row3) {
        //             if (!isset($totalKredit[$row3->rekening])) {
        //                 $totalKredit[$row3->rekening] = 0;
        //             }
        //             if (!isset($totalDebet[$row3->rekening])) {
        //                 $totalDebet[$row3->rekening] = 0;
        //             }
        //             $totalKredit[$row3->rekening] += $row3->kredit;
        //             $totalDebet[$row3->rekening] += $row3->debet;
        //             $tmps[$row3->rekening] = array(
        //                 "total_kredit" => $totalKredit[$row3->rekening],
        //                 "total_debet"  => $totalDebet[$row3->rekening],
        //             );
        //             $tmpResult[$title] = $tmps;
        //         }
        //     }
        //     $arrBiaya = $tmpResult;
        // }

        //=============PENJUALAN=============
        /*
         * saldo_kredit : anggaran
         * saldo_debet : realisasi
         * efisiensi = $saldo_kredit - saldo_debet
         *
         * */
        $keyBiaya = isset($_GET['kb']) ? $_GET['kb'] : 1;
        $tahun = dtimeNow('Y');
        $bulan = dtimeNow('m');
        $tmp1 = $this->call_efisiensi_bom_pembantu_bulanan($tahun, $bulan);
        // arrPrint($tmp1);
        $rTemp1 = array();
        if (sizeof($tmp1) > 0) {
            foreach ($tmp1 as $row) {
                $bl = $row->bln;
                $yr = $row->thn;
                $ext_id = $row->extern_id;
                $ext_nama = $row->extern_nama;
                $saldo_kredit = $row->saldo_kredit;
                $saldo_debet = $row->saldo_debet;
                $saldo_netto = $saldo_kredit - $saldo_debet;


                // $rTemp1["$yr-$bl"][] = $row;
                $rTemp1["$yr-$bl"][$ext_id]["saldo_kredit"] = $saldo_kredit;
                $rTemp1["$yr-$bl"][$ext_id]["saldo_debet"] = $saldo_debet;
                $rTemp1["$yr-$bl"][$ext_id]["saldo_netto"] = $saldo_netto;

                $subRekening[$ext_id] = $ext_nama;
            }
        }
        // arrPrintHijau($subRekening);
        // arrPrintKuning($rTemp1);
        // foreach ($rTemp1 as $ky => $item) {
        //     arrPrintHijau($item);
        //
        // }
        $tmp2 = $this->call_efisiensi_bom_bulanan($tahun, $bulan);
        // cekMerah($this->db->last_query());
        $rTemp2 = array();
        if (sizeof($tmp2) > 0) {
            foreach ($tmp2 as $row) {
                $bl = $row->bln;
                $yr = $row->thn;
                $rTemp2["$yr-$bl"][] = $row;
            }
        }
        // arrPrintHijau($rTemp2);
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
                    $totalKredit[$row3->rekening] += $row3->saldo_kredit;
                    $totalDebet[$row3->rekening] += $row3->saldo_debet;
                    $tmps = array(
                        "bom" => $totalKredit[$row3->rekening],
                        "realisasi" => $totalDebet[$row3->rekening],
                        "efisiensi" => $totalKredit[$row3->rekening] - $totalDebet[$row3->rekening],
                    );
                    $tmpResult[$title] = $tmps;
                }
            }
            $arrPenjualan = $tmpResult;
        }
        // arrPrintPink($arrPenjualan);
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

            $this->load->config("heAccounting");
            $categoriRl = $this->config->item("categoryRL");
            // arrPrint($categoriRl);
            $arrKeyBiaya = $categoriRl[2];
            $arrKeyRll = $categoriRl[3];
            // arrPrintPink($arrPenjualan);

            foreach ($arrPenjualan as $periode => $data) {

                $penjualan = isset($data['penjualan']['total_kredit']) ? $data['penjualan']['total_kredit'] - $data['penjualan']['total_debet'] : 0;
                $penjualan_projek = isset($data['penjualan projek']['total_kredit']) ? $data['penjualan projek']['total_kredit'] - $data['penjualan projek']['total_debet'] : 0;
                $hpp_projek = isset($data['hpp projek']['total_debet']) ? $data['hpp projek']['total_debet'] : 0;
                $return_penjualan = isset($data['return penjualan']['total_debet']) ? $data['return penjualan']['total_debet'] : 0;
                $hpp = isset($data['hpp']['total_debet']) ? $data['hpp']['total_debet'] : 0;
                $efisiensi_biaya = isset($data['efisiensi biaya']['total_debet']) ? $data['efisiensi biaya']['total_debet'] - $data['efisiensi biaya']['total_kredit'] : 0;
                //-----
                $subDatas = $rTemp1[$periode];
                // arrPrintHijau($subDatas);
                foreach ($subRekening as $subRekening_id => $subRekening_nama) {
                    $val_sub_rekening = isset($subDatas[$subRekening_id]['saldo_netto']) ? $subDatas[$subRekening_id]['saldo_netto'] : 0;
                    $strKey = "val_$subRekening_id";
                    $$strKey = isset($subDatas[$subRekening_id]) ? $subDatas[$subRekening_id] : 0;

                }

                /* ---------------------------------
                    [777] => laba(rugi) opname supplies
                    [1] => delivery cost
                    [2] => direct labor
                    [4] => quality
                ------------------------------------ */

                $project = $penjualan_projek;

                $penjualan_net = ($penjualan - $return_penjualan) + $project;

                $total_biaya = 0;
                foreach ($arrKeyBiaya as $ke_rek => $item) {
                    $lr_nilai = isset($data[$ke_rek]['total_kredit']) ? $data[$ke_rek]['total_kredit'] - $data[$ke_rek]['total_debet'] : 0;
                    $total_biaya += $lr_nilai;
                }

                $laba_rugi_lain_lain = 0;
                foreach ($arrKeyRll as $key_rek => $item) {
                    $lr_nilai = isset($data[$key_rek]['total_kredit']) ? $data[$key_rek]['total_kredit'] - $data[$key_rek]['total_debet'] : 0;
                    $laba_rugi_lain_lain += $lr_nilai;
                }
                // cekHere($periode." ". $laba_rugi_lain_lain);
                // cekHere("$laba_rugi_lain_lain = $kerugian + $jasa_kirim + $laba_lain_lain + $laba_rugi_perubahan_grade_produk + $laba_rugi_selisih_adjusment + $quality + $delivery_cost + $direct_labor + $beban_lain_lain + $pendapatan;");
                /*
                 * hpp project belum dijumlah sebagai penjualan bruto
                 * penjualan project belum masuk sebagai penjualan
                 * jadi nilai bruto belum sesuai, laba rugi lain
                 * lk = penjualan - hpp &
                 * hpp =  penjualan - lk
                 * penjualan = hpp + lk
                 *  Tulislah "laba bersih" dari scr{pembersih} jika
                    value = src{penjualannetto} - src{Totalbiaya}
                    DAN value + src{Totalbiaya} = src{penjualannetto}
                 */

                /*new 11/10/2022*/
                $bruto = 0;
                $netto = 0;
                if ($total_biaya < 0) {
                    $total_biaya_2 = $total_biaya * -1;
                } else {
                    $total_biaya_2 = $total_biaya;
                }
                if ($laba_rugi_lain_lain < 0) {
                    $laba_rugi_lain_lain_2 = $laba_rugi_lain_lain * -1;
                    $biaya_tanpa_hpp = $total_biaya_2 + $laba_rugi_lain_lain_2;
                } else {
                    $laba_rugi_lain_lain_2 = $laba_rugi_lain_lain;
                    $biaya_tanpa_hpp = $total_biaya_2 - $laba_rugi_lain_lain_2;
                }

                // $biaya_tanpa_hpp = ($total_biaya < 0 ? ($total_biaya * -1) : $total_biaya) - ($laba_rugi_lain_lain < 0 ? ($laba_rugi_lain_lain * -1) : ($laba_rugi_lain_lain * 1));

                // $biaya_tanpa_hpp = $total_biaya + $laba_rugi_lain_lain;
                // $total_hpp = $hpp + $hpp_projek + $efisiensi_biaya;
                // cekHijau("$total_hpp = $hpp + $hpp_projek;");
                $bruto = $data['bom'];
                $netto = $data['efisiensi'];
                $project = $data['efisiensi'];
                $total_hpp = $data['realisasi'];
                // cekHitam($val_2);
                // cekHitam($val_777['saldo_netto']);
                $arrLabel[] = $periode;
                $arrBruto[] = $bruto;
                $arrNetto[] = $netto;
                $arrHpp[] = $total_hpp;
                $arrBiaya[] = $val_2['saldo_netto'];
                $arrBiayaHpp[] = $val_4['saldo_netto'];;
                $arrPenj[] = $penjualan_net;
                $arrProj[] = isset($val_777['saldo_netto']) ? $val_777['saldo_netto'] : 0;

                $result = array(
                    "label" => $arrLabel,
                    "bruto" => $arrBruto,
                    "netto" => $arrNetto,
                    "hpp" => $arrHpp,
                    "biaya" => $arrBiaya,
                    "biayahpp" => $arrBiayaHpp,
                    "penjualan" => $arrPenj,
                    "project" => $arrProj,
                );
            }

            $label_series = array();
            $label_series["bruto"] = "BOM";
            $label_series["hpp"] = "Realisasi";
            $label_series["netto"] = "Efisiensi";
            $label_series["biaya"] = "Direct Labor";
            $label_series["biayahpp"] = "Quality";
            $label_series["project"] = "laba(rugi) opname supplies";

            // arrPrint($_GET);
            /*----merubah tampilan cart batang atau line---*/
            $tipe_series = array();
            if (isset($_GET['tipe']) && $_GET['tipe'] == 1) {
                $tipe_series["hpp"] = "column";
                $tipe_series["netto"] = "column";

                $tipe = 0;
            } else {
                $tipe_series["hpp"] = "column";
                $tipe_series["netto"] = "spline";

                $tipe = 1;
            }
            // arrPrint($result);
        }

        $class = get_class();

        $link_cart = base_url() . "dashboard/Graph/viewEfisiensiBomBlnan?tipe=$tipe";

        $data = array(
            "mode" => "viewEfisiensiBomBlnan",
            "title" => "Efisiensi produksi $tahun",
            "subTitle" => "<a href=\"javascript:void(0);\" id=\"mode_$keyBiaya\" classs=\"btn\"><i class=\"fa fa-bar-chart\"></i></a>",
            "label_series" => $label_series,
            "tipe_series" => $tipe_series,
            "content" => $result,
            "periode" => '',
            "indeks" => '',
            "keyBiaya" => '',
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
            "link_cart" => $link_cart,
        );
        $this->load->view("graph", $data);

    }

    public function viewMultyEfisiensiBomBlnan()
    {
        // $this->load->model("Mdls/MdlRugilaba");
        // $d = new MdlRugilaba();
        // $thn_now = dtimeNow('Y');
        // $thn_batas = $thn_now - 3;
        // //============= B I A Y A =============
        // $d->addFilter("thn>'$thn_batas'");
        // $d->addFilter("kategori='biaya'");
        // $d->addFilter("periode='bulanan'");
        // //        $d->addFilter("rekening='penjualan'");
        // $tmp1 = $d->lookupAll()->result();
        // // cekMerah($this->db->last_query());
        //
        // $rTemp1 = array();
        // if (sizeof($tmp1) > 0) {
        //     foreach ($tmp1 as $row) {
        //         $bl = $row->bln;
        //         $yr = $row->thn;
        //         $rTemp1["$yr-$bl"][] = $row;
        //     }
        // }
        // $arrBiaya = array();
        // if (sizeof($rTemp1) > 0) {
        //     $tmpResult = array();
        //     foreach ($rTemp1 as $title => $row2) {
        //         $kredit = 0;
        //         $debet = 0;
        //         $totalKredit = array();
        //         $totalDebet = array();
        //         $tmps = array();
        //         $total = 0;
        //         foreach ($row2 as $row3) {
        //             if (!isset($totalKredit[$row3->rekening])) {
        //                 $totalKredit[$row3->rekening] = 0;
        //             }
        //             if (!isset($totalDebet[$row3->rekening])) {
        //                 $totalDebet[$row3->rekening] = 0;
        //             }
        //             $totalKredit[$row3->rekening] += $row3->kredit;
        //             $totalDebet[$row3->rekening] += $row3->debet;
        //             $tmps[$row3->rekening] = array(
        //                 "total_kredit" => $totalKredit[$row3->rekening],
        //                 "total_debet"  => $totalDebet[$row3->rekening],
        //             );
        //             $tmpResult[$title] = $tmps;
        //         }
        //     }
        //     $arrBiaya = $tmpResult;
        // }

        //=============PENJUALAN=============
        /*
         * saldo_kredit : anggaran
         * saldo_debet : realisasi
         * efisiensi = $saldo_kredit - saldo_debet
         *
         * */

        $tahun = dtimeNow('Y');
        $bulan = dtimeNow('m');
        $tmp1 = $this->call_efisiensi_bom_pembantu_bulanan($tahun, $bulan);
        //        showLast_query("merah");
        //        arrPrint($tmp1);
        $rTemp1 = array();
        $subRekening = array();
        if (sizeof($tmp1) > 0) {
            foreach ($tmp1 as $row) {
                $bl = $row->bln;
                $yr = $row->thn;
                $ext_id = $row->extern_id;
                $ext_nama = $row->extern_nama;
                $saldo_kredit = $row->saldo_kredit;
                $saldo_debet = $row->saldo_debet;
                $saldo_netto = $saldo_kredit - $saldo_debet;


                // $rTemp1["$yr-$bl"][] = $row;
                $rTemp1["$yr-$bl"][$ext_id]["saldo_kredit"] = $saldo_kredit;
                $rTemp1["$yr-$bl"][$ext_id]["saldo_debet"] = $saldo_debet;
                $rTemp1["$yr-$bl"][$ext_id]["saldo_netto"] = $saldo_netto;

                $subRekening[$ext_id] = $ext_nama;
            }
        }
        // arrPrintHijau($subRekening);
        // arrPrintKuning($rTemp1);
        // foreach ($rTemp1 as $ky => $item) {
        //     arrPrintHijau($item);
        //
        // }
        $tmp2 = $this->call_efisiensi_bom_bulanan($tahun, $bulan);
        // cekMerah($this->db->last_query());
        $rTemp2 = array();
        if (sizeof($tmp2) > 0) {
            foreach ($tmp2 as $row) {
                $bl = $row->bln;
                $yr = $row->thn;
                $rTemp2["$yr-$bl"][] = $row;
            }
        }
        // arrPrintHijau($rTemp2);
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
                    $totalKredit[$row3->rekening] += $row3->saldo_kredit;
                    $totalDebet[$row3->rekening] += $row3->saldo_debet;
                    $tmps = array(
                        "bom" => $totalKredit[$row3->rekening],
                        "realisasi" => $totalDebet[$row3->rekening],
                        "efisiensi" => $totalKredit[$row3->rekening] - $totalDebet[$row3->rekening],
                    );
                    $tmpResult[$title] = $tmps;
                }
            }
            $arrPenjualan = $tmpResult;
        }
        // arrPrintPink($arrPenjualan);
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

            $this->load->config("heAccounting");
            $categoriRl = $this->config->item("categoryRL");
            // arrPrint($categoriRl);
            $arrKeyBiaya = $categoriRl[2];
            $arrKeyRll = $categoriRl[3];
            // arrPrintPink($arrPenjualan);

            $keyBiaya = isset($_GET['kb']) ? $_GET['kb'] : 1;
            foreach ($arrPenjualan as $periode => $data) {

                $penjualan = isset($data['penjualan']['total_kredit']) ? $data['penjualan']['total_kredit'] - $data['penjualan']['total_debet'] : 0;
                $penjualan_projek = isset($data['penjualan projek']['total_kredit']) ? $data['penjualan projek']['total_kredit'] - $data['penjualan projek']['total_debet'] : 0;
                $hpp_projek = isset($data['hpp projek']['total_debet']) ? $data['hpp projek']['total_debet'] : 0;
                $return_penjualan = isset($data['return penjualan']['total_debet']) ? $data['return penjualan']['total_debet'] : 0;
                $hpp = isset($data['hpp']['total_debet']) ? $data['hpp']['total_debet'] : 0;
                $efisiensi_biaya = isset($data['efisiensi biaya']['total_debet']) ? $data['efisiensi biaya']['total_debet'] - $data['efisiensi biaya']['total_kredit'] : 0;
                //-----
                $subDatas = $rTemp1[$periode];
                // arrPrintHijau($subDatas);
                foreach ($subRekening as $subRekening_id => $subRekening_nama) {
                    $val_sub_rekening = isset($subDatas[$subRekening_id]['saldo_netto']) ? $subDatas[$subRekening_id]['saldo_netto'] : 0;
                    $strKey = "val_$subRekening_id";
                    $$strKey = isset($subDatas[$subRekening_id]) ? $subDatas[$subRekening_id] : 0;

                }

                /* ---------------------------------
                    [777] => laba(rugi) opname supplies
                    [1] => delivery cost
                    [2] => direct labor
                    [4] => quality
                ------------------------------------ */

                $project = $penjualan_projek;

                $penjualan_net = ($penjualan - $return_penjualan) + $project;

                $total_biaya = 0;
                foreach ($arrKeyBiaya as $ke_rek => $item) {
                    $lr_nilai = isset($data[$ke_rek]['total_kredit']) ? $data[$ke_rek]['total_kredit'] - $data[$ke_rek]['total_debet'] : 0;
                    $total_biaya += $lr_nilai;
                }

                $laba_rugi_lain_lain = 0;
                foreach ($arrKeyRll as $key_rek => $item) {
                    $lr_nilai = isset($data[$key_rek]['total_kredit']) ? $data[$key_rek]['total_kredit'] - $data[$key_rek]['total_debet'] : 0;
                    $laba_rugi_lain_lain += $lr_nilai;
                }
                // cekHere($periode." ". $laba_rugi_lain_lain);
                // cekHere("$laba_rugi_lain_lain = $kerugian + $jasa_kirim + $laba_lain_lain + $laba_rugi_perubahan_grade_produk + $laba_rugi_selisih_adjusment + $quality + $delivery_cost + $direct_labor + $beban_lain_lain + $pendapatan;");
                /*
                 * hpp project belum dijumlah sebagai penjualan bruto
                 * penjualan project belum masuk sebagai penjualan
                 * jadi nilai bruto belum sesuai, laba rugi lain
                 * lk = penjualan - hpp &
                 * hpp =  penjualan - lk
                 * penjualan = hpp + lk
                 *  Tulislah "laba bersih" dari scr{pembersih} jika
                    value = src{penjualannetto} - src{Totalbiaya}
                    DAN value + src{Totalbiaya} = src{penjualannetto}
                 */

                /*new 11/10/2022*/
                $bruto = 0;
                $netto = 0;
                if ($total_biaya < 0) {
                    $total_biaya_2 = $total_biaya * -1;
                } else {
                    $total_biaya_2 = $total_biaya;
                }
                if ($laba_rugi_lain_lain < 0) {
                    $laba_rugi_lain_lain_2 = $laba_rugi_lain_lain * -1;
                    $biaya_tanpa_hpp = $total_biaya_2 + $laba_rugi_lain_lain_2;
                } else {
                    $laba_rugi_lain_lain_2 = $laba_rugi_lain_lain;
                    $biaya_tanpa_hpp = $total_biaya_2 - $laba_rugi_lain_lain_2;
                }

                // $biaya_tanpa_hpp = ($total_biaya < 0 ? ($total_biaya * -1) : $total_biaya) - ($laba_rugi_lain_lain < 0 ? ($laba_rugi_lain_lain * -1) : ($laba_rugi_lain_lain * 1));

                // $biaya_tanpa_hpp = $total_biaya + $laba_rugi_lain_lain;
                // $total_hpp = $hpp + $hpp_projek + $efisiensi_biaya;
                // cekHijau("$total_hpp = $hpp + $hpp_projek;");
                $bom = isset($rTemp1[$periode][$keyBiaya]) ? $rTemp1[$periode][$keyBiaya]["saldo_kredit"] : 0;
                $realisasi = isset($rTemp1[$periode][$keyBiaya]) ? $rTemp1[$periode][$keyBiaya]["saldo_debet"] : 0;
                $efisiensi = $bom - $realisasi;
                $bruto = $bom;
                $netto = $efisiensi;
                $project = $data['efisiensi'];
                $total_hpp = $realisasi;
                // cekHitam($val_2);
                // cekHitam($val_777['saldo_netto']);
                $arrLabel[] = $periode;
                $arrBruto[] = $bruto;
                $arrNetto[] = $netto;
                $arrHpp[] = $total_hpp;
                $arrBiaya[] = $val_2['saldo_netto'];
                $arrBiayaHpp[] = $val_4['saldo_netto'];;
                $arrPenj[] = $penjualan_net;
                $arrProj[] = isset($val_777['saldo_netto']) ? $val_777['saldo_netto'] : 0;

                $result = array(
                    "label" => $arrLabel,
                    "bruto" => $arrBruto,
                    "netto" => $arrNetto,
                    "hpp" => $arrHpp,
                    "biaya" => $arrBiaya,
                    "biayahpp" => $arrBiayaHpp,
                    "penjualan" => $arrPenj,
                    "project" => $arrProj,
                );
            }

            $label_series = array();
            $label_series["bruto"] = "BOM";
            $label_series["hpp"] = "Realisasi";
            $label_series["netto"] = "Efisiensi";
            $label_series["biaya"] = "Direct Labor";
            $label_series["biayahpp"] = "Quality";
            $label_series["project"] = "laba(rugi) opname supplies";

            // arrPrint($_GET);
            /*----merubah tampilan cart batang atau line---*/
            $tipe_series = array();
            if (isset($_GET['tipe']) && $_GET['tipe'] == 1) {
                $tipe_series["hpp"] = "column";
                $tipe_series["netto"] = "column";

                $tipe = 0;
            } else {
                $tipe_series["hpp"] = "column";
                $tipe_series["netto"] = "spline";

                $tipe = 1;
            }
            // arrPrint($result);
        }

        $class = get_class();

        $link_cart = base_url() . "dashboard/Graph/viewMultyEfisiensiBomBlnan?tipe=$tipe&kb=$keyBiaya";

        $data = array(
            "mode" => "viewMultyEfisiensiBomBlnan",
            "title" => "Efisiensi " . $subRekening[$keyBiaya] . " $tahun",
            "subTitle" => "<a href=\"javascript:void(0);\" id=\"mode_$keyBiaya\" classs=\"btn\"><i class=\"fa fa-bar-chart\"></i></a>",
            "label_series" => $label_series,
            "tipe_series" => $tipe_series,
            "subRekening" => $subRekening,
            "keyBiaya" => $keyBiaya,
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
            "link_cart" => $link_cart,
        );
        $this->load->view("graph", $data);

    }

    public function viewMultyEfisiensiBomThn()
    {
        // $dlm_milyar = 1000000000;
        /*PENJUALAN*/
        $keyBiaya = isset($_GET['kb']) ? $_GET['kb'] : 1;
        //----------------
        $thn_lalu = previousYear();
        $tahun[] = $thn_lalu;

        // $this->load->model("Coms/ComRekening");
        // $cr = new ComRekening();
        $tahun = dtimeNow('Y');
        // $bulan = dtimeNow('m') - 3;
        // $date_req = "$tahun-$bulan";

        $var = $this->call_efisiensi_bom_pembantu_tahun($tahun);
        // $var["prediksi"] = 9999;
        // arrPrint($var[$keyBiaya]);
        // cekHere($akredit);

        // $tahun[] = dtimeNow('Y');

        /*persen penjualan now*/
        // $persen_penjualan_now = ($nilai_penjualan_now / $nilai_penjualan_past) * 100;
        // $persen_penjualan_now_f = number_format($persen_penjualan_now, 0);
        //
        // /*--prediksi target penjualan tahun ini--*/
        // $hari_dlm_setahun = 365;
        // $hari_ke = dtimeNow('z') + 1;
        // $target_penjualan = ($hari_dlm_setahun / $hari_ke) * $nilai_penjualan_now;
        // $target_penjualan_now[] = $target_penjualan;
        // // cekHere($hari_ke);
        // // cekHere(date('z',dtimeToSecond('2022-12-31')) + 1);
        //
        // /*--target ---------------*/
        // // arrPrint($_GET['target_new']);
        // if (isset($_GET['target_new'])) {
        //     $persen_target = $_GET['target_new'];
        // }
        // else {
        //     $dir = getcwd() . "/application/modules/dashboard/logs";
        //     $myfiles = fopen($dir . "/target_pertumbuhan.txt", "r") or die("Unable to open file!");
        //     $persen_target = fread($myfiles, filesize($dir . "/target_pertumbuhan.txt"));
        //     fclose($myfiles);
        // }
        // // arrPrintHijau(json_decode($persen_target, true));
        // $target_persen = $persen_target * 1;
        //
        // $adj_target = $nilai_penjualan_past * ($target_persen / 100);
        // $target_tahun_now[] = $nilai_penjualan_past + $adj_target;

        // $var = "";
        //
        // $tahun[] = "target";
        // // echo $var;
        // $var["tahun"] = $tahun;
        // $var["penjualan_past"] = $penjualan_past;
        // // $var["penjualan_past_label"] = $thn_lalu;
        // $var["penjualan_now"] = $penjualan_now;
        // $var["target_penjualan_now"] = $target_penjualan_now;
        // $var["target_tahun_now"] = $target_tahun_now;
        // // $var["penjualan_now_label"] = dtimeNow('Y');;
        //
        // arrPrintHijau($var);
        $tipe = "TH. $tahun";
        $stamp = "";
        if ($tahun == dtimeNow('Y')) {
            // $tipe = "YTD $tahun";
            $tipe = "YTD";
            // $stamp = formatTanggal($date_req, 'Y M');
            $stamp = dtimeNow('Y-M-d');
        }
        $data = array(
            "mode" => "viewEfisiensiBomThn",
            "title" => ucwords($var[$keyBiaya]["nama"]) . " $tipe",
            "subTitle" => "Efisiensi BOM $stamp",
            "keyBiaya" => $keyBiaya,
            "content" => $var[$keyBiaya],
            "penjualan_now_label" => dtimeNow('Y'),
            "penjualan_past_label" => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
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
        $this->load->view("graph", $data);
    }

    /*----scater----*/
    public function viewSebaran()
    {
        // $dlm_milyar = 1000000000;
        /*PENJUALAN*/

        //----------------
        $thn_lalu = previousYear();
        $tahun[] = $thn_lalu;

        // $this->load->model("Coms/ComRekening");
        // $cr = new ComRekening();
        $tahun = dtimeNow('Y');
        // $tahun = 2022;
        // $bulan = dtimeNow('m') - 3;
        // $date_req = "$tahun-$bulan";

        $var = $this->call_penjualan_produk_tahun($tahun);
        // $var["prediksi"] = 9999;
        // arrPrint($var);

        $tipe = "TH. $tahun";
        $stamp = "";
        if ($tahun == dtimeNow('Y')) {
            $tipe = "YTD $tahun";
            // $stamp = formatTanggal($date_req, 'Y M');
            $stamp = dtimeNow('Y-M-d');
        }
        $data = array(
            "mode" => "viewSebaran",
            "title" => "Margin Produk",
            "subTitle" => dtimeNow('M Y'),
            "keyBiaya" => "",
            "content" => $var,
            "penjualan_now_label" => dtimeNow('Y'),
            "penjualan_past_label" => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
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
        $this->load->view("graph", $data);
    }

    public function viewSebaranTtm()
    {
        // $dlm_milyar = 1000000000;
        /*PENJUALAN*/

        //----------------
        $thn_lalu = previousYear();
        $tahun[] = $thn_lalu;

        // $this->load->model("Coms/ComRekening");
        // $cr = new ComRekening();
        $tahun = dtimeNow('Y');
        // $tahun = 2022;
        // $bulan = dtimeNow('m') - 3;
        // $date_req = "$tahun-$bulan";

        $var = $this->call_penjualan_produk_tahun($tahun);
        // $var["prediksi"] = 9999;
        // arrPrint($var);

        $tipe = "TH. $tahun";
        $stamp = "";
        if ($tahun == dtimeNow('Y')) {
            $tipe = "YTD $tahun";
            // $stamp = formatTanggal($date_req, 'Y M');
            $stamp = dtimeNow('Y-M-d');
        }
        $data = array(
            "mode" => "viewSebaran",
            "title" => "Margin Produk",
            "subTitle" => dtimeNow('M Y'),
            "keyBiaya" => "",
            "content" => $var,
            "penjualan_now_label" => dtimeNow('Y'),
            "penjualan_past_label" => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
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
        $this->load->view("graph", $data);
    }

    public function call_penjualan_produk_tahun($tahun)
    {
        $data_z = $this->call_penjualan_produk_tahun_z($tahun);

        $koloms = array(
            "extern_id",
            "extern_nama",
            "rekening",
            // "extern_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            "sum(qty_kredit) as qty_kredit",
            "avg(harga) as harga",
            "harga_avg",
        );
        $this->db->select($koloms);
        $rekenings = array(
            "588spd",
            "382spd",
            "582spd",
        );
        $this->db->where_in("jenis", $rekenings);
        $condites = array(
            "year(fulldate)" => $tahun,
        );
        // $this->db->where($condites);
        $this->db->group_by('extern_id');
        $tableName = "__rek_pembantu_produk__1010030030";
        $src_pls = $this->db->get($tableName)->result();
        // showLast_query("kuning");
        // arrPrintKuning($src_pls);
        $datas = array();
        foreach ($src_pls as $speks) {
            $produk_id = $speks->extern_id;

            $datas[$produk_id] = (array)$speks + (array)$data_z[$produk_id];
        }

        return $datas;
    }

    public function call_penjualan_produk_tahun_z($tahun)
    {
        $koloms = array(
            "extern_id",
            "extern_nama",
            "rekening",
            // "extern_id",
            // "sum(debet) as debet",
            // "sum(kredit) as kredit",
            // "sum(qty_kredit) as qty_kredit",
            // "avg(harga) as harga",
            "harga_netto",
        );
        $this->db->select($koloms);
        $rekenings = array(
            "588spd",
            "382spd",
            "582spd",
        );
        $this->db->where_in("rekening", $rekenings);
        $condites = array(
            "thn" => $tahun,
            "periode" => "tahunan",
        );
        $this->db->where($condites);
        // $this->db->group_by('extern_id');
        $tableName = "z_sales_pembantu_cache";
        $src_pls = $this->db->get($tableName)->result();
        // showLast_query("kuning");
        // arrPrintKuning($src_pls);
        $datas = array();
        foreach ($src_pls as $speks) {
            $produk_id = $speks->extern_id;
            $datas[$produk_id] = $speks;
        }


        return $datas;
    }

    public function viewSebaranLajuPenjualan()
    {
        // $dlm_milyar = 1000000000;
        /*PENJUALAN*/

        //----------------
        $thn_lalu = previousYear();
        $tahun[] = $thn_lalu;

        // $this->load->model("Coms/ComRekening");
        // $cr = new ComRekening();
        $tahun = dtimeNow('Y');
        // $tahun = 2022;
        // $bulan = dtimeNow('m') - 3;
        // $date_req = "$tahun-$bulan";

        // $var = $this->call_penjualan_produk_tahun($tahun);
        $var = $this->call_penjualan_produk_tahunan_z($thn_lalu);
        // showLast_query("biru");
        // $var["prediksi"] = 9999;
        // arrPrint($var);

        $tipe = "TH. $tahun";
        $stamp = "";
        if ($tahun == dtimeNow('Y')) {
            $tipe = "YTD $tahun";
            // $stamp = formatTanggal($date_req, 'Y M');
            $stamp = dtimeNow('Y-M-d');
        }
        $data = array(
            "mode" => "viewSebaranPenjualan",
            "title" => "Prosentase Pertumbuhan Profit",
            "subTitle" => $thn_lalu - 1 . " Vs. " . $thn_lalu,
            "keyBiaya" => "",
            "content" => $var,
            "penjualan_now_label" => dtimeNow('Y'),
            "penjualan_past_label" => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
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
        $this->load->view("graph", $data);
    }

    public function viewSebaranLajuPenjualanAbsolute()
    {
        // $dlm_milyar = 1000000000;
        /*PENJUALAN*/

        //----------------
        $thn_lalu = previousYear();
        $tahun[] = $thn_lalu;

        // $this->load->model("Coms/ComRekening");
        // $cr = new ComRekening();
        $tahun = dtimeNow('Y');
        // $tahun = 2022;
        // $bulan = dtimeNow('m') - 3;
        // $date_req = "$tahun-$bulan";

        // $var = $this->call_penjualan_produk_tahun($tahun);
        $var = $this->call_penjualan_produk_tahunan_z($thn_lalu);
        // showLast_query("biru");
        // $var["prediksi"] = 9999;
        // arrPrint($var);

        $tipe = "TH. $tahun";
        $stamp = "";
        if ($tahun == dtimeNow('Y')) {
            $tipe = "YTD $tahun";
            // $stamp = formatTanggal($date_req, 'Y M');
            $stamp = dtimeNow('Y-M-d');
        }
        $data = array(
            "mode" => "viewSebaranPenjualanAbsolute",
            "title" => "Pertumbuhan Profit Absolute",
            "subTitle" => $thn_lalu - 1 . " Vs. " . $thn_lalu,
            "keyBiaya" => "",
            "content" => $var,
            "penjualan_now_label" => dtimeNow('Y'),
            "penjualan_past_label" => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
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
        $this->load->view("graph", $data);
    }

    public function viewSebaranProfitMargin()
    {
        // $dlm_milyar = 1000000000;
        /*PENJUALAN*/

        //----------------
        $thn_lalu = previousYear();
        $tahun[] = $thn_lalu;

        // $this->load->model("Coms/ComRekening");
        // $cr = new ComRekening();
        $tahun = dtimeNow('Y');
        // $tahun = 2022;
        // $bulan = dtimeNow('m') - 3;
        // $date_req = "$tahun-$bulan";

        // $var = $this->call_penjualan_produk_tahun($tahun);
        $var = $this->call_penjualan_produk_tahunan_z($thn_lalu);
        // showLast_query("biru");
        // $var["prediksi"] = 9999;
        // arrPrint($var);

        //------------------
        $koloms = array(
            "extern_id",
            "extern_nama",
            "rekening",
            // "extern_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            "sum(qty_kredit) as qty_kredit",
            "avg(harga) as harga",
            "harga_avg",
        );
        $this->db->select($koloms);
        $rekenings = array(
            "588spd",
            "382spd",
            "582spd",
        );
        $this->db->where_in("jenis", $rekenings);
        $condites = array(
            "year(fulldate)" => $tahun,
        );
        // $this->db->where($condites);
        $this->db->group_by('extern_id');
        $tableName = "__rek_pembantu_produk__1010030030";
        $src_pls = $this->db->get($tableName)->result();
        showLast_query("kuning");
        // arrPrint($src_pls);
        cekKuning(sizeof($src_pls));

        $beli_datas = array();
        foreach ($src_pls as $speks) {
            $produk_id = $speks->extern_id;

            $beli_datas[$produk_id] = (array)$speks;
        }
        // arrPrintHijau($beli_datas);
        //------------------

        $tipe = "TH. $tahun";
        $stamp = "";
        if ($tahun == dtimeNow('Y')) {
            $tipe = "YTD $tahun";
            // $stamp = formatTanggal($date_req, 'Y M');
            $stamp = dtimeNow('Y-M-d');
        }
        $data = array(
            "mode" => "viewSebaranProfitMargin",
            "title" => "Penjualan margin Profit",
            "subTitle" => $thn_lalu - 1 . " Vs. " . $thn_lalu,
            "keyBiaya" => "",
            "pembelian_data" => $beli_datas,
            "content" => $var,
            "penjualan_now_label" => dtimeNow('Y'),
            "penjualan_past_label" => $thn_lalu,
            "target_penjualan_now_label" => "'Predictive results'",
            "target_tahun_now_label" => "'Target'",
            // "persen_now"                 => $persen_penjualan_now_f,
            // "target_persen"              => $target_persen,
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
        $this->load->view("graph", $data);
    }

    public function call_penjualan_produk_tahunan_z($tahun)
    {
        $tahun_lalu = $tahun - 1;
        $koloms = array(
            "extern_id",
            "extern_nama",
            "rekening",
            // "extern_id",
            // "sum(debet) as debet",
            // "sum(kredit) as kredit",
            // "sum(qty_kredit) as qty_kredit",
            // "avg(harga) as harga",
            "harga_netto",
            "saldo_kirim",
            "thn"
        );
        $this->db->select($koloms);
        $rekenings = array(
            // "588spd",
            "7499",
            "382spd",
            "582spd",
        );
        $this->db->where_in("rekening", $rekenings);
        $tahuns = array(
            "thn_ini" => $tahun,
            "thn_lalu" => $tahun_lalu
        );
        $this->db->where_in("thn", $tahuns);
        $condites = array(
            // "thn"     => $tahun,
            "periode" => "tahunan",
        );
        $this->db->where($condites);
        // $this->db->group_by('extern_id');
        $tableName = "z_sales_pembantu_cache";
        $src_pls = $this->db->get($tableName)->result();
        // showLast_query("kuning");
        // arrPrintKuning($src_pls);
        $datas = array();
        foreach ($src_pls as $speks) {
            $produk_id = $speks->extern_id;
            $produk_nama = $speks->extern_nama;
            $thn = $speks->thn;

            $thn_ini = $tahuns["thn_ini"];
            $thn_lalu = $tahuns["thn_lalu"];
            if ($thn_ini == $thn) {
                $datas["thn_ini"][$produk_id] = $speks;
            }
            if ($thn_lalu == $thn) {
                $datas["thn_lalu"][$produk_id] = $speks;
            }
            $datas["produk"][$produk_id] = $produk_nama;
        }


        return $datas;
    }

    /* -------------------------------------------------------------------------
  * untuk mengon off kan koneksi ke mongo dari confic heWeb
  * -------------------------------------------------------------------------*/
    private function MdbConnection()
    {
        $this->load->config("heWebs");
        $con_webs = $this->config->item("mongo");
        // arrPrintKuning($con_webs);

        $status_ping = $this->ping_monngo();
        $mdb_connection = false;
        if ($status_ping == 0) {
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
            "link_id" => 0,
        );
        $this->db->limit(1);
        $this->db->order_by("id", "desc");
        $this->db->select("id");
        $src_tr = $tr->lookupByCondition($condites)->row();

        return $src_tr->id;
    }

    private function ping_monngo()
    {
        $host = "192.168.5.14";
        $status = 0;
        $pingresult = exec("/bin/ping -c 4 $host", $outcome, $status);

        return $status;
    }
}