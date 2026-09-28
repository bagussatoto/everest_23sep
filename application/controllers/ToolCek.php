<?php


class ToolCek extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
        $this->load->helper("he_angka");
    }

    function index()
    {
        $arrTools = array(
            "kas" => "viewUnsyncedKas",
            "produk" => "viewUnsyncedProduk",
            "produk rakitan" => "viewUnsyncedProdukRakitan",
            "supplies" => "viewUnsyncedSupplies",
            "valas" => "viewUnsyncedValas",
            "audit_project_3way" => "cekProjectTps3Way",
            "edit_registry_project" => "editRegistryProject",
        );

//        foreach ($arrTools as $key => $value) {
//            echo "<div>";
//            echo "<h3>";
//            echo "<a href='" . base_url() . get_class($this) . "/$value' target='_blank'>:: $key ::</a>";
//            echo "</h3>";
//            echo "</div>";
//        }
    }

    function cekMasterDetail()
    {
        $tbl_master = "__rek_master__1010030030";
        $tbl_detail = "__rek_pembantu_produk__1010030030";

//        $cabang_id = "-1";
        $cabang_id = "1";
        $tahun = "2024";
//        $tahun = "2025";


        $where = array(
            "cabang_id" => $cabang_id,
            "year(dtime)" => $tahun,
        );
        //---------------------------------
        $this->db->where($where);
        $queryMaster = $this->db->get($tbl_master)->result();
//        showLast_query("biru");
//        cekBiru(count($query));
        //---------------------------------

        $this->db->where($where);
        $queryDetail = $this->db->get($tbl_detail)->result();
        showLast_query("kuning");
//        cekKuning(count($query));
        //---------------------------------

        $detailData = array();
        foreach ($queryDetail as $spec) {
            $trid = $spec->transaksi_id;
            if (!isset($detailData[$trid]["debet"])) {
                $detailData[$trid]["debet"] = 0;
            }
            if (!isset($detailData[$trid]["kredit"])) {
                $detailData[$trid]["kredit"] = 0;
            }
            $detailData[$trid]["debet"] += $spec->debet;
            $detailData[$trid]["kredit"] += $spec->kredit;

//            break;
        }
//arrPrint($detailData);
//mati_disini();
        $masterData = array();
        foreach ($queryMaster as $spec) {
            $trid = $spec->transaksi_id;
            $trno = $spec->transaksi_no;
            $debet = $spec->debet;
            $kredit = $spec->kredit;
            $dtime = $spec->dtime;
            $fulldate = $spec->fulldate;
            $debet_detail = isset($detailData[$trid]["debet"]) ? $detailData[$trid]["debet"] : 0;
            $kredit_detail = isset($detailData[$trid]["kredit"]) ? $detailData[$trid]["kredit"] : 0;

            $masterData[$trid]["trid"] = $trid;
            $masterData[$trid]["trno"] = $trno;
            $masterData[$trid]["debet_detail"] = $debet_detail;
            $masterData[$trid]["kredit_detail"] = $kredit_detail;
            $masterData[$trid]["dtime"] = $dtime;
            $masterData[$trid]["fulldate"] = $fulldate;
            if (!isset($masterData[$trid]["master_debet"])) {
                $masterData[$trid]["master_debet"] = 0;
            }
            if (!isset($masterData[$trid]["master_kredit"])) {
                $masterData[$trid]["master_kredit"] = 0;
            }
            $masterData[$trid]["master_debet"] += $debet;
            $masterData[$trid]["master_kredit"] += $kredit;
//            break;
        }
//        arrPrintCyan($masterData[372490]);

        $str = "<table rules='all' width='100%' style='border:1px solid black;'>";
        $str .= "<tr style='text-align:center;font-size:15px;font-weight:bold;'>";
        $str .= "<td>No.</td>";
        $str .= "<td>tgl</td>";
        $str .= "<td>trid</td>";
        $str .= "<td>nomer</td>";
        $str .= "<td style='background-color:yellow;'>m debet</td>";
        $str .= "<td style='background-color:#5cb730;'>m kredit</td>";
        $str .= "<td style='background-color:yellow;'>d debet</td>";
        $str .= "<td style='background-color:#5cb730;'>d kredit</td>";
        $str .= "</tr>";
        $no = 0;
        $master_debet_total = 0;
        $master_kredit_total = 0;
        $debet_detail_total = 0;
        $kredit_detail_total = 0;
        foreach ($masterData as $trid => $tridspec) {
            $fulldate = $tridspec["fulldate"];
            $nomer = $tridspec["trno"];
            $master_debet = $tridspec["master_debet"];
            $master_kredit = $tridspec["master_kredit"];
            $debet_detail = $tridspec["debet_detail"];
            $kredit_detail = $tridspec["kredit_detail"];
            $selisih_debet_cek = $master_debet - $debet_detail;
            $selisih_debet_cek = ($selisih_debet_cek < 0) ? ($selisih_debet_cek * -1) : $selisih_debet_cek;
            $selisih_kredit_cek = $master_kredit - $kredit_detail;
            $selisih_kredit_cek = ($selisih_kredit_cek < 0) ? ($selisih_kredit_cek * -1) : $selisih_kredit_cek;
            if (($selisih_debet_cek > 100) || ($selisih_kredit_cek > 100)) {

                $master_debet_total += $master_debet;
                $master_kredit_total += $master_kredit;
                $debet_detail_total += $debet_detail;
                $kredit_detail_total += $kredit_detail;
                $master_debet_f = number_format($master_debet);
                $master_kredit_f = number_format($master_kredit);
                $debet_detail_f = number_format($debet_detail);
                $kredit_detail_f = number_format($kredit_detail);
                $no++;
                $str .= "<tr>";
                $str .= "<td>$no</td>";
                $str .= "<td>$fulldate</td>";
                $str .= "<td>$trid</td>";
                $str .= "<td>$nomer</td>";
                $str .= "<td style='background-color:yellow;text-align:right;'>$master_debet_f</td>";
                $str .= "<td style='background-color:#5cb730;text-align:right;'>$master_kredit_f</td>";
                $str .= "<td style='background-color:yellow;text-align:right;'>$debet_detail_f</td>";
                $str .= "<td style='background-color:#5cb730;text-align:right;'>$kredit_detail_f</td>";
                $str .= "</tr>";

            }
        }
        $selisih_debet = $master_debet_total - $debet_detail_total;
        $selisih_kredit = $master_kredit_total - $kredit_detail_total;
        $master_debet_total_f = number_format($master_debet_total);
        $master_kredit_total_f = number_format($master_kredit_total);
        $debet_detail_total_f = number_format($debet_detail_total);
        $kredit_detail_total_f = number_format($kredit_detail_total);
        $selisih_debet_f = number_format($selisih_debet);
        $selisih_kredit_f = number_format($selisih_kredit);
        $str .= "<tr style='text-align:center;font-size:15px;font-weight:bold;'>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td style='background-color:yellow;text-align:right;'>$master_debet_total_f</td>";
        $str .= "<td style='background-color:#5cb730;text-align:right;'>$master_kredit_total_f</td>";
        $str .= "<td style='background-color:yellow;text-align:right;'>$debet_detail_total_f</td>";
        $str .= "<td style='background-color:#5cb730;text-align:right;'>$kredit_detail_total_f</td>";
        $str .= "</tr>";
        $str .= "</table>";
        $str .= "<div >";
        $str .= "SELISIH DEBET: $selisih_debet_f";
        $str .= "<br>SELISIH KREDIT: $selisih_kredit_f";
        $str .= "</div>";
        echo $str;
    }


    public function cekCacheDobel()
    {
        $tbl_master_cache = "__rek_master__1010030030";
        $tbl_master = "__rek_master__1010030030";
        $tbl_detail = "__rek_pembantu_produk__1010030030";
        $tbl_detail_cache = "_rek_pembantu_produk_cache";
        $cabang_id = "1";
        $gudang_id = "-10";
        $periode = "forever";
        $arrDobel = array();
        $where = array(
            "cabang_id" => $cabang_id,
            "gudang_id" => $gudang_id,
            "periode" => $periode,
        );
        $this->db->where($where);
        $queryDetailCache = $this->db->get($tbl_detail_cache)->result();
        showLast_query("biru");

        foreach ($queryDetailCache as $spec) {

            $arrDobel[$spec->extern_id][$spec->id] = $spec->extern_id;
        }
        foreach ($arrDobel as $pid => $pspec) {
            if (sizeof($pspec) > 1) {
                arrPrint($pspec);
            }
        }


    }


    public function patchMasterValue()
    {
        $tbl_master_piutang = "__rek_master__1010060010";
        $tbl_master_hkp = "__rek_master__2040010";
        $tbl_master_hpp = "__rek_master__5010";
        $tbl_master = "__rek_master__1010030030";
//        $tbl_master = "__rek_master__2040010";
//        $tbl_master = "__rek_master__5010";
//        $tbl_master = "__rek_master__1010060010";
        $tbl_detail = "__rek_pembantu_produk__1010030030";

        $cabang_id = "-1";
//        $cabang_id = "1";
//        $tahun = "2024";
//        $tahun = "2025";


        $where = array(
            "cabang_id" => $cabang_id,
//            "year(dtime)" => $tahun,
//            "transaksi_id" => 49290,
            "transaksi_id>" => 5,
            "gudang_id<>" => 0,
            "jenis" => "9911",
        );
        $where_master = array(
            "cabang_id" => $cabang_id,
//            "year(dtime)" => $tahun,
        );
        //---------------------------------
        $this->db->where($where);
        $queryDetail = $this->db->get($tbl_detail)->result();
        showLast_query("kuning");
//        cekKuning(count($query));
//        mati_disini(__LINE__);
        //---------------------------------
        $detailData = array();
        $trIDs = array();
        foreach ($queryDetail as $spec) {
            $trid = $spec->transaksi_id;
            if ($trid > 0) {
                $trIDs[$trid] = $trid;
            }
            if (!isset($detailData[$trid]["debet"])) {
                $detailData[$trid]["debet"] = 0;
            }
            if (!isset($detailData[$trid]["kredit"])) {
                $detailData[$trid]["kredit"] = 0;
            }
            $detailData[$trid]["debet"] += $spec->debet;
            $detailData[$trid]["kredit"] += $spec->kredit;
        }

        $this->db->where($where_master);
        $this->db->where_in("transaksi_id", $trIDs);
        $queryMaster = $this->db->get($tbl_master)->result();
//        showLast_query("biru");
//        cekBiru(count($query));
        //---------------------------------


        $this->db->trans_start();


        $masterData = array();
        foreach ($queryMaster as $spec) {
            $trid = $spec->transaksi_id;
            $trno = $spec->transaksi_no;
            $debet = $spec->debet;
            $kredit = $spec->kredit;
            $dtime = $spec->dtime;
            $fulldate = $spec->fulldate;
            $debet_detail = isset($detailData[$trid]["debet"]) ? $detailData[$trid]["debet"] : 0;
            $kredit_detail = isset($detailData[$trid]["kredit"]) ? $detailData[$trid]["kredit"] : 0;

            $masterData[$trid]["trid"] = $trid;
            $masterData[$trid]["trno"] = $trno;
            $masterData[$trid]["debet_detail"] = $debet_detail;
            $masterData[$trid]["kredit_detail"] = $kredit_detail;
            $masterData[$trid]["dtime"] = $dtime;
            $masterData[$trid]["fulldate"] = $fulldate;
            if (!isset($masterData[$trid]["master_debet"])) {
                $masterData[$trid]["master_debet"] = 0;
            }
            if (!isset($masterData[$trid]["master_kredit"])) {
                $masterData[$trid]["master_kredit"] = 0;
            }
            $masterData[$trid]["master_debet"] += $debet;
            $masterData[$trid]["master_kredit"] += $kredit;
//            break;
        }
//        arrPrintCyan($masterData[372490]);

        $str = "<table rules='all' width='100%' style='border:1px solid black;'>";
        $str .= "<tr style='text-align:center;font-size:15px;font-weight:bold;'>";
        $str .= "<td>No.</td>";
        $str .= "<td>tgl</td>";
        $str .= "<td>trid</td>";
        $str .= "<td>nomer</td>";
        $str .= "<td style='background-color:yellow;'>m debet</td>";
        $str .= "<td style='background-color:#5cb730;'>m kredit</td>";
        $str .= "<td style='background-color:yellow;'>d debet</td>";
        $str .= "<td style='background-color:#5cb730;'>d kredit</td>";
        $str .= "</tr>";
        $no = 0;
        $master_debet_total = 0;
        $master_kredit_total = 0;
        $debet_detail_total = 0;
        $kredit_detail_total = 0;
        foreach ($masterData as $trid => $tridspec) {
            $fulldate = $tridspec["fulldate"];
            $nomer = $tridspec["trno"];
            $master_debet = $tridspec["master_debet"];
            $master_kredit = $tridspec["master_kredit"];
            $debet_detail = $tridspec["debet_detail"];
            $kredit_detail = $tridspec["kredit_detail"];
            $netto_master = $master_debet - $master_kredit;
            $netto_detail = $debet_detail - $kredit_detail;
            // persediaan
            $selisih_debet_cek = $master_debet - $debet_detail;
            $selisih_kredit_cek = $debet_detail - $kredit_detail;
            $selisih_netto_cek = $netto_master - $netto_detail;
            // HPP
//            $selisih_debet_cek = $master_kredit - $debet_detail;
//            $selisih_kredit_cek = $master_debet - $kredit_detail;
            // Hutang ke pusat
//            $selisih_debet_cek = $master_kredit - $debet_detail;
//            $selisih_kredit_cek = $master_debet - $kredit_detail;
            // PIUTANG CABANG
//            $selisih_debet_cek = $master_kredit - $debet_detail;
//            $selisih_kredit_cek = $master_debet - $kredit_detail;

            $selisih_debet_cek = ($selisih_debet_cek < 0) ? ($selisih_debet_cek * -1) : $selisih_debet_cek;
            $selisih_kredit_cek = ($selisih_kredit_cek < 0) ? ($selisih_kredit_cek * -1) : $selisih_kredit_cek;
            $selisih_netto_cek = ($selisih_netto_cek < 0) ? ($selisih_netto_cek * -1) : $selisih_netto_cek;
//            cekUngu("[$selisih_debet_cek] [$selisih_kredit_cek]");
//            if (($selisih_debet_cek > 100) || ($selisih_kredit_cek > 100)) {
//            if ($selisih_netto_cek > 100) {
            $master_debet_total += $master_debet;
            $master_kredit_total += $master_kredit;
            $debet_detail_total += $debet_detail;
            $kredit_detail_total += $kredit_detail;
            $master_debet_f = number_format($master_debet);
            $master_kredit_f = number_format($master_kredit);
            $debet_detail_f = number_format($debet_detail);
            $kredit_detail_f = number_format($kredit_detail);
            $no++;
            $str .= "<tr>";
            $str .= "<td>$no</td>";
            $str .= "<td>$fulldate</td>";
            $str .= "<td>$trid</td>";
            $str .= "<td>$nomer</td>";
            $str .= "<td style='background-color:yellow;text-align:right;'>$master_debet_f</td>";
            $str .= "<td style='background-color:#5cb730;text-align:right;'>$master_kredit_f</td>";
            $str .= "<td style='background-color:yellow;text-align:right;'>$debet_detail_f</td>";
            $str .= "<td style='background-color:#5cb730;text-align:right;'>$kredit_detail_f</td>";
            $str .= "</tr>";

            //update tabel master persediaan----------------------------
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                $where_cek = array(
                    "transaksi_id" => $trid,
                    "cabang_id" => $cabang_id,
                );
                $this->db->where($where_cek);
                $queryMaster = $this->db->get($tbl_master)->result();
                showLast_query("kuning");
                if (sizeof($queryMaster) > 0) {
                    $this->db->set('debet', 0);
                    $this->db->set('kredit', 0);
                    $this->db->where($where_cek);
                    $this->db->update($tbl_master);
                    showLast_query("orange");

                    $where_update = array(
                        "id" => $queryMaster[0]->id,
                    );
                    $this->db->set('debet', $debet_detail);
                    $this->db->set('kredit', $kredit_detail);
                    $this->db->where($where_update);
                    $this->db->update($tbl_master);
                    showLast_query("orange");
                }
            }
            //----------------------------
            //update tabel master hpp----------------------------
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                $where_cek = array(
                    "transaksi_id" => $trid,
                    "cabang_id" => $cabang_id,
                );
                $this->db->where($where_cek);
                $queryMasterHpp = $this->db->get($tbl_master_hpp)->result();
                showLast_query("hitam");
                if (sizeof($queryMasterHpp) > 0) {
                    $this->db->set('debet', 0);
                    $this->db->set('kredit', 0);
                    $this->db->where($where_cek);
                    $this->db->update($tbl_master_hpp);
                    showLast_query("hitam");

                    $where_update = array(
                        "id" => $queryMasterHpp[0]->id,
                    );
                    $this->db->set('debet', $kredit_detail);
                    $this->db->set('kredit', $debet_detail);
                    $this->db->where($where_update);
                    $this->db->update($tbl_master_hpp);
                    showLast_query("hitam");
                }
            }
            //----------------------------
            //update tabel master hutang ke pusat----------------------------
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                $where_cek = array(
                    "transaksi_id" => $trid,
                    "cabang_id" => $cabang_id,
                );
                $this->db->where($where_cek);
                $queryMasterHkp = $this->db->get($tbl_master_hkp)->result();
                showLast_query("hitam");
                if (sizeof($queryMasterHkp) > 0) {
                    $this->db->set('debet', 0);
                    $this->db->set('kredit', 0);
                    $this->db->where($where_cek);
                    $this->db->update($tbl_master_hkp);
                    showLast_query("hitam");

                    $where_update = array(
                        "id" => $queryMasterHkp[0]->id,
                    );
                    $this->db->set('debet', $kredit_detail);
                    $this->db->set('kredit', $debet_detail);
                    $this->db->where($where_update);
                    $this->db->update($tbl_master_hkp);
                    showLast_query("hitam");
                }
            }
            //----------------------------
            //update tabel master piutang cabang----------------------------
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                $where_cek = array(
                    "transaksi_id" => $trid,
                    "cabang_id" => $cabang_id,
                );
                $this->db->where($where_cek);
                $queryMasterPiutang = $this->db->get($tbl_master_piutang)->result();
                showLast_query("hitam");
                if (sizeof($queryMasterPiutang) > 0) {
                    $this->db->set('debet', 0);
                    $this->db->set('kredit', 0);
                    $this->db->where($where_cek);
                    $this->db->update($tbl_master_piutang);
                    showLast_query("hitam");

                    $where_update = array(
                        "id" => $queryMasterPiutang[0]->id,
                    );
                    $this->db->set('debet', $kredit_detail);
                    $this->db->set('kredit', $debet_detail);
                    $this->db->where($where_update);
                    $this->db->update($tbl_master_piutang);
                    showLast_query("hitam");
                }
            }
            //----------------------------
