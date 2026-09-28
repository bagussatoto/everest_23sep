<?php

// START OF COMPLETE REPEATED LOGIC
/**
 * Created by PhpStorm.
 * User: none
 * Date: 5/6/2023
 * Time: 8:39 PM
 */
class PaymentSource extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
    }


    public function addDescriptionFromRegistriMain()
    {
        if ($_GET['r'] && $_GET['r'] > 0) {
            $detik = $_GET['r'];
            header("refresh:$detik");
        }

        $tbl_1 = "transaksi_payment_source";
        $jenis = array(
            "1467",
            "467",
        );
        if (is_array($jenis)) {

            $this->db->where_in("jenis", $jenis);
        }
        else {
            $this->db->where("jenis", $jenis);
        }
        $wheres = array(
            // "sisa>" => "1000",
            // "LENGTH(extern_label2)" => 0,
            "transaksi_ref_id" => null,
            "id>"                   => 0,
        );
        $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        $this->db->limit(1);
        $srcs = $this->db->get($tbl_1)->result_array();
        showLast_query("kuning");
        // arrPrintHijau($srcs);

        // -----------------------------------------------------------------------------
        $this->db->trans_start();
        foreach ($srcs as $src) {

            $ps_id = $src['id'];
            $transaksi_id = $src['transaksi_id'];
            $transaksi_jenis = $src['jenis'];
            // $transaksi_eFaktur = $src['eFaktur'];
            // -----------------------------------------------------------------------------

            $tbl_2 = "transaksi";
            $wheres = array(
                "id" => $transaksi_id,
            );
            $this->db->where($wheres);
            // $this->db->order_by("dtime", "asc");
            // $this->db->limit(1);
            $this->db->select("ids_his");
            $srcs_2 = $this->db->get($tbl_2)->row_array();
            showLast_query("pink");
            arrPrintPink($srcs_2);

            $ids_his = blobDecode($srcs_2['ids_his']);
            $arrHis = $ids_his[2];
            $nomerpo = $arrHis['nomer2'];
            $trid = $arrHis['trID'];
            // arrPrintHijau($ids_his[2]);
            // -----------------------------------------------------------------------------

            $tbl_2 = "transaksi_data_registry";
            $wheres = array(
                "transaksi_id" => $transaksi_id,
            );
            $this->db->where($wheres);
            // $this->db->order_by("dtime", "asc");
            // $this->db->limit(1);
            $this->db->select("main");
            $srcs_2 = $this->db->get($tbl_2)->row_array();
            showLast_query("pink");
            arrPrintPink($srcs_2);

            $main = blobDecode($srcs_2['main']);

            // arrPrintKuning($main);
            // arrPrintKuning($main['description_main_followup']);
            // ----------------------------------------------------------------------------

            $description = isset($main["description"]) ? (strlen($main["description"]) > 3 ? $main["description"] : "-") : "none";                                                            // $description (note)
            $description_main_followup = isset($main["description_main_followup"]) ? (strlen($main["description_main_followup"]) > 5 ? $main["description_main_followup"] : "--") : "none";   // invoice dari supplier
            // cekHere($description_main_followup);
            $dataBaru = array(
                "extern_label2" => $description,                // note GRN
                "extern2_nama"  => $description_main_followup,  // invoice dari supplier
                // "extern_date2" => $description,              // e=faktur tanggal
                // "extern_jenis" => $description,              // e-faktur
                "transaksi_"
            );
            $where = array(
                "id"          => $ps_id,
                "LENGTH(extern_label2)" => 0,
            );
            $this->db->where($where);
            $this->db->update($tbl_1, $dataBaru);
            showLast_query("here");


        }
        cekKuning(dtimeNow() . " || id:: $transaksi_id || jenis:: $transaksi_jenis");

        $link_stop = MODUL_PATH . "PaymentSource/addDescriptionFromRegistriMain?r=0";
        cekHitam("<a href='$link_stop' style='color: #ffff00;'>stop</a>");

        matiHere("belum commit " . __LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

    }

    public function checkMissing3675()
    {
        $queryString = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
        redirect(base_url() . "Tool/spkPaymentSource" . $queryString);
    }

    public function spk()
    {
        $queryString = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
        redirect(base_url() . "Tool/spkPaymentSource" . $queryString);
    }

    public function rekonsiliasi()
    {
        $queryString = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
        redirect(base_url() . "Tool/spkRekonsiliasi" . $queryString);
    }

    public function audit()
    {
        $queryString = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
        redirect(base_url() . "Tool/spkRekonsiliasi" . $queryString);
    }

    public function index()
    {
        matiHere("typo");
    }

}
// END OF COMPLETE REPEATED LOGIC