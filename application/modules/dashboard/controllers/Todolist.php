<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 03/04/2019
 * Time: 13.50
 */

class Todolist extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);

        session_write_close();
        // $this->load->library("SmtpMailer");
        //
        // $this->reportJenis = array(
        //     "pre_penjualan"          => array(
        //         "582spo",
        //         "382spo",
        //         // "582so",
        //     ),
        //     "pre_penjualan_canceled" => array(
        //         "582spo",
        //         "382spo",
        //         // "582so",
        //     ),
        //     "penjualan"              => array(
        //         "582spd",
        //         "982",
        //         "382spd",
        //         // "982",
        //     ),
        //     "pembelian_supplies"     => array(
        //         "461",
        //         "961",
        //     ),
        //     "pembelian_produk"       => array(
        //         "467",
        //         "961",
        //     ),
        // );
    }

    public function viewTodolistTransaksi()
    {
        // link:: opname/Opname/cekTransaksiGantung/Produk
        // cekPink(my_id());
        $myId = my_id();
        // $myId = 17;
        // $myId = 69;
        // $myId = 316;
        $this->load->helper("he_access_right");
        $this->load->config("heTransaksi_ui");
        $configUi = $this->config->item("heTransaksi_ui");
        $src_access = alowedAccess_he_access_right($myId, $configUi);
        // arrPrintKuning($configUi);
        // arrPrintKuning($src_access);

        $alias_steps = array();
        foreach ($configUi as $jmaster => $item) {
            $modul = $item['modul'];
            $label_parent = $item['label'];
            $aliasing_master[$jmaster] = $label_parent;
            $modul_master[$jmaster] = $modul;

            $steps = $item['steps'];
            foreach ($steps as $step => $step_params) {
                $source = $step_params['source'];
                $label_step = $step_params['label'];
                if (isset($step_params['source'])) {
                    $alias_steps[$source] = $label_step;
                    $modul_steps[$source] = $modul;
                }
            }
        }
        // arrPrintHijau($alias_steps);

        $hak_master_jenis = array();
        $hak_jenis = array();
        $hak_jenis_master = array();
        foreach ($src_access as $mJenis => $src_accesies) {
            foreach ($src_accesies as $src_accesy) {
                // arrPrintPink($src_accesy);
                foreach ($src_accesy as $ac_jenis => $item) {
                    $hak_jenis_master[$mJenis][] = $ac_jenis;
                    $hak_jenis[] = $ac_jenis;
                    $hak_master_jenis[$ac_jenis] = $mJenis;
                }
            }
        }
        // asort($hak_jenis_master);
        asort($hak_jenis);
        // arrPrintWebs($hak_master_jenis);
        // arrPrintWebs($hak_jenis_master);
        // arrPrint($hak_jenis);

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // $condites = array();
        // $this->db->limit(5);
        // $srcTr = $tr->lookupByCondition($condites)->result();
        // showLast_query("hijau");

        $jenis_gantungs = $tr->callGantunganTransaksi(false);
//        showLast_query("biru");
        // arrPrintPink($jenis_gantungs);
        $jenis_gantung = array();
        $jenis_saya = array();

        foreach ($hak_master_jenis as $jenis_tr => $jenis_master) {
            $jml = sizeof($jenis_gantungs[$jenis_tr]);

            // cekBiru("$jenis_tr $jml $jenis_master");
            $jenis_gantung[$jenis_tr] = $jml;
            $myJenisMaster[$jenis_master][$jenis_tr] = $jml;

            if (!isset($myJenisMasterJml[$jenis_master])) {
                $myJenisMasterJml[$jenis_master] = 0;
            }
            $myJenisMasterJml[$jenis_master] += $jml;
            // if(in_array($jenis_tr, $jenis_gantungs)){
            //     $jenis_saya[$jenis_tr] = $jml;
            // }
        }
        $jenis_saya = array_filter($myJenisMasterJml);
        // arrPrintKuning());
        // arrPrintKuning($myJenisMaster);
        // arrPrintHijau($jenis_gantung);

        $var = "<ul class='todo-list ui-sortable'>";
        foreach ($jenis_saya as $jenis_transaksi => $jml_transaksi) {

            $label = $aliasing_master[$jenis_transaksi];
            $modul = $modul_master[$jenis_transaksi];
            $master_jns = $hak_master_jenis[$jenis_transaksi];
            $link_transaksi = base_url() . "$modul/Transaksi/index/$master_jns";

            $var .= "<li class='col-md-6'>";
            $var .= "<span class='handle ui-sortable-handle'>";
            $var .= "<i class='fa fa fa-ellipsis-v'></i>&nbsp;";
            $var .= "<i class='fa fa fa-ellipsis-v'></i>";
            $var .= "</span>";
            $var .= "<span>";
            $var .= "<a href='$link_transaksi' class='text-uppercase'>";
            $var .= "$label";
            $var .= "</a>";
            $var .= "</span>";
            $var .= "<small class='label label-danger'>$jml_transaksi</small>";
            $var .= "</li>";
        }
        $var .= "</ul>";

        // $content = "<div>";
        // $content .= "<div>";

        // echo $var;

        $data = array(
            "mode" => "viewTodolistTransaksi",
            "title" => "Efisiensi " . $subRekening[$keyBiaya] . " $tahun",
            "subTitle" => "<a href=\"javascript:void(0);\" id=\"mode_$keyBiaya\" classs=\"btn\"><i class=\"fa fa-bar-chart\"></i></a>",
            "label_series" => $label_series,
            "tipe_series" => $tipe_series,
            "subRekening" => $subRekening,
            "keyBiaya" => $keyBiaya,
            "content" => $var,
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

}