//            }
        }
        $selisih_debet = $master_debet_total - $debet_detail_total;
        $selisih_kredit = $master_kredit_total - $kredit_detail_total;
        $master_debet_total_f = number_format($master_debet_total);
        $master_kredit_total_f = number_format($master_kredit_total);
        $debet_detail_total_f = number_format($debet_detail_total);
        $kredit_detail_total_f = number_format($kredit_detail_total);
        $selisih_debet_f = number_format($selisih_debet);
        $selisih_kredit_f = number_format($selisih_kredit);
        $str .= "<tr style='text-align:center;font-size:15px;font-weight:bold;'>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td style='background-color:yellow;text-align:right;'>$master_debet_total_f</td>";
        $str .= "<td style='background-color:#5cb730;text-align:right;'>$master_kredit_total_f</td>";
        $str .= "<td style='background-color:yellow;text-align:right;'>$debet_detail_total_f</td>";
        $str .= "<td style='background-color:#5cb730;text-align:right;'>$kredit_detail_total_f</td>";
        $str .= "</tr>";
        $str .= "</table>";
        $str .= "<div >";
        $str .= "SELISIH DEBET: $selisih_debet_f";
        $str .= "<br>SELISIH KREDIT: $selisih_kredit_f";
        $str .= "</div>";
        echo $str;


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    public function patchMasterValuePiutangCabang()
    {
        $tbl_master_piutang = "__rek_master__1010060010";
        $tbl_master_hkp = "__rek_master__2040010";
        $tbl_master_hpp = "__rek_master__5010";
//        $tbl_master = "__rek_master__1010030030";
//        $tbl_master = "__rek_master__2040010";
//        $tbl_master = "__rek_master__5010";
        $tbl_master = "__rek_master__1010060010";
        $tbl_detail = "__rek_pembantu_produk__1010030030";

        $cabang_id = "-1";
//        $cabang_id = "1";
//        $tahun = "2024";
//        $tahun = "2025";


        $where = array(
            "cabang_id" => $cabang_id,
//            "year(dtime)" => $tahun,
//            "transaksi_id" => 49290,
            "transaksi_id>" => 5,
            "gudang_id<>" => 0,
        );
        $where_master = array(
            "cabang_id" => $cabang_id,
//            "year(dtime)" => $tahun,
        );
        //---------------------------------
        $this->db->where($where);
        $queryDetail = $this->db->get($tbl_detail)->result();
        showLast_query("kuning");
//        cekKuning(count($query));
//        mati_disini(__LINE__);
        //---------------------------------
        $detailData = array();
        $trIDs = array();
        foreach ($queryDetail as $spec) {
            $trid = $spec->transaksi_id;
            if ($trid > 0) {
                $trIDs[$trid] = $trid;
            }
            if (!isset($detailData[$trid]["debet"])) {
                $detailData[$trid]["debet"] = 0;
            }
            if (!isset($detailData[$trid]["kredit"])) {
                $detailData[$trid]["kredit"] = 0;
            }
            $detailData[$trid]["debet"] += $spec->debet;
            $detailData[$trid]["kredit"] += $spec->kredit;
        }

        $this->db->where($where_master);
        $this->db->where_in("transaksi_id", $trIDs);
        $queryMaster = $this->db->get($tbl_master)->result();
//        showLast_query("biru");
//        cekBiru(count($query));
        //---------------------------------


        $this->db->trans_start();


        $masterData = array();
        foreach ($queryMaster as $spec) {
            $trid = $spec->transaksi_id;
            $trno = $spec->transaksi_no;
            $debet = $spec->debet;
            $kredit = $spec->kredit;
            $dtime = $spec->dtime;
            $fulldate = $spec->fulldate;
            $debet_detail = isset($detailData[$trid]["debet"]) ? $detailData[$trid]["debet"] : 0;
            $kredit_detail = isset($detailData[$trid]["kredit"]) ? $detailData[$trid]["kredit"] : 0;

            $masterData[$trid]["trid"] = $trid;
            $masterData[$trid]["trno"] = $trno;
            $masterData[$trid]["debet_detail"] = $debet_detail;
            $masterData[$trid]["kredit_detail"] = $kredit_detail;
            $masterData[$trid]["dtime"] = $dtime;
            $masterData[$trid]["fulldate"] = $fulldate;
            if (!isset($masterData[$trid]["master_debet"])) {
                $masterData[$trid]["master_debet"] = 0;
            }
            if (!isset($masterData[$trid]["master_kredit"])) {
                $masterData[$trid]["master_kredit"] = 0;
            }
            $masterData[$trid]["master_debet"] += $debet;
            $masterData[$trid]["master_kredit"] += $kredit;
//            break;
        }
//        arrPrintCyan($masterData[372490]);

        $str = "<table rules='all' width='100%' style='border:1px solid black;'>";
        $str .= "<tr style='text-align:center;font-size:15px;font-weight:bold;'>";
        $str .= "<td>No.</td>";
        $str .= "<td>tgl</td>";
        $str .= "<td>trid</td>";
        $str .= "<td>nomer</td>";
        $str .= "<td style='background-color:yellow;'>m debet</td>";
        $str .= "<td style='background-color:#5cb730;'>m kredit</td>";
        $str .= "<td style='background-color:yellow;'>d debet</td>";
        $str .= "<td style='background-color:#5cb730;'>d kredit</td>";
        $str .= "</tr>";
        $no = 0;
        $master_debet_total = 0;
        $master_kredit_total = 0;
        $debet_detail_total = 0;
        $kredit_detail_total = 0;
        foreach ($masterData as $trid => $tridspec) {
            $fulldate = $tridspec["fulldate"];
            $nomer = $tridspec["trno"];
            $master_debet = $tridspec["master_debet"];
            $master_kredit = $tridspec["master_kredit"];
            $debet_detail = $tridspec["debet_detail"];
            $kredit_detail = $tridspec["kredit_detail"];
            // persediaan
//            $selisih_debet_cek = $master_debet - $debet_detail;
//            $selisih_kredit_cek = $master_kredit - $kredit_detail;
            // HPP
//            $selisih_debet_cek = $master_kredit - $debet_detail;
//            $selisih_kredit_cek = $master_debet - $kredit_detail;
            // Hutang ke pusat
//            $selisih_debet_cek = $master_kredit - $debet_detail;
//            $selisih_kredit_cek = $master_debet - $kredit_detail;
            // PIUTANG CABANG
            $selisih_debet_cek = $master_kredit - $debet_detail;
            $selisih_kredit_cek = $master_debet - $kredit_detail;

            $selisih_debet_cek = ($selisih_debet_cek < 0) ? ($selisih_debet_cek * -1) : $selisih_debet_cek;
            $selisih_kredit_cek = ($selisih_kredit_cek < 0) ? ($selisih_kredit_cek * -1) : $selisih_kredit_cek;
//            cekUngu("[$selisih_debet_cek] [$selisih_kredit_cek]");
            if (($selisih_debet_cek > 100) || ($selisih_kredit_cek > 100)) {
                $master_debet_total += $master_debet;
                $master_kredit_total += $master_kredit;
                $debet_detail_total += $debet_detail;
                $kredit_detail_total += $kredit_detail;
                $master_debet_f = number_format($master_debet);
                $master_kredit_f = number_format($master_kredit);
                $debet_detail_f = number_format($debet_detail);
                $kredit_detail_f = number_format($kredit_detail);
                $no++;
                $str .= "<tr>";
                $str .= "<td>$no</td>";
                $str .= "<td>$fulldate</td>";
                $str .= "<td>$trid</td>";
                $str .= "<td>$nomer</td>";
                $str .= "<td style='background-color:yellow;text-align:right;'>$master_debet_f</td>";
                $str .= "<td style='background-color:#5cb730;text-align:right;'>$master_kredit_f</td>";
                $str .= "<td style='background-color:yellow;text-align:right;'>$debet_detail_f</td>";
                $str .= "<td style='background-color:#5cb730;text-align:right;'>$kredit_detail_f</td>";
                $str .= "</tr>";

                //update tabel master persediaan----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMaster = $this->db->get($tbl_master)->result();
                    showLast_query("kuning");
                    if (sizeof($queryMaster) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master);
                        showLast_query("orange");

                        $where_update = array(
                            "id" => $queryMaster[0]->id,
                        );
                        $this->db->set('debet', $debet_detail);
                        $this->db->set('kredit', $kredit_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master);
                        showLast_query("orange");
                    }
                }
                //----------------------------
                //update tabel master hpp----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMasterHpp = $this->db->get($tbl_master_hpp)->result();
                    showLast_query("hitam");
                    if (sizeof($queryMasterHpp) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master_hpp);
                        showLast_query("hitam");

                        $where_update = array(
                            "id" => $queryMasterHpp[0]->id,
                        );
                        $this->db->set('debet', $kredit_detail);
                        $this->db->set('kredit', $debet_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master_hpp);
                        showLast_query("hitam");
                    }
                }
                //----------------------------
                //update tabel master hutang ke pusat----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMasterHkp = $this->db->get($tbl_master_hkp)->result();
                    showLast_query("hitam");
                    if (sizeof($queryMasterHkp) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master_hkp);
                        showLast_query("hitam");

                        $where_update = array(
                            "id" => $queryMasterHkp[0]->id,
                        );
                        $this->db->set('debet', $kredit_detail);
                        $this->db->set('kredit', $debet_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master_hkp);
                        showLast_query("hitam");
                    }
                }
                //----------------------------
                //update tabel master piutang cabang----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMasterPiutang = $this->db->get($tbl_master_piutang)->result();
                    showLast_query("hitam");
                    if (sizeof($queryMasterPiutang) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master_piutang);
                        showLast_query("hitam");

                        $where_update = array(
                            "id" => $queryMasterPiutang[0]->id,
                        );
                        $this->db->set('debet', $kredit_detail);
                        $this->db->set('kredit', $debet_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master_piutang);
                        showLast_query("hitam");
                    }
                }
                //----------------------------
            }
        }
        $selisih_debet = $master_debet_total - $debet_detail_total;
        $selisih_kredit = $master_kredit_total - $kredit_detail_total;
        $master_debet_total_f = number_format($master_debet_total);
        $master_kredit_total_f = number_format($master_kredit_total);
        $debet_detail_total_f = number_format($debet_detail_total);
        $kredit_detail_total_f = number_format($kredit_detail_total);
        $selisih_debet_f = number_format($selisih_debet);
        $selisih_kredit_f = number_format($selisih_kredit);
        $str .= "<tr style='text-align:center;font-size:15px;font-weight:bold;'>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td style='background-color:yellow;text-align:right;'>$master_debet_total_f</td>";
        $str .= "<td style='background-color:#5cb730;text-align:right;'>$master_kredit_total_f</td>";
        $str .= "<td style='background-color:yellow;text-align:right;'>$debet_detail_total_f</td>";
        $str .= "<td style='background-color:#5cb730;text-align:right;'>$kredit_detail_total_f</td>";
        $str .= "</tr>";
        $str .= "</table>";
        $str .= "<div >";
        $str .= "SELISIH DEBET: $selisih_debet_f";
        $str .= "<br>SELISIH KREDIT: $selisih_kredit_f";
        $str .= "</div>";
        echo $str;


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    public function patchMasterValueHpp()
    {
        $tbl_master_piutang = "__rek_master__1010060010";
        $tbl_master_hkp = "__rek_master__2040010";
        $tbl_master_hpp = "__rek_master__5010";
//        $tbl_master = "__rek_master__1010030030";
//        $tbl_master = "__rek_master__2040010";
        $tbl_master = "__rek_master__5010";
//        $tbl_master = "__rek_master__1010060010";
        $tbl_detail = "__rek_pembantu_produk__1010030030";

        $cabang_id = "1";
//        $cabang_id = "1";
//        $tahun = "2024";
//        $tahun = "2025";


        $where = array(
            "cabang_id" => $cabang_id,
//            "year(dtime)" => $tahun,
//            "transaksi_id" => 49290,
            "transaksi_id>" => 5,
            "gudang_id<>" => 0,
        );
        $where_master = array(
            "cabang_id" => $cabang_id,
//            "year(dtime)" => $tahun,
        );
        //---------------------------------
        $this->db->where($where);
        $queryDetail = $this->db->get($tbl_detail)->result();
        showLast_query("kuning");
//        cekKuning(count($query));
//        mati_disini(__LINE__);
        //---------------------------------
        $detailData = array();
        $trIDs = array();
        foreach ($queryDetail as $spec) {
            $trid = $spec->transaksi_id;
            if ($trid > 0) {
                $trIDs[$trid] = $trid;
            }
            if (!isset($detailData[$trid]["debet"])) {
                $detailData[$trid]["debet"] = 0;
            }
            if (!isset($detailData[$trid]["kredit"])) {
                $detailData[$trid]["kredit"] = 0;
            }
            $detailData[$trid]["debet"] += $spec->debet;
            $detailData[$trid]["kredit"] += $spec->kredit;
        }

        $this->db->where($where_master);
        $this->db->where_in("transaksi_id", $trIDs);
        $queryMaster = $this->db->get($tbl_master)->result();
//        showLast_query("biru");
//        cekBiru(count($query));
        //---------------------------------


        $this->db->trans_start();


        $masterData = array();
        foreach ($queryMaster as $spec) {
            $trid = $spec->transaksi_id;
            $trno = $spec->transaksi_no;
            $debet = $spec->debet;
            $kredit = $spec->kredit;
            $dtime = $spec->dtime;
            $fulldate = $spec->fulldate;
            $debet_detail = isset($detailData[$trid]["debet"]) ? $detailData[$trid]["debet"] : 0;
            $kredit_detail = isset($detailData[$trid]["kredit"]) ? $detailData[$trid]["kredit"] : 0;

            $masterData[$trid]["trid"] = $trid;
            $masterData[$trid]["trno"] = $trno;
            $masterData[$trid]["debet_detail"] = $debet_detail;
            $masterData[$trid]["kredit_detail"] = $kredit_detail;
            $masterData[$trid]["dtime"] = $dtime;
            $masterData[$trid]["fulldate"] = $fulldate;
            if (!isset($masterData[$trid]["master_debet"])) {
                $masterData[$trid]["master_debet"] = 0;
            }
            if (!isset($masterData[$trid]["master_kredit"])) {
                $masterData[$trid]["master_kredit"] = 0;
            }
            $masterData[$trid]["master_debet"] += $debet;
            $masterData[$trid]["master_kredit"] += $kredit;
//            break;
        }
//        arrPrintCyan($masterData[372490]);

        $str = "<table rules='all' width='100%' style='border:1px solid black;'>";
        $str .= "<tr style='text-align:center;font-size:15px;font-weight:bold;'>";
        $str .= "<td>No.</td>";
        $str .= "<td>tgl</td>";
        $str .= "<td>trid</td>";
        $str .= "<td>nomer</td>";
        $str .= "<td style='background-color:yellow;'>m debet</td>";
        $str .= "<td style='background-color:#5cb730;'>m kredit</td>";
        $str .= "<td style='background-color:yellow;'>d debet</td>";
        $str .= "<td style='background-color:#5cb730;'>d kredit</td>";
        $str .= "</tr>";
        $no = 0;
        $master_debet_total = 0;
        $master_kredit_total = 0;
        $debet_detail_total = 0;
        $kredit_detail_total = 0;
        foreach ($masterData as $trid => $tridspec) {
            $fulldate = $tridspec["fulldate"];
            $nomer = $tridspec["trno"];
            $master_debet = $tridspec["master_debet"];
            $master_kredit = $tridspec["master_kredit"];
            $debet_detail = $tridspec["debet_detail"];
            $kredit_detail = $tridspec["kredit_detail"];
            // persediaan
//            $selisih_debet_cek = $master_debet - $debet_detail;
//            $selisih_kredit_cek = $master_kredit - $kredit_detail;
            // HPP
            $selisih_debet_cek = $master_kredit - $debet_detail;
            $selisih_kredit_cek = $master_debet - $kredit_detail;
            // Hutang ke pusat
//            $selisih_debet_cek = $master_kredit - $debet_detail;
//            $selisih_kredit_cek = $master_debet - $kredit_detail;
            // PIUTANG CABANG
//            $selisih_debet_cek = $master_kredit - $debet_detail;
//            $selisih_kredit_cek = $master_debet - $kredit_detail;

            $selisih_debet_cek = ($selisih_debet_cek < 0) ? ($selisih_debet_cek * -1) : $selisih_debet_cek;
            $selisih_kredit_cek = ($selisih_kredit_cek < 0) ? ($selisih_kredit_cek * -1) : $selisih_kredit_cek;
//            cekUngu("[$selisih_debet_cek] [$selisih_kredit_cek]");
            if (($selisih_debet_cek > 100) || ($selisih_kredit_cek > 100)) {
                $master_debet_total += $master_debet;
                $master_kredit_total += $master_kredit;
                $debet_detail_total += $debet_detail;
                $kredit_detail_total += $kredit_detail;
                $master_debet_f = number_format($master_debet);
                $master_kredit_f = number_format($master_kredit);
                $debet_detail_f = number_format($debet_detail);
                $kredit_detail_f = number_format($kredit_detail);
                $no++;
                $str .= "<tr>";
                $str .= "<td>$no</td>";
                $str .= "<td>$fulldate</td>";
                $str .= "<td>$trid</td>";
                $str .= "<td>$nomer</td>";
                $str .= "<td style='background-color:yellow;text-align:right;'>$master_debet_f</td>";
                $str .= "<td style='background-color:#5cb730;text-align:right;'>$master_kredit_f</td>";
                $str .= "<td style='background-color:yellow;text-align:right;'>$debet_detail_f</td>";
                $str .= "<td style='background-color:#5cb730;text-align:right;'>$kredit_detail_f</td>";
                $str .= "</tr>";

                //update tabel master persediaan----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMaster = $this->db->get($tbl_master)->result();
                    showLast_query("kuning");
                    if (sizeof($queryMaster) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master);
                        showLast_query("orange");

                        $where_update = array(
                            "id" => $queryMaster[0]->id,
                        );
                        $this->db->set('debet', $debet_detail);
                        $this->db->set('kredit', $kredit_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master);
                        showLast_query("orange");
                    }
                }
                //----------------------------
                //update tabel master hpp----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMasterHpp = $this->db->get($tbl_master_hpp)->result();
                    showLast_query("hitam");
                    if (sizeof($queryMasterHpp) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master_hpp);
                        showLast_query("hitam");

                        $where_update = array(
                            "id" => $queryMasterHpp[0]->id,
                        );
                        $this->db->set('debet', $kredit_detail);
                        $this->db->set('kredit', $debet_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master_hpp);
                        showLast_query("hitam");
                    }
                }
                //----------------------------
                //update tabel master hutang ke pusat----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMasterHkp = $this->db->get($tbl_master_hkp)->result();
                    showLast_query("hitam");
                    if (sizeof($queryMasterHkp) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master_hkp);
                        showLast_query("hitam");

                        $where_update = array(
                            "id" => $queryMasterHkp[0]->id,
                        );
                        $this->db->set('debet', $kredit_detail);
                        $this->db->set('kredit', $debet_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master_hkp);
                        showLast_query("hitam");
                    }
                }
                //----------------------------
                //update tabel master piutang cabang----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMasterPiutang = $this->db->get($tbl_master_piutang)->result();
                    showLast_query("hitam");
                    if (sizeof($queryMasterPiutang) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master_piutang);
                        showLast_query("hitam");

                        $where_update = array(
                            "id" => $queryMasterPiutang[0]->id,
                        );
                        $this->db->set('debet', $kredit_detail);
                        $this->db->set('kredit', $debet_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master_piutang);
                        showLast_query("hitam");
                    }
                }
                //----------------------------
            }
        }
        $selisih_debet = $master_debet_total - $debet_detail_total;
        $selisih_kredit = $master_kredit_total - $kredit_detail_total;
        $master_debet_total_f = number_format($master_debet_total);
        $master_kredit_total_f = number_format($master_kredit_total);
        $debet_detail_total_f = number_format($debet_detail_total);
        $kredit_detail_total_f = number_format($kredit_detail_total);
        $selisih_debet_f = number_format($selisih_debet);
        $selisih_kredit_f = number_format($selisih_kredit);
        $str .= "<tr style='text-align:center;font-size:15px;font-weight:bold;'>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td style='background-color:yellow;text-align:right;'>$master_debet_total_f</td>";
        $str .= "<td style='background-color:#5cb730;text-align:right;'>$master_kredit_total_f</td>";
        $str .= "<td style='background-color:yellow;text-align:right;'>$debet_detail_total_f</td>";
        $str .= "<td style='background-color:#5cb730;text-align:right;'>$kredit_detail_total_f</td>";
        $str .= "</tr>";
        $str .= "</table>";
        $str .= "<div >";
        $str .= "SELISIH DEBET: $selisih_debet_f";
        $str .= "<br>SELISIH KREDIT: $selisih_kredit_f";
        $str .= "</div>";
        echo $str;


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    public function patchMasterValuePiutangSupplier()
    {
        $tbl_master_piutang = "__rek_master__1010060010";
        $tbl_master_hkp = "__rek_master__2040010";
        $tbl_master_hpp = "__rek_master__5010";
//        $tbl_master = "__rek_master__1010030030";
//        $tbl_master = "__rek_master__2040010";
        $tbl_master = "__rek_master__1010020030";
//        $tbl_master = "__rek_master__5010";
//        $tbl_master = "__rek_master__1010060010";
        $tbl_detail = "__rek_pembantu_produk__1010030030";

        $cabang_id = "-1";
//        $cabang_id = "1";
//        $tahun = "2024";
//        $tahun = "2025";


        $where = array(
            "cabang_id" => $cabang_id,
//            "year(dtime)" => $tahun,
//            "transaksi_id" => 49290,
            "transaksi_id>" => 5,
            "gudang_id<>" => 0,
            "jenis" => "3333",
        );
        $where_master = array(
            "cabang_id" => $cabang_id,
//            "year(dtime)" => $tahun,
        );
        //---------------------------------
        $this->db->where($where);
        $queryDetail = $this->db->get($tbl_detail)->result();
        showLast_query("kuning");
//        cekKuning(count($query));
//        mati_disini(__LINE__);
        //---------------------------------
        $detailData = array();
        $trIDs = array();
        foreach ($queryDetail as $spec) {
            $trid = $spec->transaksi_id;
            if ($trid > 0) {
                $trIDs[$trid] = $trid;
            }
            if (!isset($detailData[$trid]["debet"])) {
                $detailData[$trid]["debet"] = 0;
            }
            if (!isset($detailData[$trid]["kredit"])) {
                $detailData[$trid]["kredit"] = 0;
            }
            $detailData[$trid]["debet"] += $spec->debet;
            $detailData[$trid]["kredit"] += $spec->kredit;
        }

        $this->db->where($where_master);
        $this->db->where_in("transaksi_id", $trIDs);
        $queryMaster = $this->db->get($tbl_master)->result();
//        showLast_query("biru");
//        cekBiru(count($query));
        //---------------------------------


        $this->db->trans_start();


        $masterData = array();
        foreach ($queryMaster as $spec) {
            $trid = $spec->transaksi_id;
            $trno = $spec->transaksi_no;
            $debet = $spec->debet;
            $kredit = $spec->kredit;
            $dtime = $spec->dtime;
            $fulldate = $spec->fulldate;
            $debet_detail = isset($detailData[$trid]["debet"]) ? $detailData[$trid]["debet"] : 0;
            $kredit_detail = isset($detailData[$trid]["kredit"]) ? $detailData[$trid]["kredit"] : 0;

            $masterData[$trid]["trid"] = $trid;
            $masterData[$trid]["trno"] = $trno;
            $masterData[$trid]["debet_detail"] = $debet_detail;
            $masterData[$trid]["kredit_detail"] = $kredit_detail;
            $masterData[$trid]["dtime"] = $dtime;
            $masterData[$trid]["fulldate"] = $fulldate;
            if (!isset($masterData[$trid]["master_debet"])) {
                $masterData[$trid]["master_debet"] = 0;
            }
            if (!isset($masterData[$trid]["master_kredit"])) {
                $masterData[$trid]["master_kredit"] = 0;
            }
            $masterData[$trid]["master_debet"] += $debet;
            $masterData[$trid]["master_kredit"] += $kredit;
//            break;
        }
//        arrPrintCyan($masterData[372490]);

        $str = "<table rules='all' width='100%' style='border:1px solid black;'>";
        $str .= "<tr style='text-align:center;font-size:15px;font-weight:bold;'>";
        $str .= "<td>No.</td>";
        $str .= "<td>tgl</td>";
        $str .= "<td>trid</td>";
        $str .= "<td>nomer</td>";
        $str .= "<td style='background-color:yellow;'>m debet</td>";
        $str .= "<td style='background-color:#5cb730;'>m kredit</td>";
        $str .= "<td style='background-color:yellow;'>d debet</td>";
        $str .= "<td style='background-color:#5cb730;'>d kredit</td>";
        $str .= "</tr>";
        $no = 0;
        $master_debet_total = 0;
        $master_kredit_total = 0;
        $debet_detail_total = 0;
        $kredit_detail_total = 0;
        foreach ($masterData as $trid => $tridspec) {
            $fulldate = $tridspec["fulldate"];
            $nomer = $tridspec["trno"];
            $master_debet = $tridspec["master_debet"];
            $master_kredit = $tridspec["master_kredit"];
            $debet_detail = $tridspec["debet_detail"];
            $kredit_detail = $tridspec["kredit_detail"];
            // persediaan
//            $selisih_debet_cek = $master_debet - $debet_detail;
//            $selisih_kredit_cek = $master_kredit - $kredit_detail;
            // HPP
            $selisih_debet_cek = $master_kredit - $debet_detail;
            $selisih_kredit_cek = $master_debet - $kredit_detail;
            // Hutang ke pusat
//            $selisih_debet_cek = $master_kredit - $debet_detail;
//            $selisih_kredit_cek = $master_debet - $kredit_detail;
            // PIUTANG CABANG
//            $selisih_debet_cek = $master_kredit - $debet_detail;
//            $selisih_kredit_cek = $master_debet - $kredit_detail;

            $selisih_debet_cek = ($selisih_debet_cek < 0) ? ($selisih_debet_cek * -1) : $selisih_debet_cek;
            $selisih_kredit_cek = ($selisih_kredit_cek < 0) ? ($selisih_kredit_cek * -1) : $selisih_kredit_cek;
//            cekUngu("[$selisih_debet_cek] [$selisih_kredit_cek]");
            if (($selisih_debet_cek > 100) || ($selisih_kredit_cek > 100)) {
                $master_debet_total += $master_debet;
                $master_kredit_total += $master_kredit;
                $debet_detail_total += $debet_detail;
                $kredit_detail_total += $kredit_detail;
                $master_debet_f = number_format($master_debet);
                $master_kredit_f = number_format($master_kredit);
                $debet_detail_f = number_format($debet_detail);
                $kredit_detail_f = number_format($kredit_detail);
                $no++;
                $str .= "<tr>";
                $str .= "<td>$no</td>";
                $str .= "<td>$fulldate</td>";
                $str .= "<td>$trid</td>";
                $str .= "<td>$nomer</td>";
                $str .= "<td style='background-color:yellow;text-align:right;'>$master_debet_f</td>";
                $str .= "<td style='background-color:#5cb730;text-align:right;'>$master_kredit_f</td>";
                $str .= "<td style='background-color:yellow;text-align:right;'>$debet_detail_f</td>";
                $str .= "<td style='background-color:#5cb730;text-align:right;'>$kredit_detail_f</td>";
                $str .= "</tr>";

                //update tabel master persediaan----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMaster = $this->db->get($tbl_master)->result();
                    showLast_query("kuning");
                    if (sizeof($queryMaster) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master);
                        showLast_query("orange");

                        $where_update = array(
                            "id" => $queryMaster[0]->id,
                        );
                        $this->db->set('debet', $debet_detail);
                        $this->db->set('kredit', $kredit_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master);
                        showLast_query("orange");
                    }
                }
                //----------------------------
                //update tabel master hpp----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMasterHpp = $this->db->get($tbl_master_hpp)->result();
                    showLast_query("hitam");
                    if (sizeof($queryMasterHpp) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master_hpp);
                        showLast_query("hitam");

                        $where_update = array(
                            "id" => $queryMasterHpp[0]->id,
                        );
                        $this->db->set('debet', $kredit_detail);
                        $this->db->set('kredit', $debet_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master_hpp);
                        showLast_query("hitam");
                    }
                }
                //----------------------------
                //update tabel master hutang ke pusat----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMasterHkp = $this->db->get($tbl_master_hkp)->result();
                    showLast_query("hitam");
                    if (sizeof($queryMasterHkp) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master_hkp);
                        showLast_query("hitam");

                        $where_update = array(
                            "id" => $queryMasterHkp[0]->id,
                        );
                        $this->db->set('debet', $kredit_detail);
                        $this->db->set('kredit', $debet_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master_hkp);
                        showLast_query("hitam");
                    }
                }
                //----------------------------
                //update tabel master piutang cabang----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMasterPiutang = $this->db->get($tbl_master_piutang)->result();
                    showLast_query("hitam");
                    if (sizeof($queryMasterPiutang) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master_piutang);
                        showLast_query("hitam");

                        $where_update = array(
                            "id" => $queryMasterPiutang[0]->id,
                        );
                        $this->db->set('debet', $kredit_detail);
                        $this->db->set('kredit', $debet_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master_piutang);
                        showLast_query("hitam");
                    }
                }
                //----------------------------
            }
        }
        $selisih_debet = $master_debet_total - $debet_detail_total;
        $selisih_kredit = $master_kredit_total - $kredit_detail_total;
        $master_debet_total_f = number_format($master_debet_total);
        $master_kredit_total_f = number_format($master_kredit_total);
        $debet_detail_total_f = number_format($debet_detail_total);
        $kredit_detail_total_f = number_format($kredit_detail_total);
        $selisih_debet_f = number_format($selisih_debet);
        $selisih_kredit_f = number_format($selisih_kredit);
        $str .= "<tr style='text-align:center;font-size:15px;font-weight:bold;'>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td style='background-color:yellow;text-align:right;'>$master_debet_total_f</td>";
        $str .= "<td style='background-color:#5cb730;text-align:right;'>$master_kredit_total_f</td>";
        $str .= "<td style='background-color:yellow;text-align:right;'>$debet_detail_total_f</td>";
        $str .= "<td style='background-color:#5cb730;text-align:right;'>$kredit_detail_total_f</td>";
        $str .= "</tr>";
        $str .= "</table>";
        $str .= "<div >";
        $str .= "SELISIH DEBET: $selisih_debet_f";
        $str .= "<br>SELISIH KREDIT: $selisih_kredit_f";
        $str .= "</div>";
        echo $str;


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    // master cek ke detail
    public function patchMasterValue2()
    {
        $this->load->model("MdlTransaksi");
        $tr = New MdlTransaksi();

        $tbl_master_piutang = "__rek_master__1010060010";
        $tbl_master_hkp = "__rek_master__2040010";
        $tbl_master_hpp = "__rek_master__5010";
//        $tbl_master = "__rek_master__1010030030";// persediaan
//        $tbl_master = "__rek_master__2040010";// hutang ke pusat
//        $tbl_master = "__rek_master__5010";// hpp
        $tbl_master = "__rek_master__1010060010";// piutang cabang
        $tbl_detail = "__rek_pembantu_produk__1010030030";
        $jenis = "585";

        $cabang_id = isset($_GET["w"]) ? $_GET["w"] : "-1";
//        $cabang_id = "1";
//        $tahun = "2024";
//        $tahun = "2025";

        //---------------------------------
        $trID_trans = array();
        $tr->addFilter("jenis='$jenis'");
//        $tr->addFilter("cabang_id='$cabang_id'");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $trID_trans[$trSpec->id] = $trSpec->id;
            }
        }
        //---------------------------------


        $where = array(
            "cabang_id" => $cabang_id,
//            "year(dtime)" => $tahun,
//            "transaksi_id" => 49290,
//            "transaksi_id>" => 5,
            "gudang_id<>" => 0,
//            "jenis" => "467",
        );
        $where_master = array(
            "cabang_id" => $cabang_id,
//            "year(dtime)" => $tahun,
            "jenis" => "$jenis",
        );
        //---------------------------------
        $this->db->where($where_master);
        $this->db->where_in("transaksi_id", $trID_trans);
        $queryMaster = $this->db->get($tbl_master)->result();
//        showLast_query("hitam");
//        cekBiru(count($query));
        $trIDs = array();
        foreach ($queryMaster as $spec) {
            $trid = $spec->transaksi_id;
            $trIDs[$trid] = $trid;
//            if ($trid > 0) {
//            }
        }
        //---------------------------------


        $this->db->where($where);
        $this->db->where_in("transaksi_id", $trID_trans);
        $queryDetail = $this->db->get($tbl_detail)->result();
//        showLast_query("kuning");
//        cekKuning(count($query));
//        mati_disini(__LINE__);
        //---------------------------------
        $detailData = array();
