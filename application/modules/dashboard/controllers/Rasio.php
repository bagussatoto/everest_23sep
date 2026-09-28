<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 03/04/2019
 * Time: 13.50
 */

class Rasio extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
        // validateUserSession($this->session->login['id']);
        $this->load->library("SmtpMailer");
    }

    public function viewRekening()
    {
        $show_query = 0;
        /*ngambil data neraca lajur pusat*/
        // $this->load->model("Coms/ComRekening");
        // $r = new ComRekening();
        $tmp = array();

        /*PENJUALAN*/
        //----------------
        $prev_thn = $thn_lalu = previousYear();
        $tahun[] = $thn_lalu;
        //cekHere("tahun: $prev_thn");
        $this->load->model("Coms/ComRekening");
        $cr = new ComRekening();
        /*piutang tahun lalu*/
        //region Description
        $koloms = array(
            "thn",
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            "thn"      => previousYear(),
            // "bln"     => dtimeNow('m'),
            "periode"  => "tahunan",
            "rekening" => "1010020010"
        );
        $this->db->where($condites);
        $conditeins = array(
            "penjualan",
            "return penjualan",
        );
        // $this->db->where_in("rekening", $conditeins);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->row();
        //endregion
        $piutang_dagang_thn_lalu = $src_datas->debet;
        // showLast_query("biru");
        // arrPrint($src_datas);

        /*piutang thn sebelum -1 tahun ini*/
        //region Description
        $koloms = array(
            "thn",
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            "thn"      => previousYear() - 1,
            // "bln"     => dtimeNow('m'),
            "periode"  => "tahunan",
            "rekening" => "1010020010"
        );
        $this->db->where($condites);

        // $this->db->where_in("rekening", $conditeins);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->row();
        //endregion
        $piutang_dagang_sebelum_thn_lalu = $src_datas->debet;
        // showLast_query("biru");
        // arrPrint($src_datas);

        $avg_piutang_dagang = ($piutang_dagang_sebelum_thn_lalu + $piutang_dagang_thn_lalu) / 2;
        // cekHijau("($avg_piutang_dangang = ($piutang_dagang_sebelum_thn_lalu + $piutang_dagang_thn_lalu) / 2 (avg piutang tahun " . previousYear() . ") <br>avg_piutang = (piutang_thn_lalu + piutang_sebelum_thn_lalu) / 2");

        //region persediaan produk
        $conditeins = array(
            // "persediaan produk",
            // "persediaan produk rakitan",
            // "persediaan supplies",
            // "persediaan supplies proses",
            "1010030010",
            "1010030020",
            "1010030030",
            "1010030040",
            "1010030050",
            "1010030060",
            "1010030070",
        );
        $src_datas = $this->sum_rek_master_cache_thn($prev_thn, $conditeins);
        // showLast_query("biru");
        // arrPrint($src_datas);
        //endregion
        // ini persediaan masih dengan ppv, dibagi 1.25 menjadi persediaan riil (2022-10-08 dari pak tandori)
        $persediaan = ($src_datas->debet / 1.25);

        //region penjualan dan return penjualan termasuk projek

        $conditeins = array(
            // "penjualan",
            // "return penjualan",
            // "penjualan projek",
            "4010",
        );
        $src_datas = $this->sum_rek_master_cache_thn($prev_thn, $conditeins);
        // showLast_query("hijau");
        // arrPrint($src_datas);
        //endregion
        $penjualan = $src_datas->kredit;
        $return_penjualan = $src_datas->debet;
        $netto_penjualan = $penjualan - $return_penjualan;

        $inventory_turn_over = $netto_penjualan / $persediaan;
        $perputaran_piutang = $netto_penjualan / $avg_piutang_dagang;
        // cekHijau("$inventory_turn_over = $netto_penjualan / $persediaan ||$perputaran_piutang = $netto_penjualan / $avg_piutang_dagang (perputaran) <br>perputaran_piutang = penjualan / avg_piutang");
        $conditeins = array(
            "2010010",
        );
        $src_datas = $this->sum_rek_master_cache_thn($prev_thn, $conditeins);
        if($show_query == 1){
            showLast_query("kuning");
            arrPrint($src_datas);
        }
        $hutang_prev_thn = $src_datas->kredit;
        $src_datas = $this->sum_rek_master_cache_thn($prev_thn - 1, $conditeins);
        if($show_query == 1){
            showLast_query("kuning");
            arrPrint($src_datas);
        }
        $hutang_sebelum_prev_thn = $src_datas->kredit;

        $avg_hutang_akhir = ($hutang_sebelum_prev_thn + $hutang_prev_thn) / 2;

        //region hutang dagang prev

        $src_datas = $this->sum_rek_hutang_dagang($prev_thn);
        if($show_query == 1){
            showLast_query("pink");
            arrPrint($src_datas);
        }
        $hutang_dagang = $src_datas->kredit;
        $src_datas = $this->sum_rek_hutang_biaya($prev_thn);
        if($show_query == 1){
            showLast_query("pink");
            arrPrint($src_datas);
        }
        $hutang_biaya = $src_datas->kredit;
        $src_datas = $this->sum_rek_hutang_aktiva_tetap($prev_thn);
        if($show_query == 1){
            showLast_query("pink");
            arrPrint($src_datas);
        }
        $hutang_aktiva_tetap = $src_datas->kredit;
        //endregion
        $src_datas = $this->sum_rek_master_persediaan($prev_thn);
        if($show_query == 1){
            showLast_query("orange");
            arrPrint($src_datas);
        }
        $pembelian_produk = $src_datas->debet;

        $src_datas = $this->sum_rek_master_persediaan_supplies($prev_thn);
        if($show_query == 1){
            showLast_query("orange");
            arrPrint($src_datas);
        }
        $pembelian_supplies = $src_datas->debet;
        $src_datas = $this->sum_rek_master_persediaan_rakitan($prev_thn);
        if($show_query == 1){
            showLast_query("orange");
            arrPrint($src_datas);
        }
        $pembelian_rakitan = $src_datas->debet;
        $pembelian_bruto_thn_lalu = ($pembelian_produk + $pembelian_supplies + $pembelian_rakitan) / 1.25;
        // $pembelian_bruto_thn_lalu = $hutang_biaya + $hutang_dagang + $hutang_aktiva_tetap;

        $src_datas = $this->sum_rek_piutang_pembelian($prev_thn);
        // showLast_query("pink");
        // arrPrint($src_datas);
        $pembelian_return_thn_lalu = $src_datas->debet;

        $pembelian_thn_lalu = $pembelian_bruto_thn_lalu - $pembelian_return_thn_lalu;

        //region hutang dagang -1 (tidak dipakai)
        // $conditeins = array(
        //     "hutang dagang",
        // );
        // $src_datas = $this->sum_rek_hutang_dagang($prev_thn - 1);
        // showLast_query("kuning");
        // arrPrint($src_datas);
        //endregion
        // $hutang_dagang_sebelum_thn_lalu = $src_datas->debet - $src_datas->kredit;
        // $hutang_dagang_sebelum_thn_lalu = $src_datas->kredit;
        // $avg_hutang_dagang = ($hutang_dagang_sebelum_thn_lalu + $pembelian_thn_lalu) / 2;

        // $perputaran_hutang = $pembelian_bruto_thn_lalu / $avg_hutang_akhir * 100;
        // $perputaran_hutang = $pembelian_thn_lalu / $avg_hutang_akhir * 100;
        $perputaran_hutang = $pembelian_thn_lalu / $avg_hutang_akhir;

        $umur_piutang = 365 / $perputaran_piutang;
        // cekHijau("$avg_pengumpulan = 365 / $perputaran_piutang; (umur piutang)  <br>umur piutang = jml_hari(365) / perputaran_piutang");
        $cekDatas = array(
            "avg_piutang_dagang = (piutang_dagang_sebelum_thn_lalu + piutang_dagang_thn_lalu) / 2" => "$avg_piutang_dagang = ($piutang_dagang_sebelum_thn_lalu + $piutang_dagang_thn_lalu) / 2;",
            "perputaran_piutang = netto_penjualan / avg_piutang_dagang"                            => "$perputaran_piutang = $netto_penjualan / $avg_piutang_dagang;",
            "umur_piutang = 365 / perputaran_piutang"                                              => "$umur_piutang = 365 / $perputaran_piutang",
            "avg_hutang_akhir = (hutang_sebelum_prev_thn + hutang_prev_thn) / 2;"                  => "$avg_hutang_akhir = ($hutang_sebelum_prev_thn + $hutang_prev_thn) / 2",
            "pembelian_thn_lalu = pembelian_bruto_thn_lalu - pembelian_return_thn_lalu"            => "$pembelian_thn_lalu = $pembelian_bruto_thn_lalu - $pembelian_return_thn_lalu",
            "perputaran_hutang = pembelian_thn_lalu / avg_hutang_akhir"                            => "$perputaran_hutang = $pembelian_thn_lalu / $avg_hutang_akhir",
            // "avg_hutang_dagang = (hutang_dagang_sebelum_thn_lalu + hutang_dagang_thn_lalu) / 2;"   => "$avg_hutang_dagang = ($hutang_dagang_sebelum_thn_lalu + $pembelian_thn_lalu) / 2",
            "inventory_turn_over = netto_penjualan / persediaan;"                                  => "$inventory_turn_over = $netto_penjualan / $persediaan",
        );
        // cekBiru($this->viewCek($cekDatas));

        $boards = array(
            "1" => array(
                "rekening" => "avg_piutang_dagang",
                // "rekening_posisi" => "debet",
                // "nilai"      => $kas,
                "label"    => "avg piutang dagang " . previousYear(),
                "icon"     => "fa-money",
                "bg"       => "bg-green",
                // "link"            => "Neraca/viewBalanceSheet",
                // "link_label"      => "Balance Sheet",
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
            "2" => array(
                "rekening" => "perputaran_piutang",
                // "rekening_posisi" => "kredit",
                // "nilai"      => $hutangKepusat,
                "label"    => "perputaran piutang $prev_thn",
                "satuan"   => "kali",
                "icon"     => "fa-recycle",
                // "bg"              => "bg-teal",
                "bg"       => "bg-fuchsia",
                // "link"            => "Rugilaba/viewPLConsolidated/mtd?gr=czo1MToibGFwb3JhbmtldWFuZ2Fua29uc29saWRhc2lpbnRlcm5hbC1ybGNvbmludGVybmFsbXRkIjs=",
                // "link_label"      => "MTD (CONSOLIDATED)",
            ),
            "3" => array(
                "rekening" => "umur_piutang",
                // "rekening_posisi" => "debet",
                // "nilai"      => $hutangKepusat,
                "label"    => "umur piutang $prev_thn",
                "satuan"   => "hari",
                "icon"     => "fa-sun-o",
                "bg"       => "bg-red",
                // "link"            => "Neraca/viewBalanceSheet",
                // "link_label"      => "Balance Sheet",
            ),
            "6" => array(
                "rekening" => "avg_hutang_akhir",
                // "rekening_posisi" => "debet",
                // "nilai"      => $hutangKepusat,
                "label"    => "avg hutang $prev_thn",
                // "satuan"   => "kali",
                "icon"     => "fa-money",
                "bg"       => "bg-purple",
                // "link"            => "Neraca/viewBalanceSheet",
                // "link_label"      => "Balance Sheet",
            ),
            "4" => array(
                "rekening" => "perputaran_hutang",
                // "rekening_posisi" => "debet",
                // "nilai"      => $hutangKepusat,
                "label"    => "perputaran hutang dagang $prev_thn",
                "satuan"   => "kali",
                "icon"     => "fa-recycle",
                "bg"       => "bg-orange",
                // "link"            => "Neraca/viewBalanceSheet",
                // "link_label"      => "Balance Sheet",
            ),
            "5" => array(
                "rekening" => "inventory_turn_over",
                // "rekening_posisi" => "debet",
                // "nilai"      => $hutangKepusat,
                "label"    => "perputaran persediaan $prev_thn",
                "satuan"   => "kali",
                "icon"     => "fa-recycle",
                "bg"       => "bg-maroon-active",
                // "link"            => "Neraca/viewBalanceSheet",
                // "link_label"      => "Balance Sheet",
            ),

        );

        $var = "";
        $tmps = array();
        $tmps["avg_piutang_dagang"] = $avg_piutang_dagang;
        $tmps["perputaran_piutang"] = $perputaran_piutang;
        $tmps["umur_piutang"] = $umur_piutang;
        $tmps["perputaran_hutang"] = $perputaran_hutang;
        $tmps["inventory_turn_over"] = $inventory_turn_over;
        $tmps["avg_hutang_akhir"] = $avg_hutang_akhir;

        $src_bulanan = $this->rekeningBulanan();
        // arrPrintPink($src_bulanan);
        $tmp_bulanans = $src_bulanan["board_bulanan"];

        //----- ini key yang perlu dengan desimal
        $arrDesimalView = array(
            "perputaran_piutang",
            "perputaran_hutang",
            "inventory_turn_over",
        );
        //-----

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
            $nilai_f = in_array($board["rekening"], $arrDesimalView) ? number_format($nilai, 2, ",", ".") : formatField2("debet", $nilai);
            $label = $board["label"];
            $box_warna = $board["bg"];
            $link_lager = isset($board["link"]) ? $board["link"] : "";
            $link_label = isset($board["link_label"]) ? $board["link_label"] : "";
            $fa_icon = $board["icon"];
            $fa_icon_link = isset($board["link_label"]) ? "fa-hand-o-right" : "";
            $satuan = isset($board["satuan"]) ? $board["satuan"] : "";
            $lebar_col = 12 / $jml;
            $lebar = $lebar_col > 4 ? "4" : $lebar_col;
            $var .= "<div class='col-lg-$lebar col-xs-6'>
                            <div class='small-box $box_warna'>
                                <div class='inner'>
                                    <h3 style='font-size: 1.5em;'>$nilai_f <small>$satuan</small></h3>     
                                    <p class='text-uppercase' style='font-size: .9em;'>$label</p>
                                </div>
                                <div class='icon'>
                                    <i class='fa $fa_icon ' style='font-size: .5em;' aria-hidden='true'></i>
                                </div>
                                <a href='$link_lager' class='small-box-footer' target='_blank'>$link_label <i class='fa $fa_icon_link'></i></a>
                            </div>
                        </div>";
        }
        // bulanan
        $tmps = $src_bulanan["board_bulanan"];
        $board_bulanans = $src_bulanan["board"];
        foreach ($board_bulanans as $board) {

            $nilai = $tmps[$board["rekening"]];
            // $nilai = $board["nilai"];
            $nilai_f = formatField2("debet", $nilai);
            $label = $board["label"];
            $box_warna = $board["bg"];
            $link_lager = isset($board["link"]) ? $board["link"] : "";
            $link_label = isset($board["link_label"]) ? $board["link_label"] : "";
            $fa_icon = $board["icon"];
            $satuan = isset($board["satuan"]) ? $board["satuan"] : "";
            $lebar_col = 12 / $jml;
            $lebar = $lebar_col > 4 ? "4" : $lebar_col;
            // $var .= "<div class='col-lg-$lebar col-xs-6'>
            //                 <div class='small-box $box_warna'>
            //                     <div class='inner'>
            //                         <h3 style='font-size: 1.5em;'>$nilai_f <small>$satuan</small></h3>
            //                         <p class='text-uppercase' style='font-size: .9em;'>$label</p>
            //                     </div>
            //                     <div class='icon'>
            //                         <i class='fa $fa_icon ' style='font-size: .5em;' aria-hidden='true'></i>
            //                     </div>
            //                     <a href='$link_lager' class='small-box-footer' target='_blank'>$link_label <i class='fa fa-hand-o-right'></i></a>
            //                 </div>
            //             </div>";
        }

        echo $var;
        // echo $var;

        // arrPrintHijau($var);

        // $data = array(
        //     "mode"                       => "viewRekening",
        //     "title"                      => "Rasio",
        //     "subTitle"                   => "",
        //     "content"                    => $var,
        //     "penjualan_now_label"        => dtimeNow('Y'),
        //     "penjualan_past_label"       => $thn_lalu,
        //     "target_penjualan_now_label" => "'Predictive results'",
        //     "target_tahun_now_label"     => "'Target'",
        //     // "persen_now"                 => $persen_penjualan_now_f,
        //     // "target_persen"              => $target_persen,
        //     "periode"                    => '',
        //     "indeks"                     => '',
        //     "buffer"                     => '',
        //     "leadTime"                   => '',
        //     "navigasi"                   => '',
        //     "navigasiAttr"               => '',
        //     "biayaBulanan"               => '',
        //     "hppBulanan"                 => '',
        //     "pembelianBulanan"           => '',
        //     "penjualanBulanan"           => '',
        //     "penjualanFireBulanan"       => '',
        //     "penjualanNonFireBulanan"    => '',
        //     "dataQuarter"                => '',
        //     "penjualanQuarter"           => '',
        //     "add_link"                   => "",
        // );
        // $this->load->view("rasio", $data);
    }

    public function rekeningBulanan()
    {
        $prev_thn = formatTanggal(previousMonth(), "Y");
        $prev_bln = formatTanggal(previousMonth(), "m");
        //region return penjualan
        $koloms = array(
            "thn",
            "bln",
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            "thn"     => $prev_thn,
            "bln"     => $prev_bln,
            "periode" => "bulanan",
            // "rekening" => "return penjualan"
        );
        $this->db->where($condites);
        $conditeins = array(
            "penjualan",
            "return penjualan",
        );
        $this->db->where_in("rekening", $conditeins);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->row();
        // showLast_query("kuning");
        // arrPrint($src_datas);
        //endregion
        $penjualan = $src_datas->kredit - $src_datas->debet;

        //region piutang
        $koloms = array(
            "thn",
            "bln",
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            "thn"     => $prev_thn,
            "bln"     => $prev_bln,
            "periode" => "bulanan",
            // "rekening" => "return penjualan"
        );
        $this->db->where($condites);
        $conditeins = array(
            // "penjualan",
            "piutang dagang",
        );
        $this->db->where_in("rekening", $conditeins);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->row();
        // showLast_query("kuning");
        // arrPrint($src_datas);
        //endregion
        $piutang_bln_lalu = $src_datas->debet;

        //region piutang sebelum bln lalu
        $sebelum_bln_lalu = previousMonth("$prev_thn-$prev_bln");
        // cekKuning("$sebelum_bln_lalu");
        $koloms = array(
            "thn",
            "bln",
            "rekening",
            "rek_id",
            "sum(kredit) as kredit",
            "sum(debet) as debet",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            "thn"     => formatTanggal($sebelum_bln_lalu, "Y"),
            "bln"     => formatTanggal($sebelum_bln_lalu, "m"),
            "periode" => "bulanan",
            // "rekening" => "return penjualan"
        );
        $this->db->where($condites);
        $conditeins = array(
            // "penjualan",
            "piutang dagang",
        );
        $this->db->where_in("rekening", $conditeins);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->row();
        // showLast_query("kuning");
        // arrPrint($src_datas);
        //endregion
        $piutang_sebelum_bln_lalu = $src_datas->debet;
        $jml_hari = formatTanggal(previousMonth(), "t");
        $avg_piutang = ($piutang_bln_lalu + $piutang_sebelum_bln_lalu) / 2;
        $perputaran_piutang = $penjualan / $avg_piutang;
        $avg_pengumpulan = $jml_hari / $perputaran_piutang;
        // cekMerah("$avg_piutang = ($piutang_bln_lalu + $piutang_sebelum_bln_lalu) / 2; (avg piutang bulan $prev_bln) <br>avg_piutang = (piutang_bln_lalu + piutang_sebelum_bln_lalu) / 2");
        // cekMerah("$perputaran_piutang = $penjualan / $avg_piutang (perputaran) <br>perputaran_piutang = penjualan / avg_piutang");
        // cekMerah("$avg_pengumpulan = $jml_hari / $perputaran_piutang; (umur piutang) <br>umur piutang = jml_hari($jml_hari) / perputaran_piutang");

        $rows["piutang"]["$prev_thn-$prev_bln"] = $piutang_bln_lalu;
        $rows["piutang"]["$sebelum_bln_lalu"] = $piutang_sebelum_bln_lalu;
        $rows["penjualan"]["$prev_thn-$prev_bln"] = $penjualan;

        $tmps = array();
        $tmps["avg_piutang_dagang"] = $avg_piutang;
        $tmps["perputaran_piutang"] = $perputaran_piutang;
        $tmps["avg_pengumpulan"] = $avg_pengumpulan;

        $boards = array(
            "1" => array(
                "rekening" => "avg_piutang_dagang",
                // "rekening_posisi" => "debet",
                // "nilai"      => $kas,
                "label"    => "avg piutang dagang " . previousMonth(),
                "icon"     => "fa-money",
                "bg"       => "bg-green",
                // "link"            => "Neraca/viewBalanceSheet",
                // "link_label"      => "Balance Sheet",
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
            "2" => array(
                "rekening" => "perputaran_piutang",
                // "rekening_posisi" => "kredit",
                // "nilai"      => $hutangKepusat,
                "label"    => "perputaran piutang (periode)",
                "satuan"   => "kali",
                "icon"     => "fa-money",
                // "bg"              => "bg-teal",
                "bg"       => "bg-fuchsia",
                // "link"            => "Rugilaba/viewPLConsolidated/mtd?gr=czo1MToibGFwb3JhbmtldWFuZ2Fua29uc29saWRhc2lpbnRlcm5hbC1ybGNvbmludGVybmFsbXRkIjs=",
                // "link_label"      => "MTD (CONSOLIDATED)",
            ),
            "3" => array(
                "rekening" => "avg_pengumpulan",
                // "rekening_posisi" => "debet",
                // "nilai"      => $hutangKepusat,
                "label"    => "umur piutang dalam hari",
                "satuan"   => "hari",
                "icon"     => "fa-money",
                "bg"       => "bg-red",
                // "link"            => "Neraca/viewBalanceSheet",
                // "link_label"      => "Balance Sheet",
            ),
        );
        $datas = array();
        $datas["row"] = $rows;
        $datas["board_bulanan"] = $tmps;
        $datas["board"] = $boards;

        // arrPrintPink($datas);
        return $datas;
    }

    public function rekeningLainNya()
    {
        // ini_set('display_errors', 0);
        // error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        // error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        error_reporting(-1);
        ini_set('display_errors', 1);
        $rumus = "";
        $rumus .= "<style type='text/css'>.merah{color: crimson;}</style>";
        $rumus .= "<ol style='text-align: left;'>";
        $rumus .= "<li class='merah'>Current Ratio = Aktiva Lancar / Hutang Lancar X 100%</li>";
        $rumus .= "<li class='merah'>Perputaran Persediaan (Inventory Turnover) = Penjualan / Persediaan x 100%</li>";
        $rumus .= "<li class='merah'>Perputaran Aktiva Tetap (Fixed Asset Turnover) = Penjualan / Aktiva Tetap x 100%</li>";
        $rumus .= "<li>Perputaran Aktiva Total (Total Asset Turnover) = Penjualan / Total Aktiva x 100%</li>";
        $rumus .= "<li>Quick Ratio = Kas + Efek + Piutang / Hutang Lancar x 100%</li>";
        $rumus .= "<li>Cash Ratio = Kas + Efek / Hutang lancar</li>";
        $rumus .= "<li class='merah'>Gross Profit Margin = Penjualan Netto – Cost of Good Sold (Harga Pokok Penjualan) / Penjualan Netto x 100%</li>";
        $rumus .= "<li>Net Profit Margin = Laba Bersih Setelah Pajak (EAT) / Penjualan Netto x 100%</li>";
        $rumus .= "<li>Operating Income Ratio = Penjualan Netto – Cost of Good Sold (HPP) – Earnings Before Interest & Taxes (EBIT) / Penjualan Netto x 100%</li>";
        $rumus .= "<li>Earning Power of Total Investment  = Laba Sebelum Pajak dan Bunga / Jumlah Aktiva x 100%</li>";
        $rumus .= "<li>Return on Investment = EAT / Jumlah Aktiva x 100%</li>";
        $rumus .= "<li>Equity = Earnings After Tax (EAT) / Jumlah Equity x 100%</li>";
        $rumus .= "<li>Return on Net Worth = Earnings After Tax (EAT) / Jumlah Modal Sendiri x 100%</li>";
        $rumus .= "<li>Perputaran Rerata Tagihan (Average Collection Turnover) = Piutang x 365 / Penjualan x 100%</li>";
        $rumus .= "<li>Perputaran Modal Kerja (Working Capital Turnover) = Penjualan / (Aktiva Lancar – Hutang Lancar) x 100%</li>";
        $rumus .= "</ol>";
        cekHijau($rumus);
        $this->load->config("heAccounting");
        $structur = $this->config->item("accountStructure");
        // cekBiru($structur);

        $prev_thn = previousYear();
        //region activa
        $rekening_activa = $structur["aktiva"];
        // arrPrintPink($rekening_activa);
        $koloms = array(
            "thn",
            // "bln",
            // "rekening",
            // "rek_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            "thn"     => $prev_thn,
            // "bln"     => $prev_bln,
            "periode" => "tahunan",
            // "rekening" => "return penjualan"
        );
        $this->db->where($condites);
        $conditeins = array(
            "penjualan",
            "return penjualan",
        );
        $this->db->where_in("rekening", $rekening_activa);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->row();
        // showLast_query("kuning");
        // arrPrint($src_datas);
        //endregion
        $activa = $src_datas->debet;
        // cekBiru("$activa");

        //region aktiva tetap (kosong ....)
        $conditeins = array(
            "aktiva tetap",
        );
        $src_datas = $this->sum_rek_master_cache_thn($prev_thn, $conditeins);
        // showLast_query("kuning");
        // arrPrint($src_datas);
        //endregion
        $aktiva_tetap = $src_datas->debet;

        //region hpp + hpp project
        $conditeins = array(
            "hpp",
            "hpp projek"
        );
        $src_datas = $this->sum_rek_master_cache_thn($prev_thn, $conditeins);
        // showLast_query("kuning");
        // arrPrint($src_datas);
        //endregion
        $hpp_total = $src_datas->debet;

        //region hutang
        $rekening_hutang_lancar = array(
            "hutang bank"              => "hutang bank",
            "hutang dagang"            => "hutang usaha",
            "hutang aktiva tetap"      => "hutang aktiva tetap",
            "hutang ke pusat"          => "hutang ke pusat",
            "hutang ke cabang"         => "hutang ke cabang",
            "hutang jangka panjang"    => "hutang jangka panjang",
            "hutang ppn"               => "hutang ppn",
            "hutang biaya ke pusat"    => "hutang biaya ke pusat",
            "hutang ke konsumen"       => "hutang ke konsumen",
            "hutang valas ke konsumen" => "uang muka penjualan ekspor",
            "hutang ke pemegang saham" => "hutang ke pemegang saham",
            "hutang ke pihak lain"     => "hutang ke pihak lain",
        );
        // arrPrintWebs($rekening_hutang_lancar);
        $koloms = array(
            "thn",
            // "bln",
            // "rekening",
            // "rek_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            "thn"     => $prev_thn,
            // "bln"     => $prev_bln,
            "periode" => "tahunan",
            // "rekening" => "return penjualan"
        );
        $this->db->where($condites);
        // $conditeins = array(
        //     "penjualan",
        //     "return penjualan",
        // );
        $this->db->where_in("rekening", $rekening_hutang_lancar);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->row();
        // showLast_query("kuning");
        // arrPrint($src_datas);
        //endregion
        $hutang = $src_datas->kredit;
        $curent_ratio = ($activa / $hutang) * 100;

        //region persediaan produk
        $conditeins = array(
            "persediaan produk",
            "persediaan produk rakitan",
            "persediaan supplies",
            "persediaan supplies proses",
        );
        $src_datas = $this->sum_rek_master_cache_thn($prev_thn, $conditeins);
        // showLast_query("kuning");
        // arrPrint($src_datas);
        //endregion
        $persediaan = $src_datas->debet;

        //region return X penjualan + projek
        $koloms = array(
            "thn",
            // "bln",
            "rekening",
            "rek_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            "thn"     => $prev_thn,
            // "bln"     => $prev_bln,
            "periode" => "tahunan",
            // "rekening" => "return penjualan"
        );
        $this->db->where($condites);
        $conditeins = array(
            "penjualan",
            "penjualan projek",
            "return penjualan",
        );
        $this->db->where_in("rekening", $conditeins);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->row();
        // showLast_query("kuning");
        // arrPrint($src_datas);
        //endregion
        $penjualan = $src_datas->kredit - $src_datas->debet;
        $perputaran_persediaan = $penjualan / $persediaan * 100;
        $perputaran_activa = $penjualan / $activa * 100;

        //region kas
        $rekenings = array(
            "kas"
        );
        $src_datas = $this->sum_rek_master_cache_thn($prev_thn, $rekenings);
        // arrPrintKuning($src_datas);
        //endregion
        $kas = $src_datas->debet;

        $gross_profit_margin = ($hpp_total / $penjualan) * 100;
        // cekKuning($kas);

        // cekBiru("$curent_ratio = ($activa / $hutang) * 100 <br>curent_ratio = (activa / hutang) * 100");
        // cekBiru("$perputaran_persediaan = $penjualan / $persediaan * 100; <br>perputaran_persediaan = penjualan / persediaan * 100;");
        // cekBiru("$perputaran_activa = $penjualan / $activa * 100; <br>perputaran_activa = penjualan / activa * 100");
        $rasios = array(
            "curent_ratio = (activa / hutang) * 100"               => "$curent_ratio = ($activa / $hutang) * 100",
            "perputaran_persediaan = penjualan / persediaan * 100" => "$perputaran_persediaan = $penjualan / $persediaan * 100",
            "perputaran_activa = penjualan / activa * 100"         => "$perputaran_activa = $penjualan / $activa * 100",
            "gross_profit_margin = (hpp_total / penjualan) * 100;" => "$gross_profit_margin = ($hpp_total / $penjualan) * 100;",
        );

        $strBiru = "";
        $strBiru .= "<ol style='text-align: left;'>";
        foreach ($rasios as $rumus => $angka) {
            $strBiru .= "<li>$rumus<div style='color: #d11dbb;'>$angka</div></li>";
        }
        $strBiru .= "</ol>";
        cekBiru($strBiru);
    }

    private function sum_rek_master_cache_thn($thn, $rekenings)
    {

        $koloms = array(
            "thn",
            // "bln",
            // "rekening",
            // "rek_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            "thn"     => $thn,
            // "bln"     => $prev_bln,
            "periode" => "tahunan",
            // "rekening" => "return penjualan"
        );
        $this->db->where($condites);
        $conditeins = array(
            "penjualan",
            "return penjualan",
        );
        $this->db->where_in("rekening", $rekenings);
        $tableName = "_rek_master_cache";
        $src_datas = $this->db->get($tableName)->row();
        // showLast_query("kuning");
        // arrPrint($src_datas);

        return $src_datas;
    }

    private function viewCek($rasios)
    {
        $strBiru = "";
        $strBiru .= "<ol style='text-align: left;'>";
        foreach ($rasios as $rumus => $angka) {
            $strBiru .= "<li>$rumus<div style='color: #d11dbb;'>$angka</div></li>";
        }
        $strBiru .= "</ol>";

        // cekBiru($strBiru);
        return $strBiru;
    }

    private function sum_rek_hutang_dagang($thn)
    {
        // __rek_pembantu_supplier__hutang_dagang
        $rekenings = "hutang dagang";
        $koloms = array(
            // "year(dtime) as thn",
            // "bln",
            // "rekening",
            // "rek_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            // "thn"     => $thn,
            "date(dtime)>=" => "$thn-01-01",
            "date(dtime)<=" => "$thn-12-31",
            // "periode" => "tahunan",
            "rekening"      => "$rekenings"
        );
        $this->db->where($condites);
        $jenies = array(
            "3463", // projek
            "460", // import
            "467", // supplies
            "461", // produk
            // "463", // jas -- hutang_jasa
            // "1463", // hutang biaya
        );
        $this->db->where_in("jenis", $jenies);
        // $tableName = "__rek_pembantu_supplier__hutang_dagang";
        $tableName = "__rek_master__hutang_dagang";
        $src_datas = $this->db->get($tableName)->row();

        return $src_datas;
    }

    private function sum_rek_hutang_biaya($thn)
    {
        //    __rek_master__hutang_biaya
        $rekenings = "hutang biaya";
        $koloms = array(
            // "year(dtime) as thn",
            // "bln",
            // "rekening",
            // "rek_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            // "thn"     => $thn,
            "date(dtime)>=" => "$thn-01-01",
            "date(dtime)<=" => "$thn-12-31",
            // "periode" => "tahunan",
            "rekening"      => "$rekenings"
        );
        $this->db->where($condites);
        $jenies = array(
            "463", // jas -- hutang_jasa
            "1463", // hutang biaya
        );
        $this->db->where_in("jenis", $jenies);

        $tableName = "__rek_master__hutang_biaya";
        $src_datas = $this->db->get($tableName)->row();

        return $src_datas;
    }

    private function sum_rek_hutang_aktiva_tetap($thn)
    {
        //    __rek_master__hutang_aktiva_tetap
        $rekenings = "hutang aktiva tetap";
        $koloms = array(
            // "year(dtime) as thn",
            // "bln",
            // "rekening",
            // "rek_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            // "thn"     => $thn,
            "date(dtime)>=" => "$thn-01-01",
            "date(dtime)<=" => "$thn-12-31",
            // "periode" => "tahunan",
            "rekening"      => "$rekenings"
        );
        $this->db->where($condites);
        $jenies = array(
            "423", // jas -- hutang_jasa
            "4821", // hutang biaya
        );
        $this->db->where_in("jenis", $jenies);

        $tableName = "__rek_master__hutang_aktiva_tetap";
        $src_datas = $this->db->get($tableName)->row();

        return $src_datas;

    }

    private function sum_rek_piutang_pembelian($thn)
    {
        $tableName = "__rek_pembantu_supplier__piutang_pembelian";
        $rekenings = "piutang pembelian";
        $koloms = array(
            // "year(dtime) as thn",
            // "bln",
            // "rekening",
            // "rek_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            // "thn"     => $thn,
            "date(dtime)>=" => "$thn-01-01",
            "date(dtime)<=" => "$thn-12-31",
            // "periode" => "tahunan",
            "rekening"      => "$rekenings"
        );
        $this->db->where($condites);
        $jenies = array(
            "960",
            "961",
            "967",
        );
        $this->db->where_in("jenis", $jenies);
        $src_datas = $this->db->get($tableName)->row();

        return $src_datas;
    }

    private function sum_rek_master_persediaan($thn){
        // __rek_master__persediaan_produk
        $tableName = "__rek_master__persediaan_produk";
        $rekenings = "persediaan produk";
        $koloms = array(
            // "year(dtime) as thn",
            // "bln",
            // "rekening",
            // "rek_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            // "thn"     => $thn,
            "date(dtime)>=" => "$thn-01-01",
            "date(dtime)<=" => "$thn-12-31",
            // "periode" => "tahunan",
            "rekening"      => "$rekenings"
        );
        $this->db->where($condites);
        $jenies = array(
            "467",
            "460",
            "585",
        );
        $this->db->where_in("jenis", $jenies);
        $src_datas = $this->db->get($tableName)->row();

        return $src_datas;
    }

    private function sum_rek_master_persediaan_supplies($thn){

        $tableName = "__rek_master__persediaan_supplies";
        $rekenings = "persediaan supplies";
        $koloms = array(
            // "year(dtime) as thn",
            // "bln",
            // "rekening",
            // "rek_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            // "thn"     => $thn,
            "date(dtime)>=" => "$thn-01-01",
            "date(dtime)<=" => "$thn-12-31",
            // "periode" => "tahunan",
            "rekening"      => "$rekenings"
        );
        $this->db->where($condites);
        $jenies = array(
            "461",
            // "585",
        );
        $this->db->where_in("jenis", $jenies);
        $src_datas = $this->db->get($tableName)->row();

        return $src_datas;
    }

    private function sum_rek_master_persediaan_rakitan($thn){

        $tableName = "__rek_master__persediaan_produk_rakitan";
        $rekenings = "persediaan produk rakitan";
        $koloms = array(
            // "year(dtime) as thn",
            // "bln",
            // "rekening",
            // "rek_id",
            "sum(debet) as debet",
            "sum(kredit) as kredit",
            // "debet",
            // "kredit",
        );
        $this->db->select($koloms);
        $condites = array(
            // "thn"     => $thn,
            "date(dtime)>=" => "$thn-01-01",
            "date(dtime)<=" => "$thn-12-31",
            // "periode" => "tahunan",
            "rekening"      => "$rekenings"
        );
        $this->db->where($condites);
        $jenies = array(
            "776",
            // "585",
        );
        $this->db->where_in("jenis", $jenies);
        $src_datas = $this->db->get($tableName)->row();

        return $src_datas;
    }
}