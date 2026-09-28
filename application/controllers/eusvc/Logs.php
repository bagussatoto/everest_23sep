<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// header("Access-Control-Allow-Origin: *");
require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Logs extends REST_Controller
{


    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->model = "MdlActivityLog";
        $this->load->database();
        $this->load->model("Mdls/" . $this->model);
        //
    }

    public function activity_get()
    {
        $dtime_now = $this->uri->segment(4);
        $limit_per_page = $this->uri->segment(14);
        $page = $this->uri->segment(15);
        $key = $this->uri->segment(16);
        $dtimeNOw = dtimeNow();
        $dateNow = dtimeNow("Y-m-d");
        // arrPrint($this->urisegment());
        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();
        $dtimeReq = strlen($dtime_now) > 0 ? $dtime_now : $dtimeNOw;
        // $o->addFilter("DATE(dtime)='$dateNow'");
        $o->addFilter("DATE(dtime)='$dtimeReq");
        //		$tmp = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);
        $tmp = $o->lookupLimitedData($limit_per_page, ($page - 1) * $limit_per_page, $key);
        // showLast_query("lime");
        // die($this->db->last_query());

        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result0[] = $tmpData;
            }

            $result["row"] = sizeof($result0);
            $result["datas"] = $result0;
        }
        else {
            $result["row"] = 0;
            $result["data"] = array();
        }
        $this->response($result, 200);
    }


    public function askMonthly_get()
    {
        // cekMerah(url_segment());
        $thn = $this->uri->segment(4);
        $this->load->library("Transaksional");
        $tr = new Transaksional();

        $src = $tr->callJmlTransakional($thn);

        $file = fopen(__DIR__ . "/sync/Logs.txt", "w");
        fwrite($file, json_encode($src));
        fclose($file);

        // arrPrint($src);

        $amount = 0;
        if (sizeof($src) > 0) {
            // foreach($tmp as $row){
            //     $amount+=$row->transaksi_nilai;
            // }

        }
        $this->response($src, 200);
    }
}

?>