//        $trIDs = array();
        foreach ($queryDetail as $spec) {
            $trid = $spec->transaksi_id;
//            if ($trid > 0) {
//                $trIDs[$trid] = $trid;
//            }
//
            if (!isset($detailData[$trid]["debet"])) {
                $detailData[$trid]["debet"] = 0;
            }
            if (!isset($detailData[$trid]["kredit"])) {
                $detailData[$trid]["kredit"] = 0;
            }
            $detailData[$trid]["debet"] += $spec->debet;
            $detailData[$trid]["kredit"] += $spec->kredit;
        }


        $this->db->trans_start();


        $masterData = array();
        foreach ($queryMaster as $spec) {
            $trid = $spec->transaksi_id;
            $trno = $spec->transaksi_no;
            $debet = $spec->debet;
            $kredit = $spec->kredit;
            $dtime = $spec->dtime;
            $fulldate = $spec->fulldate;
            $debet_detail = isset($detailData[$trid]["debet"]) ? $detailData[$trid]["debet"] : 0;
            $kredit_detail = isset($detailData[$trid]["kredit"]) ? $detailData[$trid]["kredit"] : 0;

            $masterData[$trid]["trid"] = $trid;
            $masterData[$trid]["trno"] = $trno;
            $masterData[$trid]["debet_detail"] = $debet_detail;
            $masterData[$trid]["kredit_detail"] = $kredit_detail;
            $masterData[$trid]["dtime"] = $dtime;
            $masterData[$trid]["fulldate"] = $fulldate;
            if (!isset($masterData[$trid]["master_debet"])) {
                $masterData[$trid]["master_debet"] = 0;
            }
            if (!isset($masterData[$trid]["master_kredit"])) {
                $masterData[$trid]["master_kredit"] = 0;
            }
            $masterData[$trid]["master_debet"] += $debet;
            $masterData[$trid]["master_kredit"] += $kredit;
//            break;
        }
//        arrPrintCyan($masterData[372490]);

        $str = "<table rules='all' width='100%' style='border:1px solid black;'>";
        $str .= "<tr style='text-align:center;font-size:15px;font-weight:bold;'>";
        $str .= "<td>No.</td>";
        $str .= "<td>tgl</td>";
        $str .= "<td>trid</td>";
        $str .= "<td>nomer</td>";
        $str .= "<td style='background-color:yellow;'>m debet</td>";
        $str .= "<td style='background-color:#5cb730;'>m kredit</td>";
        $str .= "<td style='background-color:yellow;'>d debet</td>";
        $str .= "<td style='background-color:#5cb730;'>d kredit</td>";
        $str .= "</tr>";
        $no = 0;
        $master_debet_total = 0;
        $master_kredit_total = 0;
        $debet_detail_total = 0;
        $kredit_detail_total = 0;
        foreach ($trID_trans as $trid => $xxx) {
//        foreach ($masterData as $trid => $tridspec) {
            $tridspec = $masterData[$trid];
            $fulldate = $tridspec["fulldate"];
            $nomer = $tridspec["trno"];
            $master_debet = $tridspec["master_debet"];
            $master_kredit = $tridspec["master_kredit"];
            $debet_detail = $tridspec["debet_detail"];
            $kredit_detail = $tridspec["kredit_detail"];
            $netto_master = $master_debet - $master_kredit;
            $netto_detail = $debet_detail - $kredit_detail;
            // persediaan
//            $selisih_debet_cek = $master_debet - $debet_detail;
//            $selisih_kredit_cek = $debet_detail - $kredit_detail;
//            $selisih_netto_cek = $netto_master - $netto_detail;
            // HPP
//            $selisih_debet_cek = $master_kredit - $debet_detail;
//            $selisih_kredit_cek = $master_debet - $kredit_detail;
            // Hutang ke pusat
//            $selisih_debet_cek = $master_kredit - $debet_detail;
//            $selisih_kredit_cek = $master_debet - $kredit_detail;
            // PIUTANG CABANG
            $selisih_debet_cek = $master_kredit - $debet_detail;
            $selisih_kredit_cek = $master_debet - $kredit_detail;

            $selisih_debet_cek = ($selisih_debet_cek < 0) ? ($selisih_debet_cek * -1) : $selisih_debet_cek;
            $selisih_kredit_cek = ($selisih_kredit_cek < 0) ? ($selisih_kredit_cek * -1) : $selisih_kredit_cek;
//            $selisih_netto_cek = ($selisih_netto_cek < 0) ? ($selisih_netto_cek * -1) : $selisih_netto_cek;
//            cekUngu("[$selisih_debet_cek] [$selisih_kredit_cek]");
            if (($selisih_debet_cek > 100) || ($selisih_kredit_cek > 100)) {
//            if ($selisih_netto_cek > 100) {
                $master_debet_total += $master_debet;
                $master_kredit_total += $master_kredit;
                $debet_detail_total += $debet_detail;
                $kredit_detail_total += $kredit_detail;
                $master_debet_f = number_format($master_debet);
                $master_kredit_f = number_format($master_kredit);
                $debet_detail_f = number_format($debet_detail);
                $kredit_detail_f = number_format($kredit_detail);
                $no++;
                $str .= "<tr>";
                $str .= "<td>$no</td>";
                $str .= "<td>$fulldate</td>";
                $str .= "<td>$trid</td>";
                $str .= "<td>$nomer</td>";
                $str .= "<td style='background-color:yellow;text-align:right;'>$master_debet_f</td>";
                $str .= "<td style='background-color:#5cb730;text-align:right;'>$master_kredit_f</td>";
                $str .= "<td style='background-color:yellow;text-align:right;'>$debet_detail_f</td>";
                $str .= "<td style='background-color:#5cb730;text-align:right;'>$kredit_detail_f</td>";
                $str .= "</tr>";

                //update tabel master persediaan----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMaster = $this->db->get($tbl_master)->result();
                    showLast_query("kuning");
                    if (sizeof($queryMaster) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master);
                        showLast_query("orange");

                        $where_update = array(
                            "id" => $queryMaster[0]->id,
                        );
                        $this->db->set('debet', $debet_detail);
                        $this->db->set('kredit', $kredit_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master);
                        showLast_query("orange");
                    }
                }
                //----------------------------
                //update tabel master hpp----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMasterHpp = $this->db->get($tbl_master_hpp)->result();
                    showLast_query("hitam");
                    if (sizeof($queryMasterHpp) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master_hpp);
                        showLast_query("hitam");

                        $where_update = array(
                            "id" => $queryMasterHpp[0]->id,
                        );
                        $this->db->set('debet', $kredit_detail);
                        $this->db->set('kredit', $debet_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master_hpp);
                        showLast_query("hitam");
                    }
                }
                //----------------------------
                //update tabel master hutang ke pusat----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMasterHkp = $this->db->get($tbl_master_hkp)->result();
                    showLast_query("hitam");
                    if (sizeof($queryMasterHkp) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master_hkp);
                        showLast_query("hitam");

                        $where_update = array(
                            "id" => $queryMasterHkp[0]->id,
                        );
                        $this->db->set('debet', $kredit_detail);
                        $this->db->set('kredit', $debet_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master_hkp);
                        showLast_query("hitam");
                    }
                }
                //----------------------------
                //update tabel master piutang cabang----------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    $where_cek = array(
                        "transaksi_id" => $trid,
                        "cabang_id" => $cabang_id,
                    );
                    $this->db->where($where_cek);
                    $queryMasterPiutang = $this->db->get($tbl_master_piutang)->result();
                    showLast_query("hitam");
                    if (sizeof($queryMasterPiutang) > 0) {
                        $this->db->set('debet', 0);
                        $this->db->set('kredit', 0);
                        $this->db->where($where_cek);
                        $this->db->update($tbl_master_piutang);
                        showLast_query("hitam");

                        $where_update = array(
                            "id" => $queryMasterPiutang[0]->id,
                        );
                        $this->db->set('debet', $kredit_detail);
                        $this->db->set('kredit', $debet_detail);
                        $this->db->where($where_update);
                        $this->db->update($tbl_master_piutang);
                        showLast_query("hitam");
                    }
                }
                //----------------------------
            }
        }
        $selisih_debet = $master_debet_total - $debet_detail_total;
        $selisih_kredit = $master_kredit_total - $kredit_detail_total;
        $master_debet_total_f = number_format($master_debet_total);
        $master_kredit_total_f = number_format($master_kredit_total);
        $debet_detail_total_f = number_format($debet_detail_total);
        $kredit_detail_total_f = number_format($kredit_detail_total);
        $selisih_debet_f = number_format($selisih_debet);
        $selisih_kredit_f = number_format($selisih_kredit);
        $str .= "<tr style='text-align:center;font-size:15px;font-weight:bold;'>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td style='background-color:yellow;text-align:right;'>$master_debet_total_f</td>";
        $str .= "<td style='background-color:#5cb730;text-align:right;'>$master_kredit_total_f</td>";
        $str .= "<td style='background-color:yellow;text-align:right;'>$debet_detail_total_f</td>";
        $str .= "<td style='background-color:#5cb730;text-align:right;'>$kredit_detail_total_f</td>";
        $str .= "</tr>";
        $str .= "</table>";
        $str .= "<div >";
        $str .= "SELISIH DEBET: $selisih_debet_f";
        $str .= "<br>SELISIH KREDIT: $selisih_kredit_f";
        $str .= "</div>";
        echo $str;

//        arrPrint($trIDs);
//        cekHere("jumlah trid transaksi: " . count($trID_trans));
//        cekBiru("jumlah trid rekening: " . count($trIDs));
//        $arrDiffTrIDs = array_diff($trID_trans, $trIDs);
//        if(sizeof($arrDiffTrIDs)>0){
//            $arrTrDataDetail = array();
//            $this->db->where_in("transaksi_id", $arrDiffTrIDs);
//            $queryData = $this->db->get("transaksi_data")->result();
//            foreach ($queryData as $dataSpec){
//                $arrTrDataDetail[$dataSpec->transaksi_id][] = array(
//                    "produk_id" => $dataSpec->produk_id,
//                    "produk_nama" => $dataSpec->produk_nama,
//                );
//            }
//            arrPrintCyan($arrTrDataDetail);
//        }

        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    public function cek2()
    {
        $this->load->model("MdlTransaksi");
        $tr = New MdlTransaksi();

        $tbl_master_pc = "__rek_master__1010060010";
        $tbl_master_hkp = "__rek_master__2040010";
        $tbl_master_hpp = "__rek_master__5010";
        $tbl_master = "__rek_master__1010030030";// persediaan
        $cabang_id = "1";
        $jenis = "585";
        $arrHkp = array();
        $arrPc = array();
        $arrPersediaan = array();
        $arrHpp = array();

        //HUTANG KE PUSAT---------------------------------
        $where_hkp = array(
//            "cabang_id" => $cabang_id,
            "jenis" => $jenis,
        );
        $this->db->where($where_hkp);
        $queryHkp = $this->db->get($tbl_master_hkp)->result();
        showLast_query("hitam");
        foreach ($queryHkp as $specHkp) {
            $trid = $specHkp->transaksi_id;
            if (!isset($arrHkp[$trid]["debet"])) {
                $arrHkp[$trid]["debet"] = 0;
            }
            if (!isset($arrHkp[$trid]["kredit"])) {
                $arrHkp[$trid]["kredit"] = 0;
            }
            $arrHkp[$trid]["debet"] += $specHkp->debet;
            $arrHkp[$trid]["kredit"] += $specHkp->kredit;
        }

        //PIUTANG CABANG---------------------------------
        $where_pc = array(
//            "cabang_id" => $cabang_id,
            "jenis" => $jenis,
        );
        $this->db->where($where_pc);
        $queryPc = $this->db->get($tbl_master_pc)->result();
        showLast_query("hitam");
        foreach ($queryPc as $specPc) {
            $trid = $specPc->transaksi_id;
            if (!isset($arrPc[$trid]["debet"])) {
                $arrPc[$trid]["debet"] = 0;
            }
            if (!isset($arrPc[$trid]["kredit"])) {
                $arrPc[$trid]["kredit"] = 0;
            }
            $arrPc[$trid]["debet"] += $specPc->debet;
            $arrPc[$trid]["kredit"] += $specPc->kredit;
        }

        //PERSEDIAAN---------------------------------
        $where = array(
//            "cabang_id" => $cabang_id,
            "jenis" => $jenis,
        );
        $this->db->where($where);
        $query = $this->db->get($tbl_master)->result();
        showLast_query("hitam");
        foreach ($query as $spec) {
            $trid = $spec->transaksi_id;
            if (!isset($arrPersediaan[$trid]["debet"])) {
                $arrPersediaan[$trid]["debet"] = 0;
            }
            if (!isset($arrPersediaan[$trid]["kredit"])) {
                $arrPersediaan[$trid]["kredit"] = 0;
            }
            $arrPersediaan[$trid]["debet"] += $spec->debet;
            $arrPersediaan[$trid]["kredit"] += $spec->kredit;
        }

        //HPP---------------------------------
        $where_hpp = array(
//            "cabang_id" => $cabang_id,
            "jenis" => $jenis,
        );
        $this->db->where($where_hpp);
        $queryHpp = $this->db->get($tbl_master_hpp)->result();
        showLast_query("hitam");
        foreach ($queryHpp as $specHpp) {
            $trid = $specHpp->transaksi_id;
            if (!isset($arrHpp[$trid]["debet"])) {
                $arrHpp[$trid]["debet"] = 0;
            }
            if (!isset($arrHpp[$trid]["kredit"])) {
                $arrHpp[$trid]["kredit"] = 0;
            }
            $arrHpp[$trid]["debet"] += $specHpp->debet;
            $arrHpp[$trid]["kredit"] += $specHpp->kredit;
        }

        // PERSEDIAAN vs PIUTANG CABANG
        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            if (sizeof($arrPersediaan) > 0) {
                foreach ($arrPersediaan as $tr => $specPersediaan) {
                    $debetPersediaan = $specPersediaan["debet"];
                    $kreditPersediaan = $specPersediaan["kredit"];
                    $debetPc = isset($arrPc[$tr]["debet"]) ? $arrPc[$tr]["debet"] : 0;
                    $kreditPc = isset($arrPc[$tr]["kredit"]) ? $arrPc[$tr]["kredit"] : 0;
                    // kredit hkp == debet pc
                    $selisih_persediaan_pc = $kreditPersediaan - $debetPc;
                    $selisih_persediaan_pc = ($selisih_persediaan_pc < 0) ? ($selisih_persediaan_pc * -1) : $selisih_persediaan_pc;
                    if ($selisih_persediaan_pc > 10) {
                        cekHere("[trid: [$tr]] [pc debet: $debetPc] [persediaan kredit: $kreditPersediaan]");
                    }
                }
            }
        }

        // PERSEDIAAN vs HUTANG KE PUSAT
        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            if (sizeof($arrPersediaan) > 0) {
                foreach ($arrPersediaan as $tr => $specPersediaan) {
                    $debetPersediaan = $specPersediaan["debet"];
                    $kreditPersediaan = $specPersediaan["kredit"];
                    $debetHkp = isset($arrHkp[$tr]["debet"]) ? $arrHkp[$tr]["debet"] : 0;
                    $kreditHkp = isset($arrHkp[$tr]["kredit"]) ? $arrHkp[$tr]["kredit"] : 0;
                    // kredit hkp == debet pc
                    $selisih_persediaan_hkp = $debetPersediaan - $kreditHkp;
                    $selisih_persediaan_hkp = ($selisih_persediaan_hkp < 0) ? ($selisih_persediaan_hkp * -1) : $selisih_persediaan_hkp;
                    if ($selisih_persediaan_hkp > 10) {
                        cekHere("[trid: [$tr]] [hkp kredit: $kreditHkp] [persediaan debet: $debetPersediaan]");
                    }
//                    else{
//                        cekHijau("[trid: $tr] [selisih_persediaan_hkp: $selisih_persediaan_hkp]");
//                    }
                }
            }
        }

        // HUTANG KE PUSAT vs PIUTANG CABANG
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            if (sizeof($arrHkp) > 0) {
                foreach ($arrHkp as $tr => $specHkp) {
                    $debetHkp = $specHkp["debet"];
                    $kreditHkp = $specHkp["kredit"];
                    $debetPc = isset($arrPc[$tr]["debet"]) ? $arrPc[$tr]["debet"] : 0;
                    $kreditPc = isset($arrPc[$tr]["kredit"]) ? $arrPc[$tr]["kredit"] : 0;
                    // kredit hkp == debet pc
                    $selisih_hkp_pc = $kreditHkp - $debetPc;
                    $selisih_hkp_pc = ($selisih_hkp_pc < 0) ? ($selisih_hkp_pc * -1) : $selisih_hkp_pc;
                    if ($selisih_hkp_pc > 10) {
                        cekHere("[trid: [$tr]] [pc debet: $debetPc] [hkp kredit: $kreditHkp]");
                    }
                }
            }
        }


    }

    public function cekLockerDiskon()
    {
        $this->load->model("Mdls/MdlLockerStockDiskonVendor");
        $this->load->model("Coms/ComRekeningPembantuPiutangSupplierDetailTransItem");

        $jenis = "diskon";
        $state = "active";
        $arrLockerDiskon = array();
        $arrDetailDiskon = array();
        $ld = New MdlLockerStockDiskonVendor();
        $ld->addFilter("jenis='$jenis'");
        $ld->addFilter("state='$state'");
        $ld->addFilter("nilai>0");
        $ldTmp = $ld->lookupAll()->result();
        showLast_query("biru");
        foreach ($ldTmp as $ldSpec) {
            $tr_id = $ldSpec->transaksi_id;
            $extern_id = $ldSpec->extern_id;
            $supplier_id = $ldSpec->supplier_id;
            $nilai = $ldSpec->nilai;
            if (!isset($arrLockerDiskon[$supplier_id][$tr_id][$extern_id])) {
                $arrLockerDiskon[$supplier_id][$tr_id][$extern_id] = 0;
            }
            $arrLockerDiskon[$supplier_id][$tr_id][$extern_id] += $nilai;
        }


        $dd = New ComRekeningPembantuPiutangSupplierDetailTransItem();
        $dd->addFilter("periode='forever'");
        $dd->addFilter("rekening='1010020030'");
        $ddTmp = $dd->lookupAll()->result();
        showLast_query("kuning");
        foreach ($ddTmp as $ddSpec) {
            $tr_id = $ddSpec->extern_id;
            $extern_id = $ddSpec->extern2_id;
            $supplier_id = $ddSpec->extern3_id;
            $nilai = $ddSpec->debet;
            if (!isset($arrDetailDiskon[$supplier_id][$tr_id][$extern_id])) {
                $arrDetailDiskon[$supplier_id][$tr_id][$extern_id] = 0;
            }
            $arrDetailDiskon[$supplier_id][$tr_id][$extern_id] += $nilai;
        }


        $this->db->trans_start();


        $no = 0;
        foreach ($arrLockerDiskon as $sup_id => $supSpec) {
            foreach ($supSpec as $trid => $trSpec) {
                foreach ($trSpec as $extid => $nilai_locker) {
                    $nilai_rek = isset($arrDetailDiskon[$sup_id][$trid][$extid]) ? $arrDetailDiskon[$sup_id][$trid][$extid] : 0;
                    $selisih = $nilai_locker - $nilai_rek;
                    if ($selisih > 100) {
                        $no++;
                        cekMerah("[$no] [supplierID: $sup_id] [transaksiID: $trid] [diskonID: $extid] [locker: $nilai_locker] [rek: $nilai_rek]");
                        $where = array(
                            "jenis" => $jenis,
                            "state" => $state,
                            "supplier_id" => $sup_id,
                            "transaksi_id" => $trid,
                            "extern_id" => $extid,
                        );
                        $data = array(
                            "nilai" => 0,
                        );
                        $ld = New MdlLockerStockDiskonVendor();
                        $ld->setFilters(array());
                        $ld->updateData($where, $data);
                        showLast_query("orange");
                    }
                }
            }
        }


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    public function cekSerial()
    {
        $tbl = "_rek_pembantu_produk_perserial_cache";
        $cabang_id = "-1";
        $gudang_id = "9";
        $this->db->where('cabang_id', $cabang_id);
        $this->db->where('gudang_id', $gudang_id);
        $query = $this->db->get($tbl)->result();
        showLast_query("biru");
        $arrSerial = array();
        foreach ($query as $spec) {
            $arrSerial[$spec->extern_nama][] = 1;
        }
        foreach ($arrSerial as $serial => $xx) {
            if (count($xx) > 1) {
                arrPrint($xx);
                cekHitam("$serial");
            }
        }
    }

    public function patchRebateSupplier()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlSupplierDiskon");
        $this->load->model("Mdls/MdlLockerStockDiskonVendor");
        $arrDataDiskon = array();
        //region data diskon-----
        $ds = New MdlSupplierDiskon();
        $dsTmp = $ds->lookupAll()->result();
        foreach ($dsTmp as $dsSpec) {
            $arrDiskonData[$dsSpec->id] = $dsSpec->label;
        }
        //endregion-----

        // region jenis 3333
        $arrData = array();
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='3333'");
        $trTmp = $tr->lookupAll()->result();
//        cekHere(count($trTmp));
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $ini_trid = $trSpec->id;
                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->setJointSelectFields("transaksi_id, main");
                $trreg->addFilter("transaksi_id='$ini_trid'");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $main = blobDecode($tmpReg[0]->main);
//                arrPrint($main);
                $pihakMainReferenceJenis = $main["pihakMainReferenceJenis"];
                $pihakMainID = ($main["pihakMainID"] > 0) ? $main["pihakMainID"] : 0;
                $pihakMainName = $main["pihakMainName"];

                if ($pihakMainID > 0) {
                    if ($pihakMainID < 100) {
                        foreach ($main["refIDs"] as $vv) {
                            $pihakMainID = $vv;
                            $pihakMainName = $vv;
                            $pihakMainReferenceJenis = 0;
                        }
                        if ($pihakMainReferenceJenis == NULL) {
                            $tr = New MdlTransaksi();
                            $tr->addFilter("id='$pihakMainID'");
                            $trTmp = $tr->lookupAll()->result();
                            $pihakMainReferenceJenis = $trTmp[0]->jenis;
                        }
                        $arrData[$ini_trid] = array(
                            "reference_id" => $pihakMainID,
                            "reference_nomer" => $pihakMainName,
                            "reference_jenis" => $pihakMainReferenceJenis,
                        );
                    }
                    else {
                        if ($pihakMainReferenceJenis == NULL) {
                            $tr = New MdlTransaksi();
                            $tr->addFilter("id='$pihakMainID'");
                            $trTmp = $tr->lookupAll()->result();
                            $pihakMainReferenceJenis = $trTmp[0]->jenis;
                        }
                        $arrData[$ini_trid] = array(
                            "reference_id" => $pihakMainID,
                            "reference_nomer" => $pihakMainName,
                            "reference_jenis" => $pihakMainReferenceJenis,
                        );
                    }
                }
            }

        }
        // endregion jenis 3333

        // region locker diskon
        $arrTrID_locker = array();
        $arrTrData_locker = array();
        $arrTrData_locker_total = array();
        $ld = New MdlLockerStockDiskonVendor();
        $ld->addFilter("nilai>'0'");
        $ldTmp = $ld->lookupAll()->result();
        if (sizeof($ldTmp) > 0) {
            foreach ($ldTmp as $ii => $ldSpec) {
                $arrTrID_locker[$ldSpec->transaksi_id] = $ldSpec->transaksi_id;
                $arrTrData_locker[$ldSpec->transaksi_id][$ldSpec->extern2_id][$ldSpec->extern_id] = array(
                    "supplier_id" => $ldSpec->supplier_id,
                    "supplier_nama" => $ldSpec->supplier_nama,
                    "produk_id" => $ldSpec->produk_id,
                    "produk_nama" => $ldSpec->produk_nama,
                    "extern_id" => $ldSpec->extern_id,
                    "extern_nama" => $ldSpec->extern_nama,
                    "extern2_id" => $ldSpec->extern2_id,
                    "extern2_nama" => $ldSpec->extern2_nama,
                    "nilai" => $ldSpec->nilai,
                    "nilai_unit" => $ldSpec->nilai_unit,
                    "jumlah" => $ldSpec->jumlah,
                    "id_tbl" => $ldSpec->id,
                    "nomer" => $ldSpec->nomer,
                );

            }
        }
        // endregion locker diskon

        $trreg = New MdlTransaksi();
        $trreg->setFilters(array());
        $trreg->setJointSelectFields("transaksi_id, main, items");
        $trreg->addFilter("transaksi_id in ('" . implode("','", $arrTrID_locker) . "')");
        $trregTmp = $trreg->lookupDataRegistries()->result();
//        showLast_query("biru");
//        arrPrint($trregTmp);
        if (sizeof($trregTmp) > 0) {
            foreach ($trregTmp as $regSpec) {
                $trid = $regSpec->transaksi_id;
                $main = blobDecode($regSpec->main);
                $items = blobDecode($regSpec->items);
//                arrPrint($main);
//                cekHere("[$trid]");
                $jenisTr = $main["jenisTr"];
                if ($jenisTr == NULL) {
                    $tr = New MdlTransaksi();
                    $tr->addFilter("id='$trid'");
                    $trTmp = $tr->lookupAll()->result();
                    $jenisTr = $trTmp[0]->jenis;
                }
                switch ($jenisTr) {
                    case "3344":
//                        foreach ($items as $pid => $pSpec){
////                            $arrDataDiskon[$trid] = "";
//                        }
//
                        break;
                    case "4643":
                        break;
                    case "467":
                        foreach ($items as $pid => $pSpec) {
                            foreach ($arrDiskonData as $diskon_id => $diskon_label) {
                                $new_key_id = "diskon_" . $diskon_id . "_id";
                                $new_key_nama = "diskon_" . $diskon_id . "_nama";
                                $new_key_nilai = "sub_diskon_" . $diskon_id . "_nilai";
                                $new_key_nilai_unit = "diskon_" . $diskon_id . "_nilai";
                                if ($pSpec[$new_key_nilai] > 0) {
                                    $arrDataDiskon[$trid][$pid][$diskon_id] = array(
                                        "supplier_id" => $pSpec["supplierID"],
                                        "supplier_nama" => $pSpec["supplierName"],
                                        "produk_id" => $pSpec[$new_key_id],
                                        "produk_nama" => $pSpec[$new_key_nama],
                                        "extern_id" => $pSpec[$new_key_id],
                                        "extern_nama" => $pSpec[$new_key_nama],
                                        "extern2_id" => $pSpec["id"],
                                        "extern2_nama" => $pSpec["nama"],
                                        "nilai" => $pSpec[$new_key_nilai],
                                        "nilai_unit" => $pSpec[$new_key_nilai_unit],
                                        "jumlah" => $pSpec["qty"],
                                    );
                                }
                            }
                        }
                        break;
                }
            }
        }
