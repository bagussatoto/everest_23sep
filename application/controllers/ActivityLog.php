<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 5/6/2019
 * Time: 8:39 PM
 */
class ActivityLog extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");

    }

    function index()
    {


    }


    function viewLog()
    {

        $ctrl = $this->uri->segment(1);
        $method = $this->uri->segment(2);

        $branchID = null != $this->uri->segment(3) ? $this->uri->segment(3) : -1;
        $whID = -1;
        $sesID = $this->session->login['id'];
//        arrPRint($sesID);
        $this->load->model("MdlActivityLog");

        $trIDs = array();
        $TransData = array();
        $c = new MdlActivityLog();
        $headerFields = $c->getListedFields();

//        arrPrint($headerFields);
        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            $limit = 100;

            $this->db->limit("$limit");


            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
            $this->db->where("date(dtime)='" . $date1 . "'");
            $this->db->order_by("id", "ASC");
            $subTitle_date = " <span style='font-size:12px;font-style:italic;'>(cli $limit terakhir)</span>";
        }
        else {
            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

//             $this->db->where("dtime>='" . $date1 . "'");
//             $this->db->where("dtime<='" . $date2 . "'");
            $this->db->where("date(dtime)>='" . $date1 . "'");
            $this->db->where("date(dtime)<='" . $date2 . "'");
//            $c->addFilter("date(dtime)>='" . $date1 . "'");
//            $c->addFilter("date(dtime)>='" . $date2 . "'");
            $this->db->order_by("id", "ASC");
            $subTitle_date = lgTranslateTime($date2) . " - " . lgTranslateTime($date2);
        }
//        $c->addFilter("ghost=0");
        $c->addFilter("uid='$sesID'");
//        $c->addFilter("uid='78'");// santoso
//        $c->addFilter("uid='69'");// yanti
//        $c->addFilter("uid='79'");// atmojo santoso
        $tmp = $c->lookupAll()->result();
//        cekBiru($this->db->last_query());

        $items = array();
        if (sizeof($tmp) > 0) {

            foreach ($tmp as $row) {
                $tmp = array();
                foreach ($headerFields as $key => $alias) {
                    $tmp[$key] = $row->$key;
                }
                $tmp1 = array();
                if ($row->transaksi_id > 0) {
                    $this->load->model("MdlTransaksi");
                    $t = new MdlTransaksi();
                    $ii = $t->lookupByID($row->transaksi_id)->result();
                    $nomer = $ii[0]->nomer;
                    $modul = isset($this->masterConfigUi[$ii[0]->jenis_master]['modul']) ? $this->masterConfigUi[$ii[0]->jenis_master]['modul'] : "";
                    $tmp1['nomer'] = $nomer;
                    $tmp1['modul'] = $modul;
                    $tmp1['modul_path'] = base_url() . "$modul/";
                    $tmp1['jenis_master'] = $ii[0]->jenis_master;
                }
                $items[] = $tmp + $tmp1;
            }
        }
//        arrPrint($items);
        $thisPage = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/?";
        $FinalHeaderFields = $headerFields + array("nomer" => "Nomer");
        $data = array(
            "mode" => "viewLog",
            "title" => "Activity Log",
            "subTitle" => "  ",
            "headerFields" => $FinalHeaderFields,
            "items" => $items,
            "warning" => isset($warning) ? $warning : array(),
            "marking" => isset($marking) ? $marking : array(),
            "markingColumn" => isset($markingColumn) ? $markingColumn : array(),
            "button" => array(),

            "thisPage" => $thisPage,
//            "thisPage" => "",
            "q" => isset($q) ? $q : "",
            "filters" => array(
                "date1" => $date1,
                "date2" => $date2,
                "dates" => date("Y-m-d"),
            ),
        );

        $this->load->view("log", $data);
    }

}