//        arrPrintCyan($arrDataDiskon);
//        mati_disini(__LINE__);

        $this->db->trans_start();


        if (sizeof($arrData) > 0) {
            foreach ($arrData as $trid => $data) {
                $where = array(
                    "id" => $trid,
                );
                $tr = New MdlTransaksi();
                $tr->setFilters(array());
                $tr->updateData($where, $data);
//                showLast_query("orange");
            }
        }

        if (sizeof($arrDataDiskon) > 0) {
            foreach ($arrDataDiskon as $trid => $tSpec) {
                foreach ($tSpec as $pid => $pSpec) {
                    foreach ($pSpec as $dkid => $dSpec) {
                        $diskon_nilai = $dSpec["nilai"];
                        $diskon_nilai_unit = $dSpec["nilai_unit"];
                        $diskon_nilai_jml = $dSpec["jumlah"];
//                        $diskon_nilai_locker = isset($arrTrData_locker[$trid][$pid][$dkid]["nilai"]) ? $arrTrData_locker[$trid][$pid][$dkid]["nilai"] : 0;
                        $diskon_nilai_locker_unit = isset($arrTrData_locker[$trid][$pid][$dkid]["nilai_unit"]) ? $arrTrData_locker[$trid][$pid][$dkid]["nilai_unit"] : 0;
                        $diskon_nilai_locker_jml = isset($arrTrData_locker[$trid][$pid][$dkid]["jumlah"]) ? $arrTrData_locker[$trid][$pid][$dkid]["jumlah"] : 0;
                        $diskon_nilai_locker = $diskon_nilai_locker_unit * $diskon_nilai_locker_jml;
                        $selisih_locker = $diskon_nilai_locker - $diskon_nilai;
                        if ($selisih_locker > 100) {
                            cekMerah("[diskon_nilai: $diskon_nilai] [diskon_nilai_locker: $diskon_nilai_locker]");
                            $where = array(
                                "id" => $arrTrData_locker[$trid][$pid][$dkid]["id_tbl"],
                            );
                            $data = array(
                                "nilai" => $diskon_nilai,
                                "nilai_unit" => $diskon_nilai,
                            );
                            $ld = New MdlLockerStockDiskonVendor();
                            $ld->setFilters(array());
                            $ld->updateData($where, $data);
                            showLast_query("orange");
                        }
                    }
                }
            }
        }

        if (sizeof($arrTrData_locker) > 0) {
            foreach ($arrTrData_locker as $trid => $tSpec) {
                foreach ($tSpec as $pid => $pSpec) {
                    foreach ($pSpec as $dkid => $dSpec) {
                        $id_tbl = $dSpec["id_tbl"];
                        $nomer = $dSpec["nomer"];
                        $nomer_ex = explode(".", $nomer);
                        switch ($nomer_ex[0]) {
                            case "467":
                                if (isset($arrDataDiskon[$trid])) {
                                    if (!isset($arrDataDiskon[$trid][$pid][$dkid])) {
                                        $where = array(
                                            "id" => $id_tbl,
                                        );
                                        $data = array(
                                            "nilai" => 0,
                                            "nilai_unit" => 0,
                                        );
                                        $ld = New MdlLockerStockDiskonVendor();
                                        $ld->setFilters(array());
                                        $ld->updateData($where, $data);
                                        showLast_query("ungu");
                                    }
                                }
                                break;
                        }

                    }
                }
            }
        }

        mati_disini("---SETOP--- " . __LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>DONE...</h3>");

    }

    public function cekRebateSupplier()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlSupplierDiskon");
        $this->load->model("Mdls/MdlLockerStockDiskonVendor");
        $this->load->model("Coms/ComRekeningPembantuPiutangSupplierDetailTransItem");

        //-----
        $tbl_mutasi = "__rek_pembantu_piutangsupplier__1010020030";
        $supplier_id = "4";
        $date1 = "2024-01-01";
        $date2 = "2026-12-31";
        $arrHeader = array(
            "id" => "trid",
            "dtime" => "dtime",
            "referenceNomer__2" => "nomer po",
            "nomer" => "nomer grn",
            "suppliers_id" => "ID supplier",
            "suppliers_nama" => "supplier",
//            "nilai_diskonpo" => array(
//                "label" => "PO rebate",
//                "format" => "debet",
//            ),
//            "nilai_diskonpo_freeproduk" => array(
//                "label" => "PO rebate<br>(freeproduk)",
//                "format" => "debet",
//            ),
            "nilai" => array(
                "label" => "GRN rebate",
                "format" => "debet",
            ),
            "nilai_freeproduk" => array(
                "label" => "GRN rebate<br>(freeproduk)",
                "format" => "debet",
            ),
//            "nilai_grn_batal" => array(
//                "label" => "GRN rebate<br>(BATAL)",
//                "format" => "debet",
//            ),
            "nilai_piutang" => array(
                "label" => "diklaim",
                "format" => "debet",
            ),
//            "nilai_piutang_batal" => array(
//                "label" => "diklaim<br>(BATAL)",
//                "format" => "debet",
//            ),
            "belum_diklaim" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "belum diklaim",
                "format" => "debet",
            ),
            "rek_realisasi_klaim" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "rek_realisasi_klaim",
                "format" => "debet",
            ),
//            "selisih_plus" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "selisih plus",
//                "format" => "debet",
//            ),
            //-------
//            "nilai_persediaan" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "persediaan",
//                "format" => "debet",
//            ),
//            "nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "credit note",
//                "format" => "debet",
//            ),
//            "nilai_voucher" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "voucher",
//                "format" => "debet",
//            ),
//            "nilai_cash" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "kas",
//                "format" => "debet",
//            ),
//            "nilai_logam_mulia" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "logam mulia",
//                "format" => "debet",
//            ),
//            "nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "pph23<br>dibayar dimuka",
//                "format" => "debet",
//            ),
            //-------
//            "new_nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "credit note<br>REVISI",
//                "format" => "debet",
//            ),
//            "new_nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "pph23<br>dibayar dimuka<br>REVISI",
//                "format" => "debet",
//            ),
            //-------
//            "adj_nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "credit note<br>ADJ",
//                "format" => "debet",
//            ),
//            "adj_nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "pph23<br>dibayar dimuka<br>ADJ",
//                "format" => "debet",
//            ),
        );
        //region data diskon-----
        $ds = New MdlSupplierDiskon();
        $dsTmp = $ds->lookupAll()->result();
        foreach ($dsTmp as $dsSpec) {
            $arrDiskonData[$dsSpec->id] = $dsSpec->label;
        }
        //endregion-----

        // region transaksi grn 467
        $arrTrIDs = array();
        $jenisTr = array(
            "467",
            "4643",
            "3344",
        );
        $date = isset($_GET["date"]) ? $_GET["date"] : "2025-05";
        $date_ex = explode("-", $date);
        $month = isset($date_ex[1]) ? $date_ex[1] : date("m");
        $year = isset($date_ex[0]) ? $date_ex[0] : date("Y");
        $tr = New MdlTransaksi();
//        $tr->addFilter("jenis='$jenisTr'");
        if($supplier_id > 0){
            $tr->addFilter("suppliers_id='$supplier_id'");
        }
        $tr->addFilter("jenis in ('" . implode("','", $jenisTr) . "')");
//        $tr->addFilter("month(dtime)='$month'");
//        $tr->addFilter("year(dtime)='$year'");
        $tr->addFilter("date(dtime)>='$date1'");
        $tr->addFilter("date(dtime)<='$date2'");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $trid = $trSpec->id;// id GRN
                $trash_4 = $trSpec->trash_4;
                $jenis = $trSpec->jenis;
                $idsHis = ($trSpec->ids_his != null) ? blobDecode($trSpec->ids_his) : array();
                if (sizeof($idsHis) > 0) {
                    foreach ($idsHis as $step_his => $data_his) {
                        if ($step_his == 1) {
                            $subCounters = blobDecode($data_his["counters"]);
                            $countStepCode = 0;
                            foreach ($subCounters["stepCode"] as $cc => $cct) {
                                $countStepCode = $cct;
                            }
                            $arrTransaksi[$trid]['referenceID'] = $data_his["trID"];
                            $arrTransaksi[$trid]['referenceNumber'] = $data_his["nomer"];
                            $arrTransaksi[$trid]['referenceNomer'] = $data_his["nomer"];
                            $arrTransaksi[$trid]['referenceDtime'] = $data_his["dtime"];
                            $arrTransaksi[$trid]['referenceFulldate'] = $data_his["fulldate"];
                            $arrTransaksi[$trid]['referenceCount'] = $countStepCode;
                        }
                        $arrTransaksi[$trid]['referenceID__' . $step_his] = $data_his["trID"];
                        $arrTransaksi[$trid]['referenceNumber__' . $step_his] = $data_his["nomer"];
                        $arrTransaksi[$trid]['referenceNomer__' . $step_his] = $data_his["nomer"];
                        $arrTransaksi[$trid]['referenceDtime__' . $step_his] = $data_his["dtime"];
                        $arrTransaksi[$trid]['referenceFulldate__' . $step_his] = $data_his["fulldate"];
                    }
                }

                $arrTrIDs[$trid] = $trid;
                foreach ($arrHeader as $key => $val) {
                    if (!isset($arrTransaksi[$trid][$key])) {
                        $arrTransaksi[$trid][$key] = isset($trSpec->$key) ? $trSpec->$key : "";
                    }
                }
                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->setJointSelectFields("transaksi_id, items, items2_sum, main");
                $trreg->addFilter("transaksi_id='$trid'");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $items = blobDecode($tmpReg[0]->items);
                $items2_sum = blobDecode($tmpReg[0]->items2_sum);
                $main = blobDecode($tmpReg[0]->main);
                switch ($jenis) {
                    case "467":
                        foreach ($items as $pid => $pSpec) {
                            foreach ($arrDiskonData as $diskon_id => $diskon_label) {
                                $new_key_id = "diskon_" . $diskon_id . "_id";
                                $new_key_nilai = "sub_diskon_" . $diskon_id . "_nilai";

                                $arrDataLocker[$trid][$diskon_id]["id"] = isset($pSpec[$new_key_id]) ? $pSpec[$new_key_id] : 0;
                                if (!isset($arrDataLocker[$trid][$diskon_id]["nilai"])) {
                                    $arrDataLocker[$trid][$diskon_id]["nilai"] = 0;
                                }
                                $arrDataLocker[$trid][$diskon_id]["nilai"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;
                                if (!isset($arrDataLockerTotal[$trid]["nilai"])) {
                                    $arrDataLockerTotal[$trid]["nilai"] = 0;
                                }
                                $arrDataLockerTotal[$trid]["nilai"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;

                                if ($trash_4 == 1) {
                                    $arrDataLockerBatal[$trid][$diskon_id]["id"] = isset($pSpec[$new_key_id]) ? $pSpec[$new_key_id] : 0;
                                    if (!isset($arrDataLockerBatal[$trid][$diskon_id]["nilai_grn_batal"])) {
                                        $arrDataLockerBatal[$trid][$diskon_id]["nilai_grn_batal"] = 0;
                                    }
                                    $arrDataLockerBatal[$trid][$diskon_id]["nilai_grn_batal"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;
                                    if (!isset($arrDataLockerTotalBatal[$trid]["nilai_grn_batal"])) {
                                        $arrDataLockerTotalBatal[$trid]["nilai_grn_batal"] = 0;
                                    }
                                    $arrDataLockerTotalBatal[$trid]["nilai_grn_batal"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;
//                            cekMerah("MASUK DISINI... [$trid]");
                                }

                            }

                        }
                        break;
                    case "4643":
                        $arrTransaksi[$trid]["referenceID__2"] = $main["referensi_so"];
                        $arrTransaksi[$trid]["referenceNomer__2"] = $main["referensi_so__nomer"];
                        foreach ($items2_sum as $pid => $pSpec) {
                            foreach ($arrDiskonData as $diskon_id => $diskon_label) {
                                if ($pSpec["diskon_id"] == $diskon_id) {
                                    $new_key_id = "diskon_id";
                                    $new_key_nilai = "sub_diskon_nilai";

                                    $arrDataLocker[$trid][$diskon_id]["id"] = isset($pSpec[$new_key_id]) ? $pSpec[$new_key_id] : 0;
                                    if (!isset($arrDataLocker[$trid][$diskon_id]["nilai"])) {
                                        $arrDataLocker[$trid][$diskon_id]["nilai"] = 0;
                                    }
                                    $arrDataLocker[$trid][$diskon_id]["nilai"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;
                                    if (!isset($arrDataLockerTotal[$trid]["nilai"])) {
                                        $arrDataLockerTotal[$trid]["nilai"] = 0;
                                    }
                                    $arrDataLockerTotal[$trid]["nilai"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;

                                    if ($trash_4 == 1) {
                                        $arrDataLockerBatal[$trid][$diskon_id]["id"] = isset($pSpec[$new_key_id]) ? $pSpec[$new_key_id] : 0;
                                        if (!isset($arrDataLockerBatal[$trid][$diskon_id]["nilai_grn_batal"])) {
                                            $arrDataLockerBatal[$trid][$diskon_id]["nilai_grn_batal"] = 0;
                                        }
                                        $arrDataLockerBatal[$trid][$diskon_id]["nilai_grn_batal"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;
                                        if (!isset($arrDataLockerTotalBatal[$trid]["nilai_grn_batal"])) {
                                            $arrDataLockerTotalBatal[$trid]["nilai_grn_batal"] = 0;
                                        }
                                        $arrDataLockerTotalBatal[$trid]["nilai_grn_batal"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;

                                    }
                                }

                            }

                        }
                        break;
                    case "3344":
                        $arrDataLockerTotal[$trid]["nilai"] = isset($main["nilai_piutang"]) ? $main["nilai_piutang"] : 0;
                        break;
                }


                $arrTransaksi[$trid]["nilai_freeproduk"] = isset($main["produk_rel_harga"]) ? $main["produk_rel_harga"] : 0;
//                break;

                $tridpo = $arrTransaksi[$trid]['referenceID__2'];
                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->setJointSelectFields("transaksi_id, items, items2_sum, main");
                $trreg->addFilter("transaksi_id='$tridpo'");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $mainpo = blobDecode($tmpReg[0]->main);
//                $arrTransaksi[$trid]["nilai_diskonpo"] = isset($mainpo["diskon_nilai"]) ? $mainpo["diskon_nilai"] : 0;
                $arrTransaksi[$trid]["nilai_diskonpo"] = isset($mainpo["diskon_nilai_total"]) ? $mainpo["diskon_nilai_total"] : 0;
                $arrTransaksi[$trid]["nilai_diskonpo_freeproduk"] = isset($mainpo["produk_rel_harga"]) ? $mainpo["produk_rel_harga"] : 0;

            }
        }
        // endregion transaksi grn 467


        // region transaksi klaim 3333
        $arrIniTridKlaim = array();
        $arrIniTridYangDiKlaim = array();
        $arrKlaim = array();
        $arrKlaimBatal = array();
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='3333'");
        if($supplier_id > 0){
            $tr->addFilter("suppliers_id='$supplier_id'");
        }
        $tr->addFilter("reference_id in ('" . implode("','", $arrTrIDs) . "')");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $jenis = $trSpec->jenis;
                $ini_trid = $trSpec->id;
                $trash_4 = $trSpec->trash_4;

                $reference_id = $trSpec->reference_id;
                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->setJointSelectFields("transaksi_id, main");
                $trreg->addFilter("transaksi_id='$ini_trid'");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $main = blobDecode($tmpReg[0]->main);
                $arrKlaim[$reference_id] = array(
                    "nilai_persediaan" => $main["nilai_persediaan"],
                    "nilai_piutang" => $main["nilai_piutang"],
                    "nilai_credit_note" => $main["nilai_credit_note"],
                    "nilai_voucher" => $main["nilai_voucher"],
                    "nilai_cash" => $main["nilai_cash"],
                    "nilai_logam_mulia" => $main["nilai_logam_mulia"],
                    "nilai_pph23" => $main["nilai_pph23"],
                );
                if ($trash_4 == 1) {
                    $arrKlaimBatal[$reference_id] = array(
                        "nilai_persediaan_batal" => $main["nilai_persediaan"],
                        "nilai_piutang_batal" => $main["nilai_piutang"],
                        "nilai_credit_note_batal" => $main["nilai_credit_note"],
                        "nilai_voucher_batal" => $main["nilai_voucher"],
                        "nilai_cash_batal" => $main["nilai_cash"],
                        "nilai_logam_mulia_batal" => $main["nilai_logam_mulia"],
                        "nilai_pph23_batal" => $main["nilai_pph23"],
                    );
                }

                $arrIniTridKlaim[] = $ini_trid;// 3333
                $arrIniTridYangDiKlaim[$reference_id] = $ini_trid;
            }

            if(sizeof($arrIniTridKlaim) > 0){
                $this->db->from($tbl_mutasi);
                $this->db->where_in("transaksi_id", $arrIniTridKlaim);
                $query = $this->db->get()->result();
//                showLast_query("biru");
                foreach ($query as $qspec){
                    if(!isset($realisasiKlaim[$qspec->transaksi_id])){
                        $realisasiKlaim[$qspec->transaksi_id] = 0;
                    }
                    $realisasiKlaim[$qspec->transaksi_id] += $qspec->kredit;
                }
            }
//arrPrint($realisasiKlaim);
        }
        // endregion transaksi klaim 3333


        $this->db->trans_start();

        $arrSupplierCek = array();

        $str = "<table style='border:1px solid black;width:100%;' rules='all'>";

        $str .= "<tr>";
        $str .= "<th>no.</th>";
        foreach ($arrHeader as $key => $val) {
            if (is_array($val)) {
                $str .= "<th>" . $val["label"] . "</th>";
            }
            else {
                $str .= "<th>$val</th>";
            }
        }
        $str .= "</tr>";

        if (sizeof($arrTransaksi) > 0) {
            $no = 0;
            foreach ($arrTransaksi as $trid => $trSpec) {
                $supplier_id = $trSpec["suppliers_id"];
                $supplier_nama = $trSpec["suppliers_nama"];
                $bgcolor = "";

                //----rebate dari items grn
                if (isset($arrDataLockerTotal[$trid])) {
                    foreach ($arrDataLockerTotal[$trid] as $aa => $bb) {
                        $trSpec[$aa] = $bb;
                    }
                }
                //----rebate dari items grn
                if (isset($arrDataLockerTotalBatal[$trid])) {
                    $bgcolor = "#ff66ff";
                    foreach ($arrDataLockerTotalBatal[$trid] as $aa => $bb) {
                        $trSpec[$aa] = $bb;
                    }
                }
                //----rebate klaim
                if (isset($arrKlaim[$trid])) {
                    foreach ($arrKlaim[$trid] as $cc => $dd) {
                        $trSpec[$cc] = $dd;
                    }
                }
                //----rebate klaim
                if (isset($arrKlaimBatal[$trid])) {
                    foreach ($arrKlaimBatal[$trid] as $cc => $dd) {
                        $trSpec[$cc] = $dd;
                    }
                }

                $selisih = $trSpec["nilai_piutang"] - ($trSpec["nilai"] + $trSpec["nilai_freeproduk"]);
                $trSpec["selisih_plus"] = ($selisih > 0) ? $selisih : 0;
                if ($trSpec["selisih_plus"] > 10) {
                    $bgcolor = "#ff3300";
                    //---- nilai klaim seharusnya (creditnote, pph23 dibayar dimuka, voucher, kas, logam mulia)
                    if ($trSpec["nilai_pph23"] > 10) {
                        $new_nilai_pph23 = (15 / 100) * $trSpec["nilai"];
                        $new_nilai_credit_note = (85 / 100) * $trSpec["nilai"];
                        $trSpec["new_nilai_pph23"] = $new_nilai_pph23;
                        $trSpec["new_nilai_credit_note"] = $new_nilai_credit_note;

                        $adj_nilai_pph23 = (15 / 100) * $trSpec["selisih_plus"];
                        $adj_nilai_credit_note = (85 / 100) * $trSpec["selisih_plus"];
                        $trSpec["adj_nilai_pph23"] = $adj_nilai_pph23;
                        $trSpec["adj_nilai_credit_note"] = $adj_nilai_credit_note;
                        //------PER SUPPLIER
                        $arrSupplierCek[$supplier_id]["suppliers_id"] = $supplier_id;
                        $arrSupplierCek[$supplier_id]["suppliers_nama"] = $supplier_nama;
                        if (!isset($arrSupplierCek[$supplier_id]["new_nilai_pph23"])) {
                            $arrSupplierCek[$supplier_id]["new_nilai_pph23"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["new_nilai_pph23"] += $new_nilai_pph23;

                        if (!isset($arrSupplierCek[$supplier_id]["new_nilai_credit_note"])) {
                            $arrSupplierCek[$supplier_id]["new_nilai_credit_note"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["new_nilai_credit_note"] += $new_nilai_credit_note;

                        if (!isset($arrSupplierCek[$supplier_id]["adj_nilai_pph23"])) {
                            $arrSupplierCek[$supplier_id]["adj_nilai_pph23"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["adj_nilai_pph23"] += $adj_nilai_pph23;

                        if (!isset($arrSupplierCek[$supplier_id]["adj_nilai_credit_note"])) {
                            $arrSupplierCek[$supplier_id]["adj_nilai_credit_note"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["adj_nilai_credit_note"] += $adj_nilai_credit_note;
                        //------
                    }
                }

                // belum diklaim belum_diklaim
                $belum_diklaim = ($trSpec["nilai"] + $trSpec["nilai_freeproduk"]) - $trSpec["nilai_piutang"];
                $belum_diklaim = ($belum_diklaim > 0) ? $belum_diklaim : 0;
                $trSpec["belum_diklaim"] = $belum_diklaim;

                // rekening realiasi klaim
                $trid__ = $arrIniTridYangDiKlaim[$trid];
                $trSpec["rek_realisasi_klaim"] = $realisasiKlaim[$trid__];


                $no++;
                $str .= "<tr style='background-color:$bgcolor;'>";
                $str .= "<td>$no</td>";
                foreach ($arrHeader as $key => $val) {
                    $val_data = isset($trSpec[$key]) ? $trSpec[$key] : "";
                    if (is_array($val)) {
                        $val_data_f = formatField_he_format($val["format"], $val_data);
                        $align = "right";
                    }
                    else {
                        $val_data_f = $val_data;
                        $align = "";
                    }
                    $str .= "<td style='text-align:$align;'>";
                    $str .= $val_data_f;
                    $str .= "</td>";

                    if (is_numeric($val_data)) {
                        if (!isset($totalBawah[$key])) {
                            $totalBawah[$key] = 0;
                        }
                        $totalBawah[$key] += $val_data;
                    }
                }
                $str .= "</tr>";
            }
        }

        $str .= "<tr>";
        $str .= "<th>-</th>";
        foreach ($arrHeader as $key => $val) {
            $val_data = isset($totalBawah[$key]) ? $totalBawah[$key] : "";
            if (is_array($val)) {
                $val_data_f = formatField_he_format($val["format"], $val_data);
                $align = "right";
            }
            else {
                $val_data_f = $val_data;
                $align = "";
            }
            $str .= "<th style='text-align:$align;'>";
            $str .= $val_data_f;
            $str .= "</th>";
        }
        $str .= "</tr>";

        $str .= "<tr>";
        $str .= "<th>-</th>";
        foreach ($arrHeader as $key => $val) {
            if (is_array($val)) {
                $val_data_f = $val["label"];
                $align = "right";
            }
            else {
                $val_data_f = $val;
                $align = "";
            }
            $str .= "<th style='text-align:$align;'>";
            $str .= $val_data_f;
            $str .= "</th>";
        }
        $str .= "</tr>";

        $str .= "</table>";
        //-------
        $str .= "<br><br><br>";
        //-------

//        arrPrintCyan($arrSupplierCek);

        echo $str;
        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    public function cekKlaimRebateSupplier()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlSupplierDiskon");
        $this->load->model("Mdls/MdlLockerStockDiskonVendor");
        $this->load->model("Coms/ComRekeningPembantuPiutangSupplierDetailTransItem");
        $this->load->model("Coms/ComJurnal");
        //-----
        $arrTrIDs = array();
        $jenisTr = array(
            "3333",
        );
        $date = isset($_GET["date"]) ? $_GET["date"] : "2025-05";
        $date_ex = explode("-", $date);
        $month = isset($date_ex[1]) ? $date_ex[1] : date("m");
        $year = isset($date_ex[0]) ? $date_ex[0] : date("Y");
        $arrHeader = array(
            "dtime" => "dtime",
            "id" => "trid",
            "nomer" => "nomer<br>klaim",
            "reference_id" => "ID referensi",
            "reference_nomer" => "nomer<br>referensi",
            "suppliers_id" => "ID supplier",
            "suppliers_nama" => "supplier",
//            "nilai_grn_batal" => array(
//                "label" => "rebate<br>(BATAL)",
//                "format" => "debet",
//            ),
            "nilai_piutang" => array(
                "label" => "diklaim",
                "format" => "debet",
            ),
            "nilai_piutang_batal" => array(
                "label" => "diklaim<br>(BATAL)",
                "format" => "debet",
            ),
            //-------
            "nilai_persediaan" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "persediaan",
                "format" => "debet",
            ),
            "nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "credit note",
                "format" => "debet",
            ),
            "nilai_voucher" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "voucher",
                "format" => "debet",
            ),
            "nilai_cash" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "kas",
                "format" => "debet",
            ),
            "nilai_logam_mulia" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "logam<br>mulia",
                "format" => "debet",
            ),
            "nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "pph23<br>dibayar dimuka",
                "format" => "debet",
            ),
            //-------
            "nilai_diskon_reguler" => array(
                "label" => "rebate<br>reguler",
                "format" => "debet",
            ),
            "nilai_freeproduk" => array(
                "label" => "rebate<br>(freeproduk)",
                "format" => "debet",
            ),
            "nilai_rebate_total" => array(
                "label" => "rebate<br>(total)",
                "format" => "debet",
            ),
            "selisih_plus" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "selisih plus<br>(lebih klaim)",
                "format" => "debet",
                "bgcolor" => "yellow",
            ),
//            "new_nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "credit note<br>REVISI",
//                "format" => "debet",
//            ),
//            "new_nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "pph23<br>dibayar dimuka<br>REVISI",
//                "format" => "debet",
//            ),
            //-------
//            "adj_nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "credit note<br>ADJ",
//                "format" => "debet",
//            ),
//            "adj_nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "pph23<br>dibayar dimuka<br>ADJ",
//                "format" => "debet",
//            ),
        );
        $arrHeader2 = array(
//            "dtime" => "dtime",
//            "id" => "trid",
//            "nomer" => "nomer<br>klaim",
//            "reference_id" => "ID referensi",
//            "reference_nomer" => "nomer<br>referensi",
            "suppliers_id" => "ID supplier",
            "suppliers_nama" => "supplier",
            //-------
//            "nilai_piutang" => array(
//                "label" => "diklaim",
//                "format" => "debet",
//            ),
//            "nilai_piutang_batal" => array(
//                "label" => "diklaim<br>(BATAL)",
//                "format" => "debet",
//            ),
//            //-------
//            "nilai_persediaan" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "persediaan",
//                "format" => "debet",
//            ),
//            "nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "credit note",
//                "format" => "debet",
//            ),
//            "nilai_voucher" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "voucher",
//                "format" => "debet",
//            ),
//            "nilai_cash" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "kas",
//                "format" => "debet",
//            ),
//            "nilai_logam_mulia" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "logam<br>mulia",
//                "format" => "debet",
//            ),
//            "nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "pph23<br>dibayar dimuka",
//                "format" => "debet",
//            ),
//            //-------
//            "nilai_diskon_reguler" => array(
//                "label" => "rebate<br>reguler",
//                "format" => "debet",
//            ),
//            "nilai_freeproduk" => array(
//                "label" => "rebate<br>(freeproduk)",
//                "format" => "debet",
//            ),
//            "nilai_rebate_total" => array(
//                "label" => "rebate<br>(total)",
//                "format" => "debet",
//            ),
            "selisih_plus" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "selisih plus<br>(lebih klaim)",
                "format" => "debet",
            ),
            //-------
            "new_nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "credit note<br>REVISI",
                "format" => "debet",
            ),
            "new_nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "pph23<br>dibayar dimuka<br>REVISI",
                "format" => "debet",
            ),
            //-------
            "adj_nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "credit note<br>ADJ",
                "format" => "debet",
            ),
            "adj_nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "pph23<br>dibayar dimuka<br>ADJ",
                "format" => "debet",
            ),
        );

        //region data diskon-----
        $ds = New MdlSupplierDiskon();
        $dsTmp = $ds->lookupAll()->result();
        foreach ($dsTmp as $dsSpec) {
            $arrDiskonData[$dsSpec->id] = $dsSpec->label;
        }
        //endregion-----

        // region transaksi klaim 3333
        $arrKlaim = array();
        $arrKlaimBatal = array();
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='3333'");
//        $tr->addFilter("month(dtime)='$month'");
//        $tr->addFilter("year(dtime)='$year'");
        $tr->addFilter("date(dtime)>='$date'");
//        $tr->addFilter("date(dtime)<='2024-12-31'");
        $tr->addFilter("jenis in ('" . implode("','", $jenisTr) . "')");
//        $tr->addFilter("id='200161'");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $jenis = $trSpec->jenis;
                $ini_trid = $trSpec->id;
                $trash_4 = $trSpec->trash_4;

                $reference_id = $trSpec->reference_id;
                $reference_nomer = $trSpec->reference_nomer;
                $reference_jenis = $trSpec->reference_jenis;
                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->setJointSelectFields("transaksi_id, main");
                $trreg->addFilter("transaksi_id='$ini_trid'");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $main = blobDecode($tmpReg[0]->main);
                $arrKlaim[$ini_trid] = array(
                    "id" => $trSpec->id,
                    "dtime" => $trSpec->dtime,
                    "nomer" => $trSpec->nomer,
                    "suppliers_id" => $trSpec->suppliers_id,
                    "suppliers_nama" => $trSpec->suppliers_nama,
                    "reference_id" => $reference_id,
                    "reference_nomer" => $reference_nomer,

                    "nilai_persediaan" => $main["nilai_persediaan"],
                    "nilai_piutang" => $main["nilai_piutang"],
                    "nilai_credit_note" => $main["nilai_credit_note"],
                    "nilai_voucher" => $main["nilai_voucher"],
                    "nilai_cash" => $main["nilai_cash"],
                    "nilai_logam_mulia" => $main["nilai_logam_mulia"],
                    "nilai_pph23" => $main["nilai_pph23"],
                );
                if ($trash_4 == 1) {
                    $arrKlaimBatal[$ini_trid] = array(
                        "nilai_persediaan_batal" => $main["nilai_persediaan"],
                        "nilai_piutang_batal" => $main["nilai_piutang"],
                        "nilai_credit_note_batal" => $main["nilai_credit_note"],
                        "nilai_voucher_batal" => $main["nilai_voucher"],
                        "nilai_cash_batal" => $main["nilai_cash"],
                        "nilai_logam_mulia_batal" => $main["nilai_logam_mulia"],
                        "nilai_pph23_batal" => $main["nilai_pph23"],
                    );
                }

                $trregref = New MdlTransaksi();
                $trregref->setFilters(array());
                $trregref->setJointSelectFields("transaksi_id, main, items");
                $trregref->addFilter("transaksi_id='$reference_id'");
                $tmpRegRef = $trregref->lookupDataRegistries()->result();
                switch ($reference_jenis) {
                    case "467":
                        $main = blobDecode($tmpRegRef[0]->main);
                        $diskon_nilai = isset($main["diskon_nilai_total"]) ? $main["diskon_nilai_total"] : 0;
                        if ($diskon_nilai == 0) {
                            $cj = New ComJurnal();
                            $cj->addFilter("transaksi_id='$reference_id'");
                            $cj->addFilter("rekening='1010020030'");
                            $cjTmp = $cj->lookupAll()->result();
                            $diskon_nilai = $cjTmp[0]->debet;
                        }
//                        mati_disini(__LINE__);
                        $arrKlaim[$ini_trid]["nilai_diskon_reguler"] = $diskon_nilai;
                        $arrKlaim[$ini_trid]["nilai_freeproduk"] = $main["produk_rel_harga"];
                        break;
                    case "3344":
                        $main = blobDecode($tmpRegRef[0]->main);
                        $arrKlaim[$ini_trid]["nilai_diskon_reguler"] = $main["nilai_piutang"];
                        break;
                    case "4643":
                        $main = blobDecode($tmpRegRef[0]->main);
                        $arrKlaim[$ini_trid]["nilai_diskon_reguler"] = $main["diskon_nilai"];
                        break;
                }
            }
        }
        // endregion transaksi klaim 3333

        $this->db->trans_start();

        $str = "<table style='border:1px solid black;width:100%;' rules='all'>";

        $str .= "<tr>";
        $str .= "<th>no.</th>";
        foreach ($arrHeader as $key => $val) {
            if (is_array($val)) {
                $bgcolor = isset($val["bgcolor"]) ? $val["bgcolor"] : "";
                $str .= "<th style='background-color:$bgcolor;'>" . $val["label"] . "</th>";
            }
            else {
                $str .= "<th>$val</th>";
            }
        }
        $str .= "</tr>";

        if (sizeof($arrKlaim) > 0) {
            $no = 0;
            foreach ($arrKlaim as $trid => $trSpec) {
                $supplier_id = $trSpec["suppliers_id"];
                $supplier_nama = $trSpec["suppliers_nama"];
                $bgcolor = "";

                //----rebate dari items grn
                if (isset($arrDataLockerTotal[$trid])) {
                    foreach ($arrDataLockerTotal[$trid] as $aa => $bb) {
                        $trSpec[$aa] = $bb;
                    }
                }
                //----rebate dari items grn
                if (isset($arrDataLockerTotalBatal[$trid])) {
                    $bgcolor = "#ff66ff";
                    foreach ($arrDataLockerTotalBatal[$trid] as $aa => $bb) {
                        $trSpec[$aa] = $bb;
                    }
                }
                //----rebate klaim
//                if (isset($arrKlaim[$trid])) {
//                    foreach ($arrKlaim[$trid] as $cc => $dd) {
//                        $trSpec[$cc] = $dd;
//                    }
//                }
                //----rebate klaim
                if (isset($arrKlaimBatal[$trid])) {
                    foreach ($arrKlaimBatal[$trid] as $cc => $dd) {
                        $trSpec[$cc] = $dd;
                    }
                }

                $trSpec["nilai_rebate_total"] = $trSpec["nilai_diskon_reguler"] + $trSpec["nilai_freeproduk"];

                $selisih = $trSpec["nilai_piutang"] - $trSpec["nilai_rebate_total"];
                $trSpec["selisih_plus"] = ($selisih > 0) ? $selisih : 0;
                if ($trSpec["selisih_plus"] > 10) {
                    $bgcolor = "#ff0000";
                    //---- nilai klaim seharusnya (creditnote, pph23 dibayar dimuka, voucher, kas, logam mulia)
                    if ($trSpec["nilai_pph23"] > 10) {
                        $new_nilai_pph23 = (15 / 100) * $trSpec["nilai_rebate_total"];
                        $new_nilai_credit_note = (85 / 100) * $trSpec["nilai_rebate_total"];
                        $trSpec["new_nilai_pph23"] = $new_nilai_pph23;
                        $trSpec["new_nilai_credit_note"] = $new_nilai_credit_note;

                        $adj_nilai_pph23 = (15 / 100) * $trSpec["selisih_plus"];
                        $adj_nilai_credit_note = (85 / 100) * $trSpec["selisih_plus"];
                        $trSpec["adj_nilai_pph23"] = $adj_nilai_pph23;
                        $trSpec["adj_nilai_credit_note"] = $adj_nilai_credit_note;

                        //------PER SUPPLIER
                        $arrSupplierCek[$supplier_id]["suppliers_id"] = $supplier_id;
                        $arrSupplierCek[$supplier_id]["suppliers_nama"] = $supplier_nama;

                        if (!isset($arrSupplierCek[$supplier_id]["selisih_plus"])) {
                            $arrSupplierCek[$supplier_id]["selisih_plus"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["selisih_plus"] += $trSpec["selisih_plus"];

                        if (!isset($arrSupplierCek[$supplier_id]["new_nilai_pph23"])) {
                            $arrSupplierCek[$supplier_id]["new_nilai_pph23"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["new_nilai_pph23"] += $new_nilai_pph23;

                        if (!isset($arrSupplierCek[$supplier_id]["new_nilai_credit_note"])) {
                            $arrSupplierCek[$supplier_id]["new_nilai_credit_note"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["new_nilai_credit_note"] += $new_nilai_credit_note;

                        if (!isset($arrSupplierCek[$supplier_id]["adj_nilai_pph23"])) {
                            $arrSupplierCek[$supplier_id]["adj_nilai_pph23"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["adj_nilai_pph23"] += $adj_nilai_pph23;

                        if (!isset($arrSupplierCek[$supplier_id]["adj_nilai_credit_note"])) {
                            $arrSupplierCek[$supplier_id]["adj_nilai_credit_note"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["adj_nilai_credit_note"] += $adj_nilai_credit_note;
                        //------
                    }
                }

                $no++;
                $str .= "<tr style='background-color:$bgcolor;'>";
                $str .= "<td>$no</td>";
                foreach ($arrHeader as $key => $val) {
                    $val_data = isset($trSpec[$key]) ? $trSpec[$key] : "";
                    if (is_array($val)) {
                        $val_data_f = formatField_he_format($val["format"], $val_data);
                        $align = "right";
                        $bgcolor = isset($val["bgcolor"]) ? $val["bgcolor"] : "";
                    }
                    else {
                        $val_data_f = $val_data;
                        $align = "";
                        $bgcolor = "";
                    }
                    $str .= "<td style='text-align:$align;background-color:$bgcolor;'>";
                    $str .= $val_data_f;
                    $str .= "</td>";

                    if (is_numeric($val_data)) {
                        if (!isset($totalBawah[$key])) {
                            $totalBawah[$key] = 0;
                        }
                        $totalBawah[$key] += $val_data;
                    }
                }
                $str .= "</tr>";
            }
        }

        $str .= "<tr>";
        $str .= "<th>-</th>";
        foreach ($arrHeader as $key => $val) {
            $val_data = isset($totalBawah[$key]) ? $totalBawah[$key] : "";
            if (is_array($val)) {
                $bgcolor = isset($val["bgcolor"]) ? $val["bgcolor"] : "";
                $val_data_f = formatField_he_format($val["format"], $val_data);
                $align = "right";
            }
            else {
                $bgcolor = "";
                $val_data_f = $val_data;
                $align = "";
            }
            $str .= "<th style='text-align:$align;background-color:$bgcolor;'>";
            $str .= $val_data_f;
            $str .= "</th>";
        }
        $str .= "</tr>";

        $str .= "<tr>";
        $str .= "<th>-</th>";
        foreach ($arrHeader as $key => $val) {
            if (is_array($val)) {
                $val_data_f = $val["label"];
                $align = "right";
            }
            else {
                $val_data_f = $val;
                $align = "";
            }
            $str .= "<th sstyle='text-align:$align;'>";
            $str .= $val_data_f;
            $str .= "</th>";
        }
        $str .= "</tr>";

        $str .= "</table>";
        //-------
        $str .= "<br><br><br>";
        //-------
        echo $str;
//        arrPrintHitam($arrSupplierCek);

        $str = "<table style='border:1px solid black;width:100%;' rules='all'>";
        $str .= "<tr>";
        $str .= "<th>no.</th>";
        foreach ($arrHeader2 as $key => $val) {
            if (is_array($val)) {
                $str .= "<th>" . $val["label"] . "</th>";
            }
            else {
                $str .= "<th>$val</th>";
            }
        }
        $str .= "</tr>";
        if (sizeof($arrSupplierCek) > 0) {
            $no = 0;
            foreach ($arrSupplierCek as $trid => $trSpec) {
                $no++;
                $str .= "<tr style='background-color:$bgcolor;'>";
                $str .= "<td>$no</td>";
                foreach ($arrHeader2 as $key => $val) {
                    $val_data = isset($trSpec[$key]) ? $trSpec[$key] : "";
                    if (is_array($val)) {
                        $val_data_f = formatField_he_format($val["format"], $val_data);
                        $align = "right";
                    }
                    else {
                        $val_data_f = $val_data;
                        $align = "";
                    }
                    $str .= "<td style='text-align:$align;'>";
                    $str .= $val_data_f;
                    $str .= "</td>";
                    if (is_numeric($val_data)) {
                        if (!isset($totalBawah2[$key])) {
                            $totalBawah2[$key] = 0;
                        }
                        $totalBawah2[$key] += $val_data;
                    }
                }
                $str .= "</tr>";
            }
            $str .= "<tr>";
            $str .= "<th>-</th>";
            foreach ($arrHeader2 as $key => $val) {
                $val_data = isset($totalBawah2[$key]) ? $totalBawah2[$key] : "";
                if (is_array($val)) {
                    $val_data_f = formatField_he_format($val["format"], $val_data);
                    $align = "right";
                }
                else {
                    $val_data_f = $val_data;
                    $align = "";
                }
                $str .= "<th style='text-align:$align;'>";
                $str .= $val_data_f;
                $str .= "</th>";
            }
            $str .= "</tr>";
        }
        $str .= "</table style='border:1px solid black;width:100%;' rules='all'>";
        echo $str;


        mati_disini("---SETOP--- " . __LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>DONE...</h3>");
    }

    public function cekUangMukaPymSrc()
    {
        $tbl_1 = "_rek_pembantu_uang_muka_reference_cache";
        $tbl_2 = "transaksi_uang_muka_source";
        $rekening = "1010050010";
        $periode = "forever";
        $cabang_id = "-1";
        $supplier_id = $_GET["sid"];

        //region cache-------
        $where = array(
            "rekening" => $rekening,
            "cabang_id" => $cabang_id,
            "periode" => $periode,
        );
        if ($supplier_id > 0) {
            $where["extern_id"] = $supplier_id;
        }
        $this->db->where($where);
        $cacheTmp = $this->db->get($tbl_1)->result();
        showLast_query("biru");
        cekBiru(count($cacheTmp));
        //endregion cache-------

        //region pymsrc
        $where = array(
            "extern_label2" => "vendor",
            "cabang_id" => $cabang_id,
            "label" => "uang muka",
        );
        if ($supplier_id > 0) {
            $where["extern_id"] = $supplier_id;
        }
        $this->db->where($where);
        $pymSsourceTmp = $this->db->get($tbl_2)->result();
        showLast_query("kuning");
        cekKuning(count($pymSsourceTmp));
        if (sizeof($pymSsourceTmp) > 0) {
            foreach ($pymSsourceTmp as $pymSsourceSpec) {
                if (!isset($pymUMRelasi[$pymSsourceSpec->extern_id][$pymSsourceSpec->extern2_id]["sisa"])) {
                    $pymUMRelasi[$pymSsourceSpec->extern_id][$pymSsourceSpec->extern2_id]["sisa"] = 0;
                }
                $pymUMRelasi[$pymSsourceSpec->extern_id][$pymSsourceSpec->extern2_id]["sisa"] += $pymSsourceSpec->sisa;
                if (!isset($pymUMRelasi[$pymSsourceSpec->extern_id][$pymSsourceSpec->extern2_id]["terbayar"])) {
                    $pymUMRelasi[$pymSsourceSpec->extern_id][$pymSsourceSpec->extern2_id]["terbayar"] = 0;
                }
                $pymUMRelasi[$pymSsourceSpec->extern_id][$pymSsourceSpec->extern2_id]["terbayar"] += $pymSsourceSpec->sisa;

                $pymUMRelasi[$pymSsourceSpec->extern_id][$pymSsourceSpec->extern2_id]["extern_id"] = $pymSsourceSpec->extern_id;
                $pymUMRelasi[$pymSsourceSpec->extern_id][$pymSsourceSpec->extern2_id]["extern_nama"] = $pymSsourceSpec->extern_nama;
                $pymUMRelasi[$pymSsourceSpec->extern_id][$pymSsourceSpec->extern2_id]["extern2_id"] = $pymSsourceSpec->extern2_id;
                $pymUMRelasi[$pymSsourceSpec->extern_id][$pymSsourceSpec->extern2_id]["extern2_nama"] = $pymSsourceSpec->extern2_nama;
            }
        }
        //endregion pymsrc

        $this->db->trans_start();


        // region uang muka ke supplier berelasi po, tampilkan di ui tabel
        $str = "<table rules='all' width='75%' style='border:1px solid black;'>";
        $str .= "<tr>";
        $str .= "<th>no.</th>";
        $str .= "<th>supplier ID</th>";
        $str .= "<th>supplier Nama</th>";
        $str .= "<th>referensi ID</th>";
        $str .= "<th>referensi nomer</th>";
        $str .= "<th>cache</th>";
        $str .= "<th>pym src</th>";
        $str .= "</tr>";
        if (sizeof($cacheTmp) > 0) {
            $no = 0;
            $totalBawah = array();
            foreach ($cacheTmp as $cacheSpec) {
                $extern_id = $cacheSpec->extern_id;
                $extern_nama = $cacheSpec->extern_nama;
                $extern2_id = $cacheSpec->extern2_id;
                $extern2_nama = $cacheSpec->extern2_nama;
                $debet = $cacheSpec->debet;
                $sisa = isset($pymUMRelasi[$extern_id][$extern2_id]["sisa"]) ? $pymUMRelasi[$extern_id][$extern2_id]["sisa"] : 0;
                $bgcolor = "";
                if (($sisa - $debet) > 100) {
                    $bgcolor = "yellow";

                    // region patch pym src
                    $where_update = array(
                        "extern_id" => $extern_id,
                        "extern2_id" => $extern2_id,
                        "label" => "uang muka",
                        "cabang_id" => $cabang_id,
                        "extern_label2" => "vendor",
                    );
                    $data_update = array(
                        "sisa" => $debet,
                    );
                    $this->db->where($where_update);
                    $this->db->update($tbl_2, $data_update);
                    showLast_query("orange");
                    // endregion patch pym src

                }
                if (!isset($totalBawah["debet"])) {
                    $totalBawah["debet"] = 0;
                }
                $totalBawah["debet"] += $debet;
                if (!isset($totalBawah["sisa"])) {
                    $totalBawah["sisa"] = 0;
                }
                $totalBawah["sisa"] += $sisa;

                $no++;
                $str .= "<tr style='background-color:$bgcolor;'>";
                $str .= "<td>$no</td>";
                $str .= "<td>$extern_id</td>";
                $str .= "<td>$extern_nama</td>";
                $str .= "<td>$extern2_id</td>";
                $str .= "<td>$extern2_nama</td>";
                $str .= "<td>" . number_format($debet) . "</td>";
                $str .= "<td>" . number_format($sisa) . "</td>";
                $str .= "</tr>";
            }
            $str .= "<tr>";
            $str .= "<th>-</th>";
            $str .= "<th>-</th>";
            $str .= "<th>-</th>";
            $str .= "<th>-</th>";
            $str .= "<th>-</th>";
            $str .= "<th>" . number_format($totalBawah["debet"]) . "</th>";
            $str .= "<th>" . number_format($totalBawah["sisa"]) . "</th>";
            $str .= "</tr>";
        }
        $str .= "</table>";
        echo $str;
        // endregion uang muka ke supplier berelasi po, tampilkan di ui tabel


        mati_disini("...cek MANUAL cli transaksi... ");
        $this->db->trans_complete() or mati_disini("Gagal saat berusaha  commit transaction!");


    }

    public function cekCashbackInvoice()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlLockerTransaksi");
        // daftar invoice yang dibatalkan
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='4822'");
        $tr->addFilter("trash_4=1");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        cekBiru(count($trTmp));
        $arrTrIDs = array();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {

                $arrTrIDs[$trSpec->id] = $trSpec->id;
            }
//            arrPrint($arrTrIDs);
            // daftar invoice yang sudah diberi cashback
            $tri = New MdlLockerTransaksi();
            $tri->setFilters(array());
            $tri->addFilter("produk_id in ('" . implode("','", $arrTrIDs) . "')");
            $tri->addFilter("jenis='komisi'");
            $tri->addFilter("state='hold'");
            $triTmp = $tri->lookupAll()->result();
            showLast_query("kuning");
            cekKuning(count($triTmp));


        }

    }

    public function patchTransaksiSupplies()
    {

        $this->db->trans_start();


        $this->load->model("MdlTransaksi");
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='461r'");
        $trTmp = $tr->lookupAll()->result();
        cekBiru(count($trTmp));
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $ini_trid = $trSpec->id;
                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->setJointSelectFields("transaksi_id, main");
                $trreg->addFilter("transaksi_id='$ini_trid'");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $main = blobDecode($tmpReg[0]->main);

                $transaksi_nilai = $trSpec->transaksi_nilai;
                $transaksi_nilai_main = isset($main["nett"]) ? $main["nett"] : 0;
                $selisih = $transaksi_nilai - $transaksi_nilai_main;
                $selisih = ($selisih < 0) ? ($selisih * -1) : $selisih;
                if ($selisih > 10) {
                    // update
                    $tru = New MdlTransaksi();
                    $where = array(
                        "id" => $ini_trid,
                    );
                    $data = array(
                        "transaksi_nilai" => $transaksi_nilai_main,
                    );
                    $tru->setFilters(array());
                    $tru->updateData($where, $data);
                    showLast_query("orange");
                }
            }
        }

//        mati_disini("---SETOP--- " . __LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>DONE...</h3>");

    }


    //-----
    public function cekRekeningPembantu()
    {

        $rekening = "1010020010";
        $tabel_main = "__rek_master__" . $rekening;
        $tabel_pembantu = "__rek_pembantu_customer__" . $rekening;
        $arrMain = array();
        $arrPembantu = array();
        $bln_thn = "01-2024";
//        $bln_thn = "02-2024";
//        $bln_thn = "03-2024";
//        $bln_thn = "04-2024";
//        $bln_thn = "05-2024";
//        $bln_thn = "06-2024";
//        $bln_thn = "07-2024";
//        $bln_thn = "08-2024";
//        $bln_thn = "09-2024";
//        $bln_thn = "10-2024";
//        $bln_thn = "11-2024";
//        $bln_thn = "12-2024";
//        $bln_thn = "01-2025";
//        $bln_thn = "02-2025";
//        $bln_thn = "03-2025";
//        $bln_thn = "04-2025";
//        $bln_thn = "05-2025";
//        $bln_thn = "06-2025";
//        $bln_thn = "07-2025";
//        $bln_thn = "08-2025";
//        $bln_thn = "09-2025";
//        $bln_thn = "10-2025";
//        $bln_thn = "11-2025";
//        $bln_thn = "12-2025";

        $bln_thn_ex = explode("-", $bln_thn);
        $bln = $bln_thn_ex[0];
        $thn = $bln_thn_ex[1];
        $where = array(
            "month(dtime)" => "$bln",
            "year(dtime)" => "$thn",
        );
        $this->db->where($where);
        $query_main = $this->db->get($tabel_main)->result();
        showLast_query("kuning");
        cekKuning(count($query_main));
        if (sizeof($query_main) > 0) {
            foreach ($query_main as $spec_main) {
                $debet = $spec_main->debet;
                $kredit = $spec_main->kredit;
                unset($spec_main->debet);
                unset($spec_main->kredit);
                if (!isset($arrMain[$spec_main->transaksi_id])) {
                    $arrMain[$spec_main->transaksi_id] = (array)$spec_main;
                }
                if (!isset($arrMain[$spec_main->transaksi_id]["debet"])) {
                    $arrMain[$spec_main->transaksi_id]["debet"] = 0;
                }
                if (!isset($arrMain[$spec_main->transaksi_id]["kredit"])) {
                    $arrMain[$spec_main->transaksi_id]["kredit"] = 0;
                }
                $arrMain[$spec_main->transaksi_id]["debet"] += $debet;
                $arrMain[$spec_main->transaksi_id]["kredit"] += $kredit;
            }
        }


        $where = array(
            "month(dtime)" => "$bln",
            "year(dtime)" => "$thn",
        );
        $this->db->where($where);
        $query_pembantu = $this->db->get($tabel_pembantu)->result();
        showLast_query("ungu");
        cekUngu(count($query_pembantu));
        if (sizeof($query_pembantu) > 0) {
            foreach ($query_pembantu as $spec_pembantu) {
                $debet = $spec_pembantu->debet;
                $kredit = $spec_pembantu->kredit;
                unset($spec_pembantu->debet);
                unset($spec_pembantu->kredit);
                if (!isset($arrPembantu[$spec_pembantu->transaksi_id])) {
                    $arrPembantu[$spec_pembantu->transaksi_id] = (array)$spec_pembantu;
                }
                if (!isset($arrPembantu[$spec_pembantu->transaksi_id]["debet"])) {
                    $arrPembantu[$spec_pembantu->transaksi_id]["debet"] = 0;
                }
                if (!isset($arrPembantu[$spec_pembantu->transaksi_id]["kredit"])) {
                    $arrPembantu[$spec_pembantu->transaksi_id]["kredit"] = 0;
                }
                $arrPembantu[$spec_pembantu->transaksi_id]["debet"] += $debet;
                $arrPembantu[$spec_pembantu->transaksi_id]["kredit"] += $kredit;
            }
        }

        //------
        $arrKeysmain = array_keys($arrMain);
        $arrKeyspembantu = array_keys($arrPembantu);
        $arrDiff_1 = array_diff($arrKeysmain, $arrKeyspembantu);
        $arrDiff_2 = array_diff($arrKeyspembantu, $arrKeysmain);
        arrPrintCyan($arrDiff_1);
        arrPrintKuning($arrDiff_2);

        $header = array(
            "transaksi_id" => "trid",
            "transaksi_no" => "nomer",
            "debet" => "debet",
            "kredit" => "kredit",
            "pembantu_debet" => "pembantu_debet",
            "pembantu_kredit" => "pembantu_kredit",
        );
        $header_summary = array(
            "debet" => "debet",
            "kredit" => "kredit",
            "pembantu_debet" => "pembantu_debet",
            "pembantu_kredit" => "pembantu_kredit",
        );

        $str = "<table rules='all' style='border:1px solid black;' width='100%'>";
        $str .= "<tr>";
        $str .= "<th>No.</th>";
        foreach ($header as $key => $val) {
            $str .= "<th>$val</th>";
        }
        $str .= "</tr>";

        $no = 0;
        if (sizeof($arrMain) > 0) {
            foreach ($arrMain as $trid => $spec_main) {
                if (isset($arrPembantu[$trid])) {
                    foreach ($arrPembantu[$trid] as $mkey => $mval) {
                        $new_key = "pembantu_" . $mkey;
                        $spec_main[$new_key] = $mval;
                    }
                }

                $selisih_debet = ($spec_main["debet"] - $spec_main["pembantu_debet"]);
                $selisih_debet = ($selisih_debet < 0) ? ($selisih_debet * -1) : $selisih_debet;
                $selisih_kredit = ($spec_main["kredit"] - $spec_main["pembantu_kredit"]);
                $selisih_kredit = ($selisih_kredit < 0) ? ($selisih_kredit * -1) : $selisih_kredit;
                if ($selisih_debet > 1) {
                    $bgcolor = "yellow";
                }
                elseif ($selisih_kredit > 1) {
                    $bgcolor = "pink";
                }
                else {
                    $bgcolor = "";
                }

                $no++;
                $str .= "<tr style='background-color:$bgcolor;'>";
                $str .= "<td>$no</td>";
                foreach ($header as $key => $val) {
                    $new_val = isset($spec_main[$key]) ? $spec_main[$key] : "-";
                    $str .= "<td>$new_val</td>";
                    if (array_key_exists($key, $header_summary)) {
                        if (!isset($totalBawah[$key])) {
                            $totalBawah[$key] = 0;
                        }
                        $totalBawah[$key] += $new_val;
                    }
                }
                $str .= "</tr>";
            }

            $selisih_debet = ($totalBawah["debet"] - $totalBawah["pembantu_debet"]);
            $selisih_debet = ($selisih_debet < 0) ? ($selisih_debet * -1) : $selisih_debet;
            $selisih_kredit = ($totalBawah["kredit"] - $totalBawah["pembantu_kredit"]);
            $selisih_kredit = ($selisih_kredit < 0) ? ($selisih_kredit * -1) : $selisih_kredit;
            if ($selisih_debet > 1) {
                $bgcolor = "yellow";
            }
            elseif ($selisih_kredit > 1) {
                $bgcolor = "pink";
            }
            else {
                $bgcolor = "";
            }
            $str .= "<tr style='background-color:$bgcolor;'>";
            $str .= "<th>-</th>";
            foreach ($header as $key => $val) {
                $new_val = isset($totalBawah[$key]) ? $totalBawah[$key] : "-";
                $str .= "<th>$new_val</th>";

            }
            $str .= "</tr>";
        }

        $str .= "</table>";
        echo $str;
    }

    // part produk
    public function cekPartProduk()
    {
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlModelIndoor_1");
        $indoors = array();

        $mi = New MdlModelIndoor_1();
        $miTmp = $mi->lookupAll()->result();
        if (sizeof($miTmp) > 0) {
            foreach ($miTmp as $miSpec) {
                $indoors[$miSpec->id] = array(
                    "id" => $miSpec->id,
                    "nama" => $miSpec->nama,
                    "sku" => $miSpec->sku,
                );
            }
        }
//        arrPrintCyan($indoors);

        $p = New MdlProduk();
        $p->addFilter("jml_serial>1");
        $pTmp = $p->lookupAll()->result();
        showLast_query("biru");
        cekBiru("data ada: " . count($pTmp));
        if (sizeof($pTmp) > 0) {
            foreach ($pTmp as $pSpec) {
                $produk_id = $pSpec->id;
                $indoor_id_1 = $pSpec->indoor_id_1;
                $indoor_id_2 = $pSpec->indoor_id_2;
                $indoor_sku_1 = $pSpec->indoor_sku_1;
                $indoor_sku_2 = $pSpec->indoor_sku_2;
                if (trim($indoor_sku_1) != trim($indoors[$indoor_id_1]["sku"])) {
                    cekHere("[pid: $produk_id] [indoor_id_1: $indoor_id_1] [$indoor_sku_1] || --> " . $indoors[$indoor_id_1]["sku"]);
                }
                if (trim($indoor_sku_2) != trim($indoors[$indoor_id_2]["sku"])) {
                    cekKuning("[pid: $produk_id] [indoor_id_2: $indoor_id_2] [$indoor_sku_2] || --> " . $indoors[$indoor_id_2]["sku"]);
                }

            }
        }

        cekHijau("<h3>CEK SELESAI...</h3>");
    }

    // cek saldi um relasi (penjualan tunai)
    public function cekUangMuka($cab_id = NULL, $gud_id = NULL)
    {
        $this->load->model("MdlTransaksi");
        $cabang_id = ($cab_id != NULL) ? $cab_id : "1";
        $gudang_id = ($gud_id != NULL) ? $gud_id : "-10";
        $sesionReplacer = array(
            "cabang_id" => $cabang_id,
            "gudang_id" => $gudang_id,
        );
        $arrSisaUangMuka = array();
        $jenisTr = "999";
        $rekening = "2010050";
        $subrekening = "2010050050";
        $com = "ComRekeningPembantuCustomerDetail";

        // region saldo um relasi
        $this->load->model("Coms/$com");
        $crd = New $com();
        $crd->addFilter("cabang_id='$cabang_id'");
        $crd->addFilter("extern2_id='$subrekening'");
        $crd->addFilter("kredit>100");
        $crdTmp = $crd->fetchBalances($rekening);
        showLast_query("biru");
        cekBiru(count($crdTmp));
        // endregion saldo um relasi
        //mati_disini(__LINE__);

        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            // region so aktif
            $tr = new MdlTransaksi();
            $tr->addFilter("jenis_top in ('5822spo','5823spo')");
            $tr->addFilter("next_substep_code<>''");
            $tr->addFilter("sub_step_number>0");
            $tr->addFilter("valid_qty>0");
            $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
            showLast_query("kuning");
            cekKuning(count($tmpHist));
            if (sizeof($tmpHist) > 0) {
                foreach ($tmpHist as $row) {
                    if ($row->ids_his != "") {
                        $hist = blobDecode($row->ids_his);
                        foreach ($hist as $step_his => $hisSpec) {
                            $arrTransID[] = $row->transaksi_id;
                            $arrTransHist[] = $hisSpec['trID'];
                            $arrIdsHist[$row->transaksi_id] = array(
                                "referenceID__" . $step_his => $hisSpec['trID'],
                                "referenceNumber__" . $step_his => $hisSpec['nomer'],
                                "referenceNomer__" . $step_his => $hisSpec['nomer'],
                                "referenceDtime__" . $step_his => $hisSpec['dtime'],
                                "referenceFulldate__" . $step_his => $hisSpec['fulldate'],
                            );
                        }
                    }
                }
                $pairRegistries = array("main", "items");
                $selectKolom = implode(",", $pairRegistries) . ", transaksi_id";
                $trReg = new MdlTransaksi();
                $trReg->setFilters(array());
                $trReg->setJointSelectFields($selectKolom);
                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $tmpReg = $trReg->lookupDataRegistries()->result();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        //                    arrPrintWebs($regRow);
                        foreach ($regRow as $key_reg => $val_reg) {
                            if ($val_reg == null) {
                                $val_reg = blobEncode(array());
                            }
                            if ($key_reg != "transaksi_id") {
                                $tmpReg_result[$regRow->transaksi_id][$key_reg] = blobDecode($val_reg);
                            }
                        }

                    }
                }
                foreach ($tmpHist as $row) {
                    $transaksi_idd = $row->transaksi_id;
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->transaksi_id]))) {
                        foreach ($tmpReg_result[$row->transaksi_id] as $param => $eReg) {
                            switch ($param) {
                                case "main":
                                    foreach ($eReg as $k => $v) {
                                        if (($k != null) && !isset($row->$k)) {
                                            $row->$k = $v;
                                        }
                                    }
                                    break;
                                case "items":
                                    if (sizeof($extHistoryFields2) > 0) {
                                        foreach ($extHistoryFields2 as $k1 => $v1) {
                                            if (is_array($v1)) {
                                                $kolom = $v1['kolom'];
                                                $format = $v1['format'];
                                                if (($k1 != null) && !isset($row->$k1)) {
                                                    $tmpDetail = "";
                                                    foreach ($eReg as $eeReg) {
                                                        $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                    $row->$k1 = $tmpDetail;
                                                }
                                            }
                                            else {
                                                if (($k1 != null) && !isset($row->$k1)) {
                                                    $tmpDetail = "";
                                                    foreach ($eReg as $eeReg) {
                                                        $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                    $row->$k1 = $tmpDetail;
                                                }
                                            }
                                        }
                                    }
                                    break;
                            }
                        }
                    }
//                arrPrintPink($row);
                    if ($row->step_number > 1) {
                        $grand_pembulatan = $row->grand_pembulatan;
                        $TransData[$row->customers_id]["customer_id"] = $row->customers_id;
                        $TransData[$row->customers_id]["customer_nama"] = $row->customers_nama;
                        $TransData[$row->customers_id]["relasi_so"][$row->transaksi_id] = $row->nomer;
                        if (!isset($TransData[$row->customers_id]["relasi_so_nilai"])) {
                            $TransData[$row->customers_id]["relasi_so_nilai"] = 0;
                        }
                        $TransData[$row->customers_id]["relasi_so_nilai"] += $grand_pembulatan;
                    }
//                break;
                }
            }
            // endregion so aktif
        }

        $header = array(
            "extern_id" => "id konsumen",
            "extern_nama" => "nama konsumen",
            "kredit" => "saldo",
            "relasi_so" => "relasi",
            "relasi_so_nilai" => "nilai relasi<br>aktif",
            "selisih" => "nilai relasi<br>tidak aktif",
        );

        $str = "<table rules='all' width='100%' style='border:1px solid black;'>";
        $str .= "<tr>";
        $str .= "<th>no.</th>";
        foreach ($header as $key => $val) {
            $str .= "<th>$val</th>";
        }
        $str .= "</tr>";


        $no = 0;
        foreach ($crdTmp as $spec) {
            if (isset($TransData[$spec->extern_id])) {
                foreach ($TransData[$spec->extern_id] as $ikey => $ival) {
                    $spec->$ikey = $ival;
                }
            }
            $customer_id = $spec->extern_id;
            $customer_nama = $spec->extern_nama;
            $saldo = $spec->kredit;
            $saldo_relasi = $spec->relasi_so_nilai;
            $selisih = $saldo - $saldo_relasi;
            $spec->selisih = $selisih;
            if ($selisih > 100) {

                $no++;
                $str .= "<tr>";
                $str .= "<td>$no</td>";
                foreach ($header as $key => $val) {
                    $align = "left";
                    $isi = isset($spec->$key) ? $spec->$key : "-";
                    if (is_numeric($isi)) {
                        if (!isset($totalBawah[$key])) {
                            $totalBawah[$key] = 0;
                        }
                        $totalBawah[$key] += $isi;
                        $align = "right";
                    }

                    switch ($key) {
                        case "relasi_so":
                            $isi = implode("<br>", $isi);
                            break;
                        case "selisih":
                        case "kredit":
                        case "relasi_so_nilai":
                            $isi = number_format($isi, "0", ".", ",");
                            break;
                    }
                    $str .= "<td style='text-align:$align;'>$isi</td>";

                }
                $str .= "</tr>";

                $arrSisaUangMuka[$customer_id] = array(
//                    "cabang_id" => $cabang_id,
//                    "gudang_id" => 0,
//                    "customer_id" => $customer_id,
//                    "customer_nama" => $customer_nama,
//                    "nilai" => $selisih,
                    "id" => $customer_id,
                    "nama" => $customer_nama,
                    "name" => $customer_nama,
                    "hpp" => $selisih,
                    "harga" => $selisih,
                    "jml" => 1,
                    "qty" => 1,
                    "reference_nomer" => "",
                    "keterangan_detail" => "",
                );
            }
        }
        $str .= "<tr>";
        $str .= "<th>-</th>";
        foreach ($header as $key => $val) {
            $isi = isset($totalBawah[$key]) ? $totalBawah[$key] : "-";
            $str .= "<th>$isi</th>";
        }
        $str .= "</tr>";

        $str .= "</table>";
        echo $str;

        cekHitam(count($arrSisaUangMuka));
        return $arrSisaUangMuka;
    }

    //-------------------------------------------
    public function koreksiAdjustment()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlProduk2");


        // load transaksi opname, mengambil data yang akan dieksekusi
        $cabangID = "34";
        $cabangNama = "CABANG GADING SERPONG";
        $gudangID = "-340";
        $gudangNama = "default warehouse at branch #34";

        $olehID = "100";
        $olehNama = "system";
        $supplierID = "0";
        $supplierNama = "";
        $pihakID = "0";
        $pihakNama = "";
        $jenis = "999";
        $this->jenisTr = $jenisTr = "999";
        $jenisTrMaster = "999";
        $dtime = date("Y-m-d H:i:s");
        $fulldate = date("Y-m-d");
        $ppnFactor = 11;
        $divID = 18;
        $cash_account = 0;
        $cash_account_nama = "";
        $referenceID = 0;
        $referenceNomer = "";
        $referenceJenis = "";
        $modul_transaksi = "adjustment";
        $tCodeTargetJenisTransaksi = $target_transaksi = $jenisTrMaster;
        $keterangan = "koreksi/pindah uang muka atas nama konsumen (umum) dari cabang ke dc/pusat";

        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            $arrDataDetail = array(
                "3143" => array(
                    "id" => "3143",
                    "nama" => "GREE CASEETTE 5PK GU140T/A-K",
                    "name" => "GREE CASEETTE 5PK GU140T/A-K",
                    "hpp" => "21687387",
                    "harga" => "21687387",
                    "jml" => "8",
                    "qty" => "8",
                    "reference_nomer" => "",
                    "keterangan_detail" => "",
                ),
                "24407" => array(
                    "id" => "24407",
                    "nama" => "GREE GWC-18N1/A",
                    "name" => "GREE GWC-18N1/A",
                    "hpp" => "5562208",
                    "harga" => "5562208",
                    "jml" => "4",
                    "qty" => "4",
                    "reference_nomer" => "",
                    "keterangan_detail" => "",
                ),
            );
        }
        else {
            $arrDataDetail = $this->cekUangMuka($cabangID, $gudangID);
        }

//        arrPrintPink($arrDataDetail);
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
            "referenceID" => $referenceID,
            "referenceNomer" => $referenceNomer,
            "referenceJenis" => $referenceJenis,
            "reference_id" => $referenceID,
            "reference_nomer" => $referenceNomer,
            "reference_jenis" => $referenceJenis,

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

        $detailGate = array();
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            $total_nilai = 0;
            $arrProdukDatas = array();
            $arrprodukIDs = $arrDataDetail;
            $arrprodukIDKey = array_keys($arrprodukIDs);
            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                $pr = New MdlProduk2();
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
                }
            }

            //region 1 produk
            foreach ($detailGate as $pid => $spec) {
                foreach ($mainGate as $key => $val) {
                    $spec[$key] = $val;
                }
                $detailGate[$pid] = $spec;
                foreach ($tableIn["detail"] as $ikey => $ival) {
                    $tableIn_detail[$pid][$ikey] = isset($spec[$ival]) ? $spec[$ival] : "";
                }
            }
            //endregion
        }
        else {

        }


        foreach ($tableIn["master"] as $key => $val) {
            $tableIn_master[$key] = isset($mainGate[$val]) ? $mainGate[$val] : "";
        }

//        $mainGate["hpp"] = -$harga_pokok;
//        $mainGate["persediaan_produk"] = -$persediaan_produk;
//        $mainGate["hutang_ke_pusat"] = $hutang_ke_pusat;
//        $mainGate["piutang_cabang_minus_3"] = $piutang_cabang;
//        $mainGate["piutang_dagang"] = -$piutang_dagang;
//        $mainGate["laba_lain_lain"] = -$laba_lain_lain;
//        $mainGate["hutang_dagang"] = -$hutang_dagang;
//        $mainGate["hutang_dagang_detail_1"] = $hutang_dagang_detail_1;
//        $mainGate["hutang_dagang_detail_2"] = -$hutang_dagang_detail_2;
//        $mainGate["modal"] = -$modal;
//        $mainGate["ppn_masukan"] = $ppn_masukan;
//        $mainGate["ppn_masukan_jasa"] = -$ppn_masukan_jasa;
//        $mainGate["kas"] = $kas;
//        $mainGate["kas_minus"] = -$kas_minus;
//        $mainGate["hutang_ke_konsumen"] = -$hutang_ke_konsumen;
//        $mainGate["hutang_ke_konsumen_noppn"] = $hutang_ke_konsumen_noppn;
//        $mainGate["hutang_ke_konsumen_noppn_minus"] = -$hutang_ke_konsumen_noppn;
//        $mainGate["titipan_tanpa_relasi"] = $titipan_tanpa_relasi;
//        $mainGate["titipan_dengan_relasi"] = -$titipan_dengan_relasi;

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
            "detail" => array(),
        );
        $components = array(
            "master" => array(),
            "detail" => array(),
        );
        $postProcessor = array(
            "master" => array(),
            "detail" => array(),
        );
        //--------------------------


        // MEMBUAT TRANSAKSI
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            //region dynamic counters

            $counters = array(
                "stepCode|placeID",
                "stepCode|olehID",
                "stepCode|placeID|olehID",
            );
            $formatNota = "stepCode|placeID";

            $pakai_ini = 0;
            if ($pakai_ini == 1) {
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
                cekMerah(":: $tmpNomorNota ::");
                mati_disini(__LINE__);
                //endregion penomoran receipt
            }
            else {
                $this->load->model("CustomCounter");
                $cn = new CustomCounter("transaksi");
                $cn->setType("transaksi");
                $cn->setModul($modul_transaksi);
                $cn->setStepCode($tCodeTargetJenisTransaksi);
                $counterForNumber = array($formatNota);
                if (!in_array($counterForNumber[0], $counters)) {
                    mati_disini(__LINE__ . " Used number should be registered in 'counters' config as well");
                }
                echo "<div style='background:#ff7766;'>";
                foreach ($counterForNumber as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $this->cCodeData[$cCode]['main'][$param];
                    }
                    $cRawValues = implode("|", $cValues[$i]);
                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
                }
                echo "</div style='background:#ff7766;'>";
                //arrPrintWebs($paramSpec);

                $tmpNomorNota = $paramSpec['paramString'];
                $tmpNomorNotaAlias = formatNota("nomer_nolink", $tmpNomorNota);
                cekMerah("[$tmpNomorNota] [$tmpNomorNotaAlias]");

                $cn = new CustomCounter("transaksi");
                $cn->setType("transaksi");
                $cn->setType("transaksi");
                $cn->setModul($modul_transaksi);
                $cn->setStepCode($tCodeTargetJenisTransaksi);
                $configCustomParams = $counters;
                $configCustomParams[] = "stepCode";
                //arrPrint($configCustomParams);
                if (sizeof($configCustomParams) > 0) {
                    $cContent = array();
                    foreach ($configCustomParams as $i => $cRawParams) {
                        $cParams = explode("|", $cRawParams);
                        $cValues = array();
                        foreach ($cParams as $param) {
                            $cValues[$i][$param] = $this->cCodeData[$cCode]['main'][$param];
                        }
                        $cRawValues = implode("|", $cValues[$i]);
                        $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                        $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                        switch ($paramSpec['id']) {
                            case 0: //===counter type is new
                                $paramKeyRaw = print_r($cParams, true);
                                $paramValuesRaw = print_r($cValues[$i], true);
                                $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                                break;
                            default: //===counter to be updated
                                $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                                break;
                        }
                        //echo "<hr>";
                    }
                }
                $appliedCounters = base64_encode(serialize($cContent));
                $appliedCounters_inText = print_r($cContent, true);
//                arrPrint($appliedCounters);
//                mati_disini(__LINE__);

            }


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
                    cekUngu($this->db->last_query());
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
            $insertID = "523786";
            $insertNum = "999.-1.56";
        }


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
        $pakai_ini = 0;
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
        $pakai_ini = 0;
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
            showLast_query("biru");

        }
        //endregion


        $pakai_ini = 0;
        if ($pakai_ini == 1) {

            $this->load->model("Coms/ComRekeningPembantuProduk");
            $this->load->model("Coms/ComProdukSerialNumber");
            foreach ($arrprodukIDs as $pid => $pSpec) {
                $itemID = $pid;
                $crd = New ComRekeningPembantuProduk();
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
                    $ff = New MdlFifoAverage();
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
                        $ss = New ComProdukSerialNumber();
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
                $tr = New MdlTransaksi();
                $tr->setFilters(array());
                $tr->updatePaymentSrc($where, $data);
                showLast_query("orange");

            }
        }


        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            $this->load->model("Coms/ComRekeningPembantuCustomer");
            $this->load->model("Coms/ComRekeningPembantuCustomerDetail");
            $this->load->model("Coms/ComPaymentUangMuka");
            $this->load->model("Coms/ComPaymentUangMukaCustomer");
            $this->load->model("Coms/ComRekeningPembantuAntarcabang");
            $this->load->model("Coms/ComJurnal");
            $this->load->model("Coms/ComRekening");

            // CABANG
            $anu_j = array(
                "comName" => "Jurnal",
                "loop" => array(
                    "2010050" => "-" . $total_nilai,// hutang ke konsumen
                    "2040010" => $total_nilai,// hutang ke pusat
                ),
                "static" => array(
                    "cabang_id" => $cabangID,
                    "jenis" => $jenisTr,
                    "transaksi_id" => $insertID,
                    "transaksi_no" => $insertNum,
                    "dtime" => date("Y-m-d H:i:s"),
                    "fulldate" => date("Y-m-d"),
                ),
            );
            $anu_r = array(
                "comName" => "Rekening",
                "loop" => array(
                    "2010050" => "-" . $total_nilai,// hutang ke konsumen
                    "2040010" => $total_nilai,// hutang ke pusat
                ),
                "static" => array(
                    "cabang_id" => $cabangID,
                    "jenis" => $jenisTr,
                    "transaksi_id" => $insertID,
                    "transaksi_no" => $insertNum,
                    "dtime" => date("Y-m-d H:i:s"),
                    "fulldate" => date("Y-m-d"),
                ),
            );
            $anu_ac = array(
                "comName" => "Rekening",
                "loop" => array(
                    "2040010" => $total_nilai,// hutang ke pusat
                ),
                "static" => array(
                    "cabang_id" => $cabangID,
                    "jenis" => $jenisTr,
                    "extern_id" => "-1",
                    "extern_nama" => "PUSAT",
                    "transaksi_id" => $insertID,
                    "transaksi_no" => $insertNum,
                    "dtime" => date("Y-m-d H:i:s"),
                    "fulldate" => date("Y-m-d"),
                ),
            );
            $cj = New ComJurnal();
            $cj->pair($anu_j);
            $cj->exec();
            $cr = New ComRekening();
            $cr->pair($anu_r);
            $cr->exec();
            $cac = New ComRekeningPembantuAntarcabang();
            $cac->pair($anu_ac);
            $cac->exec();

            // DC/PUSAT
            $anu_j = array(
                "comName" => "Jurnal",
                "loop" => array(
                    "1010060010" => $total_nilai,// piutang cabang
                    "2010050" => $total_nilai,// hutang ke pusat
                ),
                "static" => array(
                    "cabang_id" => "-1",
                    "jenis" => $jenisTr,
                    "transaksi_id" => $insertID,
                    "transaksi_no" => $insertNum,
                    "dtime" => date("Y-m-d H:i:s"),
                    "fulldate" => date("Y-m-d"),
                ),
            );
            $anu_r = array(
                "comName" => "Rekening",
                "loop" => array(
                    "1010060010" => $total_nilai,// piutang cabang
                    "2010050" => $total_nilai,// hutang ke pusat
                ),
                "static" => array(
                    "cabang_id" => "-1",
                    "jenis" => $jenisTr,
                    "transaksi_id" => $insertID,
                    "transaksi_no" => $insertNum,
                    "dtime" => date("Y-m-d H:i:s"),
                    "fulldate" => date("Y-m-d"),
                ),
            );
            $anu_ac = array(
                "comName" => "Rekening",
                "loop" => array(
                    "1010060010" => $total_nilai,// piutang cabang
                ),
                "static" => array(
                    "cabang_id" => "-1",
                    "jenis" => $jenisTr,
                    "extern_id" => $cabangID,
                    "extern_nama" => $cabangNama,
                    "transaksi_id" => $insertID,
                    "transaksi_no" => $insertNum,
                    "dtime" => date("Y-m-d H:i:s"),
                    "fulldate" => date("Y-m-d"),
                ),
            );
            $cj = New ComJurnal();
            $cj->pair($anu_j);
            $cj->exec();
            $cr = New ComRekening();
            $cr->pair($anu_r);
            $cr->exec();
            $cac = New ComRekeningPembantuAntarcabang();
            $cac->pair($anu_ac);
            $cac->exec();

            foreach ($arrDataDetail as $cus_id => $cusSpec) {
                // mengurangi um relasi so, penjualan tunai
                $anu_1 = array(
                    "comName" => "RekeningPembantuCustomer",
                    "loop" => array(
                        "2010050" => "-" . $cusSpec["harga"],// hutang ke konsumen
                    ),
                    "static" => array(
                        "cabang_id" => $cabangID,
                        "extern_id" => "2010050050",
                        "extern_nama" => "Uang Muka Konsumen Tanpa Ppn",
                        "jenis" => $jenisTr,
                        "transaksi_id" => $insertID,
                        "transaksi_no" => $insertNum,
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                    ),
                );
                $anu_2 = array(
                    "comName" => "RekeningPembantuCustomerDetail",
                    "loop" => array(
                        "2010050" => "-" . $cusSpec["harga"],// hutang ke konsumen
                    ),
                    "static" => array(
                        "cabang_id" => $cabangID,
                        "extern_id" => $cusSpec["id"],
                        "extern_nama" => $cusSpec["nama"],
                        "extern2_id" => "2010050050",
                        "extern2_nama" => "Uang Muka Konsumen Tanpa Ppn",
                        "jenis" => $jenisTr,
                        "transaksi_id" => $insertID,
                        "transaksi_no" => $insertNum,
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                    ),
                );
                $cm = New ComRekeningPembantuCustomer();
                $cm->pair($anu_1);
                $cm->exec();
                $cmd = New ComRekeningPembantuCustomerDetail();
                $cmd->pair($anu_2);
                $cmd->exec();

                // menambah um an konsumen
                $anu_3 = array(
                    "comName" => "RekeningPembantuCustomer",
                    "loop" => array(
                        "2010050" => $cusSpec["harga"],// hutang ke konsumen Uang Muka Konsumen Tanpa Ppn
                    ),
                    "static" => array(
                        "cabang_id" => "-1",
                        "extern_id" => "2010050050",// Uang Muka Konsumen Tanpa Ppn
                        "extern_nama" => "Uang Muka Konsumen Tanpa Ppn",
                        "jenis" => $jenisTr,
                        "transaksi_id" => $insertID,
                        "transaksi_no" => $insertNum,
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                    ),
                );
                $anu_4 = array(
                    "comName" => "RekeningPembantuCustomerDetail",
                    "loop" => array(
                        "2010050" => $cusSpec["harga"],// hutang ke konsumen Uang Muka Konsumen Tanpa Ppn
                    ),
                    "static" => array(
                        "cabang_id" => "-1",
                        "extern_id" => $cusSpec["id"],
                        "extern_nama" => $cusSpec["nama"],
                        "extern2_id" => "2010050050",// Uang Muka Konsumen Tanpa Ppn
                        "extern2_nama" => "Uang Muka Konsumen Tanpa Ppn",
                        "jenis" => $jenisTr,
                        "transaksi_id" => $insertID,
                        "transaksi_no" => $insertNum,
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                    ),
                );
                $cm = New ComRekeningPembantuCustomer();
                $cm->pair($anu_3);
                $cm->exec();
                $cmd = New ComRekeningPembantuCustomerDetail();
                $cmd->pair($anu_4);
                $cmd->exec();

                // tabel payment um source an konsumen
                $anu_5 = array(
                    "comName" => "PaymentUangMuka",
                    "loop" => array(
                        "2010050" => "-" . $cusSpec["harga"],// hutang ke konsumen
                    ),
                    "static" => array(
                        "cabang_id" => $cabangID,
                        "cabang_nama" => $cabangNama,
                        "gudang_id" => "0",
                        "transaksi_id" => "0",
                        "extern_id" => $cusSpec["id"],
                        "extern_nama" => $cusSpec["nama"],
                        "extern2_id" => "0",
                        "extern2_nama" => "",
                        "terbayar" => $cusSpec["harga"],
                        "label" => "uang muka konsumen",
                        "extern_label2" => "customer",
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                    ),
                );
                $anu_6 = array(
                    "comName" => "PaymentUangMukaCustomer",
                    "loop" => array(
                        "2010050" => "-" . $cusSpec["harga"],// hutang ke konsumen
                    ),
                    "static" => array(
                        "cabang_id" => $cabangID,
                        "cabang_nama" => $cabangNama,
                        "gudang_id" => "0",
                        "extern_id" => $cusSpec["id"],
                        "extern_nama" => $cusSpec["nama"],
                        "nilai" => $cusSpec["harga"],
                        "label" => "uang muka",
                        "extern_label2" => "customer",
                        "transaksi_id" => $insertID,
                        "transaksi_no" => $insertNum,
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                    ),
                );
                $anu_7 = array(
                    "comName" => "PaymentUangMuka",
                    "loop" => array(
                        "2010050" => $cusSpec["harga"],// hutang ke konsumen
                    ),
                    "static" => array(
                        "cabang_id" => "-1",
                        "cabang_nama" => "DC/PUSAT",
                        "gudang_id" => "0",
                        "transaksi_id" => "0",
                        "extern_id" => $cusSpec["id"],
                        "extern_nama" => $cusSpec["nama"],
                        "extern2_id" => "0",
                        "extern2_nama" => "",
                        "tambah" => $cusSpec["harga"],
                        "label" => "uang muka konsumen",
                        "extern_label2" => "customer",
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                    ),
                );

                $cp = New ComPaymentUangMuka();
                $cp->pair($anu_5);
                $cp->exec();
                $cpm = New ComPaymentUangMukaCustomer();
                $cpm->pair($anu_6);
                $cpm->exec();
                $cpn = New ComPaymentUangMuka();
                $cpn->pair($anu_7);
                $cpn->exec();

//                mati_disini(__LINE__ . " TESTING 1 KONSUMEN...");
            }
        }

        cekMerah(":: cek validate lajur di " . __FUNCTION__ . ", " . __FILE__);
        validateAllBalances($cabangID);


        mati_disini("---SETOP--- ADJUSTMENT BERHASIL..." . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");
    }

    public function cekDiskonSupplier()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlSupplierDiskon");
        $this->load->model("Mdls/MdlLockerStockDiskonVendor");
        $this->load->model("Coms/ComRekeningPembantuPiutangSupplierDetailTransItem");

        $tbl = "__rek_pembantu_subpiutangsuppliertrans__1010020030";
        $tbl1 = "_rek_pembantu_subpiutangsuppliertrans_cache";
        $tbl2 = "stock_locker_diskon";
        $jenis = "3333";
        $rekening = "1010020030";

        $this->db->select('*');
        $this->db->where("periode", "forever");
        $this->db->where("debet>", "0");
        $query = $this->db->get($tbl1)->result();
        showLast_query("biru");
        cekBiru(count($query));
        $grnIDs = array();
        $dataCache = array();
        if (sizeof($query) > 0) {
            foreach ($query as $spec) {
                $grnIDs[$spec->extern_id] = $spec->extern_id;
                $dataCache[$spec->extern3_id][$spec->extern_id][$spec->extern2_id] = $spec->debet;

            }
//            arrPrintCyan($dataCache);

            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $tr->setJointSelectFields("transaksi_id, main");
            $tr->addFilter("id in ('" . implode("','", $grnIDs) . "')");
            $trTmp = $tr->lookupDataRegistries_joined();
            showLast_query("kuning");
            arrPrint($trTmp);

        }
    }


    public function patchSOLunas()
    {
        $this->load->model("MdlTransaksi");
        $tabel = "transaksi";
        $tabel_src = "transaksi_payment_source";
        $tabel_status = "transaksi_status";
        $arrData = array();
        $tr = New MdlTransaksi();

        // penjualan tunai reguler
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->where($tabel . '.jenis', "5823so");
        $this->db->where($tabel . '.pembayaran_sys', 'cash');
        $this->db->where($tabel_src . '.sisa>', '100');
        $this->db->join($tabel_src, $tabel_src . ".transaksi_id=" . $tabel . ".id");
        $query = $this->db->get()->result();
        showLast_query("biru");
        cekBiru(count($query));
        if (sizeof($query) > 0) {
            foreach ($query as $spec) {
                $tr->setFilters(array());
                $tr->addFilter("transaksi_id=" . $spec->transaksi_id);
                $tr->setJointSelectFields("transaksi_id, main");
                $trreg = $tr->lookupDataRegistries()->result();
                $main = blobDecode($trreg[0]->main);
                $arrData[$spec->transaksi_id] = array(
                    "id_master" => $spec->id_master,
                    "jenis" => $spec->jenis,
                    "transaksi_id" => $spec->transaksi_id,
                    "nomer" => $spec->nomer,
                    "dtime" => $spec->dtime,
                    "fulldate" => $spec->fulldate,
                    "jenis_master" => $spec->jenis_master,
                    "oleh_id" => $spec->oleh_id,
                    "oleh_nama" => $spec->oleh_nama,
                    "customers_id" => $spec->customers_id,
                    "customers_nama" => $spec->customers_nama,
                    "cabang_id" => $spec->cabang_id,
                    "cabang_nama" => $spec->cabang_nama,
                    "status_id" => 10,
                    "status_nama" => "belum dibayar",
                    "transaksi_nilai" => $main["harga"],
                    "diskon_nilai" => $main["diskon_kategori_unit"],
                    "ppn_nilai" => $main["new_grand_ppn"],
                    "transaksi_net" => $main["grandTotal"],
                    "transaksi_dibayar" => 0,
                    "transaksi_reject" => 0,
                    "transaksi_fullfillment" => 0,
                    "transaksi_saldo" => $main["grandTotal"],
                    "transaksi_dikirim" => 0,
                );
            }
        }


        // penjualan tunai reguler
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->where($tabel . '.jenis', "5822so");
        $this->db->where($tabel . '.pembayaran_sys', 'cash');
        $this->db->where($tabel_src . '.sisa>', '100');
        $this->db->join($tabel_src, $tabel_src . ".transaksi_id=" . $tabel . ".id");
        $query = $this->db->get()->result();
        showLast_query("biru");
        cekBiru(count($query));
        if (sizeof($query) > 0) {
            foreach ($query as $spec) {
                $tr->setFilters(array());
                $tr->addFilter("transaksi_id=" . $spec->transaksi_id);
                $tr->setJointSelectFields("transaksi_id, main");
                $trreg = $tr->lookupDataRegistries()->result();
                $main = blobDecode($trreg[0]->main);
                $arrData[$spec->transaksi_id] = array(
                    "id_master" => $spec->id_master,
                    "jenis" => $spec->jenis,
                    "transaksi_id" => $spec->transaksi_id,
                    "nomer" => $spec->nomer,
                    "dtime" => $spec->dtime,
                    "fulldate" => $spec->fulldate,
                    "jenis_master" => $spec->jenis_master,
                    "oleh_id" => $spec->oleh_id,
                    "oleh_nama" => $spec->oleh_nama,
                    "customers_id" => $spec->customers_id,
                    "customers_nama" => $spec->customers_nama,
                    "cabang_id" => $spec->cabang_id,
                    "cabang_nama" => $spec->cabang_nama,
                    "status_id" => 10,
                    "status_nama" => "belum dibayar",
                    "transaksi_nilai" => $main["harga"],
                    "diskon_nilai" => $main["diskon_kategori_unit"],
                    "ppn_nilai" => $main["new_grand_ppn"],
                    "transaksi_net" => $main["grandTotal"],
                    "transaksi_dibayar" => 0,
                    "transaksi_reject" => 0,
                    "transaksi_fullfillment" => 0,
                    "transaksi_saldo" => $main["grandTotal"],
                    "transaksi_dikirim" => 0,
                );
            }
        }


        // penjualan credit
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->where($tabel . '.jenis', "5822spd");
        $this->db->where($tabel . '.pembayaran_sys !=', 'cash');
        $this->db->where($tabel_src . '.sisa>', '100');
        $this->db->join($tabel_src, $tabel_src . ".transaksi_id=" . $tabel . ".id");
        $query = $this->db->get()->result();
        showLast_query("kuning");
        cekKuning(count($query));
        if (sizeof($query) > 0) {
            foreach ($query as $spec) {
//                arrPrintKuning($spec);
                $ids_his = blobDecode($spec->ids_his);
                $trid_5822so = $ids_his[2]["trID"];
                $trid_5822spd = $spec->transaksi_id;

                if (!array_key_exists($trid_5822so, $arrData)) {
                    $tr->setFilters(array());
                    $tr->addFilter("id=" . $trid_5822so);
                    $trTmp = $tr->lookupAll()->result();
                    $specc = $trTmp[0];

                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id=" . $trid_5822so);
                    $tr->setJointSelectFields("transaksi_id, main");
                    $trreg = $tr->lookupDataRegistries()->result();
                    $main = blobDecode($trreg[0]->main);

                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id=" . $trid_5822spd);
                    $tr->setJointSelectFields("transaksi_id, main");
                    $trreg = $tr->lookupDataRegistries()->result();
                    $main_kirim = blobDecode($trreg[0]->main);

                    $arrData[$trid_5822so] = array(
                        "id_master" => $specc->id_master,
                        "jenis" => $specc->jenis,
                        "transaksi_id" => $trid_5822so,
                        "nomer" => $specc->nomer,
                        "dtime" => $specc->dtime,
                        "fulldate" => $specc->fulldate,
                        "jenis_master" => $specc->jenis_master,
                        "oleh_id" => $specc->oleh_id,
                        "oleh_nama" => $specc->oleh_nama,
                        "customers_id" => $specc->customers_id,
                        "customers_nama" => $specc->customers_nama,
                        "cabang_id" => $specc->cabang_id,
                        "cabang_nama" => $specc->cabang_nama,
                        "status_id" => 10,
                        "status_nama" => "belum dibayar",
                        "transaksi_nilai" => $main["harga"],
                        "diskon_nilai" => $main["diskon_kategori_unit"],
                        "ppn_nilai" => $main["new_grand_ppn"],
                        "transaksi_net" => $main["grandTotal"],
                        "transaksi_dibayar" => 0,
                        "transaksi_reject" => 0,
                        "transaksi_fullfillment" => 0,
                        "transaksi_saldo" => $main["grandTotal"],
                        "transaksi_dikirim" => $main_kirim["grandTotal"],
                    );
                }
                else {
                    cekMerah("SUDAH ADA DALAM ARRAY DATA...");
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id=" . $trid_5822spd);
                    $tr->setJointSelectFields("transaksi_id, main");
                    $trreg = $tr->lookupDataRegistries()->result();
                    $main_kirim = blobDecode($trreg[0]->main);

                    $kirim_sebelumnya = $arrData[$trid_5822so]["transaksi_dikirim"];
                    $kirim_new = $kirim_sebelumnya + $main_kirim["grandTotal"];
                    $arrData[$trid_5822so]["transaksi_dikirim"] = $kirim_new;
                }


//                break;
            }
        }


        // penjualan project
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->where($tabel . '.jenis', "588so");
//        $this->db->where($tabel . '.pembayaran_sys !=', 'cash');
//        $this->db->where($tabel_src . '.sisa>', '100');
//        $this->db->join($tabel_src, $tabel_src . ".transaksi_id=" . $tabel . ".id");
        $query = $this->db->get()->result();
        showLast_query("kuning");
        cekKuning(count($query));
        if (sizeof($query) > 0) {
            foreach ($query as $spec) {
//                arrPrintKuning($spec);
//                $ids_his = blobDecode($spec->ids_his);
//                $trid_5822so = $ids_his[2]["trID"];
//                $trid_5822spd = $spec->transaksi_id;

                if (!array_key_exists($spec->id, $arrData)) {
                    $specc = $spec;
//                    $tr->setFilters(array());
//                    $tr->addFilter("id=" . $trid_5822so);
//                    $trTmp = $tr->lookupAll()->result();
//                    $specc = $trTmp[0];
//
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id=" . $specc->id);
                    $tr->setJointSelectFields("transaksi_id, main");
                    $trreg = $tr->lookupDataRegistries()->result();
                    $main = blobDecode($trreg[0]->main);
//                    arrPrintWebs($main);
//
//                    $tr->setFilters(array());
//                    $tr->addFilter("transaksi_id=" . $trid_5822spd);
//                    $tr->setJointSelectFields("transaksi_id, main");
//                    $trreg = $tr->lookupDataRegistries()->result();
//                    $main_kirim = blobDecode($trreg[0]->main);

                    $arrData[$specc->id] = array(
                        "id_master" => $specc->id_master,
                        "jenis" => $specc->jenis,
                        "transaksi_id" => $specc->id,
                        "nomer" => $specc->nomer,
                        "dtime" => $specc->dtime,
                        "fulldate" => $specc->fulldate,
                        "jenis_master" => $specc->jenis_master,
                        "oleh_id" => $specc->oleh_id,
                        "oleh_nama" => $specc->oleh_nama,
                        "customers_id" => $specc->customers_id,
                        "customers_nama" => $specc->customers_nama,
                        "cabang_id" => $specc->cabang_id,
                        "cabang_nama" => $specc->cabang_nama,
                        "status_id" => 10,
                        "status_nama" => "belum dibayar",
                        "transaksi_nilai" => $main["harga_non_ppn"],
                        "diskon_nilai" => $main["diskon_kategori_unit"],
                        "ppn_nilai" => $main["new_grand_ppn"],
                        "transaksi_net" => ($main["harga_jual_project_nppn"] > 0) ? $main["harga_jual_project_nppn"] : $main["harga_non_ppn"] + $main["new_grand_ppn"],
                        "transaksi_dibayar" => 0,
                        "transaksi_reject" => 0,
                        "transaksi_fullfillment" => 0,
                        "transaksi_saldo" => ($main["harga_jual_project_nppn"] > 0) ? $main["harga_jual_project_nppn"] : $main["harga_non_ppn"] + $main["new_grand_ppn"],
                        "transaksi_dikirim" => 0,
                    );
                }
                else {
                    cekMerah("SUDAH ADA DALAM ARRAY DATA...");
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id=" . $specc->id);
                    $tr->setJointSelectFields("transaksi_id, main");
                    $trreg = $tr->lookupDataRegistries()->result();
                    $main_kirim = blobDecode($trreg[0]->main);

                    $kirim_sebelumnya = $arrData[$specc->id]["transaksi_dikirim"];
                    $kirim_new = $kirim_sebelumnya + $main_kirim["grandTotal"];
                    $arrData[$specc->id]["transaksi_dikirim"] = $kirim_new;
                }


//                break;
            }
        }


        $this->db->trans_start();


        arrPrintCyan($arrData);
        if (sizeof($arrData) > 0) {
            foreach ($arrData as $trid => $spec) {
                $anu = $this->db->insert($tabel_status, $spec);
                showLast_query("hijau");
            }
        }

//        mati_disini("---SETOP--- PATCH BERHASIL..." . __LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>DONE...</h3>");

    }

    public function patchPOLunas()
    {
        $this->load->model("MdlTransaksi");
        $tabel = "transaksi";
        $tabel_src = "transaksi_payment_source";
        $tabel_status = "transaksi_status";
        $arrData = array();
        $tr = New MdlTransaksi();

        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            // pembelian fg 466
            $this->db->select('*');
            $this->db->from($tabel_src);
            $this->db->where($tabel_src . '.jenis', "467");
            $this->db->where($tabel_src . '.sisa>', '100');
            $this->db->join($tabel, $tabel . ".id=" . $tabel_src . ".transaksi_id");
            $query = $this->db->get()->result();
            showLast_query("biru");
            cekBiru(count($query));
            if (sizeof($query) > 0) {
                foreach ($query as $spec) {
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id=" . $spec->transaksi_id);
                    $tr->setJointSelectFields("transaksi_id, main");
                    $trreg = $tr->lookupDataRegistries()->result();
                    $main = blobDecode($trreg[0]->main);
                    $arrData[$spec->transaksi_id] = array(
                        "id_master" => $spec->id_master,
                        "jenis" => $spec->jenis,
                        "transaksi_id" => $spec->transaksi_id,
                        "nomer" => $spec->nomer,
                        "dtime" => $spec->dtime,
                        "fulldate" => $spec->fulldate,
                        "jenis_master" => $spec->jenis_master,
                        "oleh_id" => $spec->oleh_id,
                        "oleh_nama" => $spec->oleh_nama,
                        "suppliers_id" => $spec->suppliers_id,
                        "suppliers_nama" => $spec->suppliers_nama,
                        "cabang_id" => $spec->cabang_id,
                        "cabang_nama" => $spec->cabang_nama,
                        "status_id" => 10,
                        "status_nama" => "belum dibayar",
                        "transaksi_nilai" => $main["harga"],
                        "diskon_nilai" => $main["diskon_kategori_unit"],
                        "ppn_nilai" => $main["ppn"],
                        "transaksi_net" => $main["grand_total"],
                        "transaksi_dibayar" => 0,
                        "transaksi_reject" => 0,
                        "transaksi_fullfillment" => 0,
                        "transaksi_saldo" => $main["grand_total"],
                        "transaksi_dikirim" => 0,
                    );
                }
            }
            arrPrint($arrData);
        }


        // pembelian fg
        $tr = new MdlTransaksi();
        $tr->addFilter("jenis_top='466r'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("valid_qty>0");
        $sesionReplacer = array(
            "cabang_id" => "-1",
            "gudang_id" => "-1",
        );
        $akses_target = array(
            "466", "467r", "467"
        );
        $tr->addFilter("next_step_code in ('" . implode("','", $akses_target) . "')");
        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
        showLast_query("biru");
        cekBiru(count($tmpHist));
        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $spec) {
                $idsHis = ($spec->ids_his != null) ? blobDecode($spec->ids_his) : array();
                $trid_po = $idsHis[2]["trID"];
                $trno_po = $idsHis[2]["nomer"];

                $tr->setFilters(array());
                $tr->addFilter("id=" . $trid_po);
                $trtmp = $tr->lookupAll()->result();
                $spec_po = $trtmp[0];

                $tr->setFilters(array());
                $tr->addFilter("transaksi_id=" . $trid_po);
                $tr->setJointSelectFields("transaksi_id, main");
                $trreg = $tr->lookupDataRegistries()->result();
                $main = blobDecode($trreg[0]->main);
                $arrData[$trid_po] = array(
                    "id_master" => $spec_po->id_master,
                    "jenis" => $spec_po->jenis,
                    "transaksi_id" => $trid_po,
                    "nomer" => $trno_po,
                    "dtime" => $spec_po->dtime,
                    "fulldate" => $spec_po->fulldate,
                    "jenis_master" => $spec_po->jenis_master,
                    "oleh_id" => $spec_po->oleh_id,
                    "oleh_nama" => $spec_po->oleh_nama,
                    "suppliers_id" => $spec_po->suppliers_id,
                    "suppliers_nama" => $spec_po->suppliers_nama,
                    "cabang_id" => $spec_po->cabang_id,
                    "cabang_nama" => $spec_po->cabang_nama,
                    "status_id" => 10,
                    "status_nama" => "belum dibayar",
                    "transaksi_nilai" => $main["harga"],
                    "diskon_nilai" => $main["diskon_kategori_unit"],
                    "ppn_nilai" => $main["ppn"],
                    "transaksi_net" => $main["grand_total"],
                    "transaksi_dibayar" => 0,
                    "transaksi_reject" => 0,
                    "transaksi_fullfillment" => 0,
                    "transaksi_saldo" => $main["grand_total"],
                    "transaksi_dikirim" => 0,
                );

            }
        }

        // pembelian supplies
        $tr = new MdlTransaksi();
        $tr->addFilter("jenis_top='461ro'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("valid_qty>0");
        $sesionReplacer = array(
            "cabang_id" => "-1",
            "gudang_id" => "-1",
        );
        $akses_target = array(
            "461r", "461",
        );
        $tr->addFilter("next_step_code in ('" . implode("','", $akses_target) . "')");
        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
        showLast_query("biru");
        cekBiru(count($tmpHist));
        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $spec) {
                $idsHis = ($spec->ids_his != null) ? blobDecode($spec->ids_his) : array();
                $trid_po = $idsHis[2]["trID"];
                $trno_po = $idsHis[2]["nomer"];
                if ($trid_po > 0) {

                    $tr->setFilters(array());
                    $tr->addFilter("id=" . $trid_po);
                    $trtmp = $tr->lookupAll()->result();
                    $spec_po = $trtmp[0];

                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id=" . $trid_po);
                    $tr->setJointSelectFields("transaksi_id, main");
                    $trreg = $tr->lookupDataRegistries()->result();
                    $main = blobDecode($trreg[0]->main);
                    $arrData[$trid_po] = array(
                        "id_master" => $spec_po->id_master,
                        "jenis" => $spec_po->jenis,
                        "transaksi_id" => $trid_po,
                        "nomer" => $trno_po,
                        "dtime" => $spec_po->dtime,
                        "fulldate" => $spec_po->fulldate,
                        "jenis_master" => $spec_po->jenis_master,
                        "oleh_id" => $spec_po->oleh_id,
                        "oleh_nama" => $spec_po->oleh_nama,
                        "suppliers_id" => $spec_po->suppliers_id,
                        "suppliers_nama" => $spec_po->suppliers_nama,
                        "cabang_id" => $spec_po->cabang_id,
                        "cabang_nama" => $spec_po->cabang_nama,
                        "status_id" => 10,
                        "status_nama" => "belum dibayar",
                        "transaksi_nilai" => $main["harga"],
                        "diskon_nilai" => $main["diskon_kategori_unit"],
                        "ppn_nilai" => $main["ppn"],
                        "transaksi_net" => $main["grand_total"],
                        "transaksi_dibayar" => 0,
                        "transaksi_reject" => 0,
                        "transaksi_fullfillment" => 0,
                        "transaksi_saldo" => $main["grand_total"],
                        "transaksi_dikirim" => 0,
                    );
                }

            }
        }

        // pembelian fg project
        $tr = new MdlTransaksi();
        $tr->addFilter("jenis_top='1466r'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("valid_qty>0");
        $sesionReplacer = array(
            "cabang_id" => "-1",
            "gudang_id" => "-1",
        );
        $akses_target = array(
            "1466", "1467r", "1467"
        );
        $tr->addFilter("next_step_code in ('" . implode("','", $akses_target) . "')");
        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
        showLast_query("biru");
        cekBiru(count($tmpHist));
        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $spec) {
                $idsHis = ($spec->ids_his != null) ? blobDecode($spec->ids_his) : array();
                $trid_po = $idsHis[2]["trID"];
                $trno_po = $idsHis[2]["nomer"];
                if ($trid_po > 0) {

                    $tr->setFilters(array());
                    $tr->addFilter("id=" . $trid_po);
                    $trtmp = $tr->lookupAll()->result();
                    $spec_po = $trtmp[0];

                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id=" . $trid_po);
                    $tr->setJointSelectFields("transaksi_id, main");
                    $trreg = $tr->lookupDataRegistries()->result();
                    $main = blobDecode($trreg[0]->main);
                    $arrData[$trid_po] = array(
                        "id_master" => $spec_po->id_master,
                        "jenis" => $spec_po->jenis,
                        "transaksi_id" => $trid_po,
                        "nomer" => $trno_po,
                        "dtime" => $spec_po->dtime,
                        "fulldate" => $spec_po->fulldate,
                        "jenis_master" => $spec_po->jenis_master,
                        "oleh_id" => $spec_po->oleh_id,
                        "oleh_nama" => $spec_po->oleh_nama,
                        "suppliers_id" => $spec_po->suppliers_id,
                        "suppliers_nama" => $spec_po->suppliers_nama,
                        "cabang_id" => $spec_po->cabang_id,
                        "cabang_nama" => $spec_po->cabang_nama,
                        "status_id" => 10,
                        "status_nama" => "belum dibayar",
                        "transaksi_nilai" => $main["harga"],
                        "diskon_nilai" => $main["diskon_kategori_unit"],
                        "ppn_nilai" => $main["ppn"],
                        "transaksi_net" => $main["grand_total"],
                        "transaksi_dibayar" => 0,
                        "transaksi_reject" => 0,
                        "transaksi_fullfillment" => 0,
                        "transaksi_saldo" => $main["grand_total"],
                        "transaksi_dikirim" => 0,
                    );
                }

            }
        }

        // pembelian service
        $tr = new MdlTransaksi();
        $tr->addFilter("jenis_top='463ro'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("valid_qty>0");
        $sesionReplacer = array(
            "cabang_id" => "-1",
            "gudang_id" => "-1",
        );
        $akses_target = array(
            "463o", "463",
        );
        $tr->addFilter("next_step_code in ('" . implode("','", $akses_target) . "')");
        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
        showLast_query("biru");
        cekBiru(count($tmpHist));
        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $spec) {
                $idsHis = ($spec->ids_his != null) ? blobDecode($spec->ids_his) : array();
                $trid_po = $idsHis[2]["trID"];
                $trno_po = $idsHis[2]["nomer"];
                if ($trid_po > 0) {

                    $tr->setFilters(array());
                    $tr->addFilter("id=" . $trid_po);
                    $trtmp = $tr->lookupAll()->result();
                    $spec_po = $trtmp[0];

                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id=" . $trid_po);
                    $tr->setJointSelectFields("transaksi_id, main");
                    $trreg = $tr->lookupDataRegistries()->result();
                    $main = blobDecode($trreg[0]->main);
                    $arrData[$trid_po] = array(
                        "id_master" => $spec_po->id_master,
                        "jenis" => $spec_po->jenis,
                        "transaksi_id" => $trid_po,
                        "nomer" => $trno_po,
                        "dtime" => $spec_po->dtime,
                        "fulldate" => $spec_po->fulldate,
                        "jenis_master" => $spec_po->jenis_master,
                        "oleh_id" => $spec_po->oleh_id,
                        "oleh_nama" => $spec_po->oleh_nama,
                        "suppliers_id" => $spec_po->suppliers_id,
                        "suppliers_nama" => $spec_po->suppliers_nama,
                        "cabang_id" => $spec_po->cabang_id,
                        "cabang_nama" => $spec_po->cabang_nama,
                        "status_id" => 10,
                        "status_nama" => "belum dibayar",
                        "transaksi_nilai" => $main["harga"],
                        "diskon_nilai" => $main["diskon_kategori_unit"],
                        "ppn_nilai" => $main["ppn"],
                        "transaksi_net" => $main["grand_total"],
                        "transaksi_dibayar" => 0,
                        "transaksi_reject" => 0,
                        "transaksi_fullfillment" => 0,
                        "transaksi_saldo" => $main["grand_total"],
                        "transaksi_dikirim" => 0,
                    );
                }

            }
        }

        // pembelian service project
        $tr = new MdlTransaksi();
        $tr->addFilter("jenis_top='3463ro'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("valid_qty>0");
        $sesionReplacer = array(
            "cabang_id" => "-1",
            "gudang_id" => "-1",
        );
        $akses_target = array(
            "3463o", "3463",
        );
        $tr->addFilter("next_step_code in ('" . implode("','", $akses_target) . "')");
        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
        showLast_query("biru");
        cekBiru(count($tmpHist));
        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $spec) {
                $idsHis = ($spec->ids_his != null) ? blobDecode($spec->ids_his) : array();
                $trid_po = $idsHis[2]["trID"];
                $trno_po = $idsHis[2]["nomer"];
                if ($trid_po > 0) {

                    $tr->setFilters(array());
                    $tr->addFilter("id=" . $trid_po);
                    $trtmp = $tr->lookupAll()->result();
                    $spec_po = $trtmp[0];

                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id=" . $trid_po);
                    $tr->setJointSelectFields("transaksi_id, main");
                    $trreg = $tr->lookupDataRegistries()->result();
                    $main = blobDecode($trreg[0]->main);
                    $arrData[$trid_po] = array(
                        "id_master" => $spec_po->id_master,
                        "jenis" => $spec_po->jenis,
                        "transaksi_id" => $trid_po,
                        "nomer" => $trno_po,
                        "dtime" => $spec_po->dtime,
                        "fulldate" => $spec_po->fulldate,
                        "jenis_master" => $spec_po->jenis_master,
                        "oleh_id" => $spec_po->oleh_id,
                        "oleh_nama" => $spec_po->oleh_nama,
                        "suppliers_id" => $spec_po->suppliers_id,
                        "suppliers_nama" => $spec_po->suppliers_nama,
                        "cabang_id" => $spec_po->cabang_id,
                        "cabang_nama" => $spec_po->cabang_nama,
                        "status_id" => 10,
                        "status_nama" => "belum dibayar",
                        "transaksi_nilai" => $main["harga"],
                        "diskon_nilai" => $main["diskon_kategori_unit"],
                        "ppn_nilai" => $main["ppn"],
                        "transaksi_net" => $main["grand_total"],
                        "transaksi_dibayar" => 0,
                        "transaksi_reject" => 0,
                        "transaksi_fullfillment" => 0,
                        "transaksi_saldo" => $main["grand_total"],
                        "transaksi_dikirim" => 0,
                    );
                }

            }
        }

        // payment source belum lunas
        $arrJenis = array("467", "461", "1467", "463", "1463", "3463");
        $this->db->select('*');
        $this->db->from($tabel_src);
        $this->db->where_in('jenis', $arrJenis);
        $this->db->where('sisa>', '100');
        $query = $this->db->get()->result();
        showLast_query("kuning");
        cekBiru(count($query));
        if (sizeof($query) > 0) {
            foreach ($query as $spec) {
                switch ($spec->jenis) {
                    case "463":
                    case "1463":
                    case "3463":
                        $trid_po = $spec->extern3_id;
                        $trno_po = $spec->extern3_nama;
                        break;
                    case "461":
                    case "467":
                    case "1467":
                        $trid_po = $spec->extern2_id;
                        $trno_po = $spec->extern2_nama;
                        break;
                }
                if ($trid_po > 0) {
                    if (!array_key_exists($trid_po, $arrData)) {
                        $tr->setFilters(array());
                        $tr->addFilter("id=" . $trid_po);
                        $trtmp = $tr->lookupAll()->result();
                        $spec_po = $trtmp[0];

                        $tr->setFilters(array());
                        $tr->addFilter("transaksi_id=" . $trid_po);
                        $tr->setJointSelectFields("transaksi_id, main");
                        $trreg = $tr->lookupDataRegistries()->result();
                        $main = blobDecode($trreg[0]->main);
                        $arrData[$trid_po] = array(
                            "id_master" => $spec_po->id_master,
                            "jenis" => $spec_po->jenis,
                            "transaksi_id" => $trid_po,
                            "nomer" => $trno_po,
                            "dtime" => $spec_po->dtime,
                            "fulldate" => $spec_po->fulldate,
                            "jenis_master" => $spec_po->jenis_master,
                            "oleh_id" => $spec_po->oleh_id,
                            "oleh_nama" => $spec_po->oleh_nama,
                            "suppliers_id" => $spec_po->suppliers_id,
                            "suppliers_nama" => $spec_po->suppliers_nama,
                            "cabang_id" => $spec_po->cabang_id,
                            "cabang_nama" => $spec_po->cabang_nama,
                            "status_id" => 10,
                            "status_nama" => "belum dibayar",
                            "transaksi_nilai" => $main["harga"],
                            "diskon_nilai" => $main["diskon_kategori_unit"],
                            "ppn_nilai" => $main["ppn"],
                            "transaksi_net" => $main["grand_total"],
                            "transaksi_dibayar" => 0,
                            "transaksi_reject" => 0,
                            "transaksi_fullfillment" => 0,
                            "transaksi_saldo" => $main["grand_total"],
                            "transaksi_dikirim" => 0,
                        );
                    }
                }
            }
        }

//        arrPrint($arrData);
//        mati_disini(__LINE__);


        $this->db->trans_start();


        arrPrintCyan($arrData);
        if (sizeof($arrData) > 0) {
            foreach ($arrData as $trid => $spec) {
                $anu = $this->db->insert($tabel_status, $spec);
                showLast_query("hijau");
            }
        }

//        mati_disini("---SETOP--- PATCH BERHASIL..." . __LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>DONE...</h3>");

    }

    public function migrasiUangMukaProject()
    {
        $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Migrasi Uang Muka Project</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
</head>
<body class="bg-light">
<div class="container-fluid mt-5 px-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Monitoring Migrasi Jurnal Balik Uang Muka (Started Project)</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="font-weight-bold">Filter Customer:</label>
                    <select id="filterCustomer" class="form-control">
                        <option value="">-- Semua Customer --</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold">Filter Status Jurnal:</label>
                    <select id="filterJurnal" class="form-control">
                        <option value="">-- Semua --</option>
                        <option value="Belum">Belum Jurnal (Semua)</option>
                        <option value="Sudah">Sudah Jurnal</option>
                        <option value="URGENT">🚨 Urgent (Belum Jurnal & DP Lunas)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold">Filter Status DP:</label>
                    <select id="filterDP" class="form-control">
                        <option value="">-- Semua --</option>
                        <option value="DP Lunas">DP Lunas</option>
                        <option value="Ada Sisa DP">Ada Sisa DP</option>
                        <option value="Tidak Ada DP">Tidak Ada DP</option>
                    </select>
                </div>
            </div>

            <table id="tabelMigrasi" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Extern ID</th>
                        <th>Nama Customer</th>
                        <th>Project ID</th>
                        <th>Nama Project</th>
                        <th>No Transaksi (ST)</th>
                        <th>Tanggal</th>
                        <th>Debet (Started)</th>
                        <th>Status Project</th>
                        <th>Status DP</th>
                        <th>Tagihan DP</th>
                        <th>Sisa DP</th>
                        <th>Sort Order</th>
                        <th>Status Jurnal Balik</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" style="text-align:right; font-weight:bold;">Total Debet:</th>
                        <th style="font-weight:bold;">0</th>
                        <th colspan="6"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    var table = $("#tabelMigrasi").DataTable({
        "ajax": "' . base_url("ToolCek/getMigrasiUangMukaProjectData") . '",
        "columns": [
            { "data": "extern_id" },
            { "data": "extern_nama" },
            { "data": "project_id" },
            { "data": "project_nama" },
            { "data": "transaksi_no" },
            { "data": "fulldate" },
            { "data": "debet" },
            { "data": "project_status" },
            { "data": "dp_status" },
            { "data": "dp_tagihan", render: $.fn.dataTable.render.number(\',\', \'.\', 0, \'\') },
            { "data": "dp_sisa", render: $.fn.dataTable.render.number(\',\', \'.\', 0, \'\') },
            { "data": "sort_order", "visible": false },
            { "data": "status", render: function(data, type, row) {
                if (data === "Sudah") {
                    return \'<span class="badge badge-success">Sudah</span> <small class="text-muted">\' + row.keterangan + \'</small>\';
                } else if (data === "Prioritas 1") {
                    return \'<span class="badge badge-danger">Belum</span> <span class="badge badge-danger text-white">🚨 URGENT (DP Lunas)</span>\';
                } else if (data === "Prioritas 2") {
                    return \'<span class="badge badge-danger">Belum</span> <span class="badge badge-warning text-dark">🔥 PRIORITAS (Sisa DP)</span>\';
                } else {
                    return \'<span class="badge badge-danger">Belum</span>\';
                }
            }}
        ],
        "order": [[11, "asc"], [5, "desc"]],
        "pageLength": 25,
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            
            var intVal = function ( i ) {
                return typeof i === "string" ?
                    i.replace(/[\$,]/g, "")*1 :
                    typeof i === "number" ?
                        i : 0;
            };
            
            // Total over all filtered pages
            var total = api
                .column( 6, { search: "applied" } )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            // Update footer
            $( api.column( 6 ).footer() ).html(
                $.fn.dataTable.render.number(\',\', \'.\', 0, \'\').display( total )
            );
        },
        "initComplete": function(settings, json) {
            var api = this.api();
            var select = $("#filterCustomer");
            
            // Ambil unique values dari Kolom indeks 1 (Nama Customer)
            api.column(1).data().unique().sort().each(function(d, j) {
                if (d) {
                    select.append(\'<option value="\' + d + \'">\' + d + \'</option>\');
                }
            });
        }
    });

    $("#filterCustomer").on("change", function() {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        // Kolom indeks 1 adalah Nama Customer. Pakai exact regex agar akurat
        table.column(1).search(val ? "^" + val + "$" : "", true, false).draw();
    });

    $("#filterJurnal").on("change", function() {
        var val = $(this).val();
        // Kolom indeks 12 adalah Status Jurnal Balik
        table.column(12).search(val).draw();
    });

    $("#filterDP").on("change", function() {
        var val = $(this).val();
        // Kolom indeks 8 adalah Status DP
        table.column(8).search(val).draw();
    });
});
</script>
</body>
</html>';
        echo $html;
    }

    public function getMigrasiUangMukaProjectData()
    {
        // 1. Ambil Semua Data DP dari transaksi_payment_source (key = project_id)
        $sqlDP = "SELECT project_id, tagihan, terbayar, sisa FROM `transaksi_payment_source` WHERE `project_id` > '0' AND `_key` = 'dp'";
        $dpRecords = $this->db->query($sqlDP)->result_array();
        $mapDP = array();
        foreach ($dpRecords as $dp) {
            $mapDP[$dp['project_id']] = $dp;
        }

        // 2. Ambil jembatan relasi dari project_produk (project_start_nomer = ST number -> id = project_id)
        $sqlPP = "SELECT id, project_start_nomer, status, trash, closing_status, nama FROM `project_produk` WHERE `project_start_nomer` != '' AND `project_start_nomer` IS NOT NULL";
        $ppRecords = $this->db->query($sqlPP)->result_array();
        $mapPP = array();
        foreach ($ppRecords as $pp) {
            $mapPP[$pp['project_start_nomer']] = $pp;
        }

        // 3. Ambil Data Project & Jurnal Uang Muka
        $sql = "SELECT extern_id, extern_nama, cabang_id, dtime, jenis, fulldate, transaksi_id, transaksi_no, debet, kredit, keterangan, extern2_nama
                FROM `__rek_pembantu_subcustomer__2010050`
                WHERE (jenis = 999 AND cabang_id > 0 AND extern2_nama LIKE '%Uang Muka%' AND extern_id IN(
                        SELECT extern_id FROM `__rek_pembantu_subcustomer__2010050` WHERE keterangan LIKE '%STARTED PROJECT%' AND cabang_id > 0 GROUP BY extern_id
                    ))
                OR (keterangan LIKE '%STARTED PROJECT%' AND cabang_id > 0)
                ORDER BY extern_id, dtime ASC";

        $query = $this->db->query($sql)->result_array();

        $projects = array();
        $journals = array();

        foreach ($query as $row) {
            if (strpos($row['keterangan'], 'STARTED PROJECT') !== false) {
                $projects[] = $row;
            } elseif ($row['jenis'] == 999 && strpos($row['extern2_nama'], 'Uang Muka') !== false) {
                $journals[] = $row;
            }
        }

        $finalData = array();
        foreach ($projects as $p) {
            $status = 'Belum';
            $jurnal_terkait = '-';

            // Cari apakah ada jurnal yang match (extern_id sama dan nominal ada toleransi pembulatan <= 100)
            foreach ($journals as $index => $j) {
                if ($j['extern_id'] == $p['extern_id']) {
                    if (abs($j['kredit'] - $p['debet']) <= 100 || abs($j['debet'] - $p['debet']) <= 100) {
                        $status = 'Sudah';
                        $jurnal_terkait = $j['transaksi_no'];
                        unset($journals[$index]);
                        break;
                    }
                }
            }

            // Cek status DP menggunakan jembatan project_produk
            $has_dp = false;
            $dp_sisa = 0;
            $dp_tagihan = 0;
            $dp_status_html = '<span class="badge badge-secondary">Tidak Ada DP</span>';
            $project_status_html = '<span class="badge badge-secondary">Unknown</span>';
            $project_id = '-';
            $project_nama = '-';

            $st_number = $p['transaksi_no'];
            if (isset($mapPP[$st_number])) {
                $pp = $mapPP[$st_number];
                $master_project_id = $pp['id'];

                $project_id = $master_project_id;
                $project_nama = $pp['nama'];

                // Set Project Status
                if ($pp['trash'] == 1) {
                    $project_status_html = '<span class="badge badge-danger">Canceled/Dihapus</span>';
                } elseif ($pp['closing_status'] == 1) {
                    $project_status_html = '<span class="badge badge-success">Sudah Closing</span>';
                } else {
                    $project_status_html = '<span class="badge badge-primary">Aktif (Belum Closing)</span>';
                }

                if (isset($mapDP[$master_project_id])) {
                    $has_dp = true;
                    $dp_sisa = $mapDP[$master_project_id]['sisa'];
                    $dp_tagihan = $mapDP[$master_project_id]['tagihan'];
                }
            }

            if ($has_dp) {
                if ($dp_sisa < 1000) {
                    $dp_status_html = '<span class="badge badge-success">DP Lunas</span>';
                } else {
                    $dp_status_html = '<span class="badge badge-warning text-dark">Ada Sisa DP</span>';
                }
            }

            if ($status === 'Sudah') {
                $sort_order = 4; // Paling bawah
                $keterangan = 'Jurnal: ' . $jurnal_terkait;
            } else {
                if ($has_dp && $dp_sisa < 1000) {
                    $status = 'Prioritas 1';
                    $sort_order = 1; // URGENT (Paling atas)
                    $keterangan = 'Belum Dijurnal - DP Lunas';
                } elseif ($has_dp && $dp_sisa >= 1000) {
                    $status = 'Prioritas 2';
                    $sort_order = 2; // PRIORITAS
                    $keterangan = 'Belum Dijurnal - Ada Sisa DP';
                } else {
                    $status = 'Belum';
                    $sort_order = 3; // Biasa
                    $keterangan = 'Belum Dijurnal';
                }
            }

            $p['project_id'] = $project_id;
            $p['project_nama'] = $project_nama;
            $p['status'] = $status;
            $p['project_status'] = $project_status_html;
            $p['dp_status'] = $dp_status_html;
            $p['dp_tagihan'] = (float)$dp_tagihan;
            $p['dp_sisa'] = (float)$dp_sisa;
            $p['keterangan'] = $keterangan;
            $p['sort_order'] = $sort_order;
            $finalData[] = $p;
        }

        $output = array("data" => $finalData);
        echo json_encode($output);
    }

    public function debugData()
    {
        $sql1 = "SELECT * FROM `__rek_pembantu_subcustomer__2010050` WHERE keterangan LIKE '%STARTED PROJECT%' LIMIT 3";
        $res1 = $this->db->query($sql1)->result_array();

        $sql2 = "SELECT * FROM `transaksi_payment_source` WHERE project_id > 0 LIMIT 3";
        $res2 = $this->db->query($sql2)->result_array();

        echo "<pre>";
        echo "=== STARTED PROJECT ===\n";
        print_r($res1);
        echo "\n=== PAYMENT SOURCE ===\n";
        print_r($res2);
        echo "</pre>";
    }

    public function analyzeLunasBelumJurnal()
    {
        $sqlPayment = "SELECT nomer, tagihan, terbayar, sisa FROM `transaksi_payment_source` WHERE `project_id` > '0' AND `sisa` < '1000' AND `_key` = 'dp' ORDER BY sisa DESC";
        $payments = $this->db->query($sqlPayment)->result_array();

        $sqlProjects = "SELECT extern_id, extern_nama, dtime, transaksi_id, transaksi_no, debet, kredit, keterangan
                        FROM `__rek_pembantu_subcustomer__2010050`
                        WHERE keterangan LIKE '%STARTED PROJECT%' AND cabang_id > 0";
        $projects = $this->db->query($sqlProjects)->result_array();

        $sqlJournals = "SELECT extern_id, debet, kredit, transaksi_no
                        FROM `__rek_pembantu_subcustomer__2010050`
                        WHERE jenis = 999 AND cabang_id > 0 AND extern2_nama LIKE '%Uang Muka%'";
        $journals = $this->db->query($sqlJournals)->result_array();

        echo "<pre>";
        echo "=== ANALISIS DP LUNAS TAPI BELUM DIJURNAL BALIK ===\n\n";

        $countMatchBelumJurnal = 0;

        foreach ($payments as $pay) {
            $nomerSO = $pay['nomer'];
            $tagihan = $pay['tagihan'];

            foreach ($projects as $proj) {
                if (!empty($nomerSO) && strpos($proj['keterangan'], $nomerSO) !== false) {

                    // Cek apakah sudah dijurnal balik?
                    $isJurnalBalik = false;
                    foreach ($journals as $j) {
                        if ($j['extern_id'] == $proj['extern_id']) {
                            if ($j['kredit'] == $proj['debet'] || $j['debet'] == $proj['debet']) {
                                $isJurnalBalik = true;
                                break;
                            }
                        }
                    }

                    if (!$isJurnalBalik) {
                        $countMatchBelumJurnal++;
                        echo "Ditemukan Project: " . $proj['keterangan'] . "\n";
                        echo "- Customer: " . $proj['extern_nama'] . "\n";
                        echo "- Debet Started Project : " . number_format($proj['debet'], 2) . "\n";
                        echo "- Tagihan Payment Source: " . number_format($pay['tagihan'], 2) . "\n";
                        if ($proj['debet'] == $pay['tagihan']) {
                            echo ">> STATUS NILAI: COCOK (IDENTIK) ✅\n";
                        } else {
                            echo ">> STATUS NILAI: TIDAK COCOK ❌ (Selisih: ".number_format(abs($proj['debet'] - $pay['tagihan']), 2).")\n";
                        }
                        echo "--------------------------------------------------------\n";
                    }
                }
            }
        }

        echo "\nTotal DP Lunas (Sisa < 1000) yang BELUM dijurnal balik: $countMatchBelumJurnal data.\n";
        echo "</pre>";
    }

    public function checkProjectProduk()
    {
        echo "=== CHECKING project_produk ===\n";

        $sql = "SELECT * FROM `project_produk` LIMIT 1";
        $res = $this->db->query($sql)->row_array();
        echo "COLUMNS:\n";
        print_r(array_keys((array)$res));

        echo "\n\n=== SAMPLE DATA ===\n";
        print_r($res);

        // Let's take a sample 'st' number from our ledger and see if it's in project_produk
        $sql2 = "SELECT transaksi_no FROM `__rek_pembantu_subcustomer__2010050` WHERE keterangan LIKE '%STARTED PROJECT%' LIMIT 5";
        $samples = $this->db->query($sql2)->result_array();

        echo "\n\n=== CHECKING SAMPLES ===\n";
        foreach($samples as $s) {
            $st_no = $s['transaksi_no'];
            echo "Checking $st_no... \n";

            $found = false;
            foreach(array_keys((array)$res) as $col) {
                // escape col
                $check = $this->db->query("SELECT * FROM `project_produk` WHERE `$col` = ?", array($st_no))->row_array();
                if ($check) {
                    echo "FOUND in column '$col'! project_id = " . (isset($check['project_id']) ? $check['project_id'] : 'UNKNOWN') . "\n";
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                echo "NOT FOUND exactly. \n";
            }
        }
    }

    public function debugJurnal1478()
    {
        echo "<pre>";
        $sql = "SELECT extern_id, extern_nama, cabang_id, dtime, jenis, fulldate, transaksi_id, transaksi_no, debet, kredit, keterangan, extern2_nama
                FROM `__rek_pembantu_subcustomer__2010050`
                WHERE extern_id = '1478'";

        $query = $this->db->query($sql)->result_array();

        $projects = array();
        $journals = array();

        foreach ($query as $row) {
            if (strpos($row['keterangan'], 'STARTED PROJECT') !== false) {
                $projects[] = $row;
            } elseif ($row['jenis'] == 999 && strpos($row['extern2_nama'], 'Uang Muka') !== false) {
                $journals[] = $row;
            }
        }

        echo "=== PROJECTS (STARTED PROJECT) ===\n";
        print_r($projects);

        echo "\n=== JOURNALS (JENIS 999 & Uang Muka) ===\n";
        print_r($journals);

        echo "</pre>";
    }

}
