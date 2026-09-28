<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class LineState extends REST_Controller
{
    private $jenisTr;



    function __construct($config = 'rest')
    {

        parent::__construct($config);
        $this->load->database();
        $this->load->model("MdlTransaksi");
//        $this->load->helper("uri");
        $this->jenisTr = $this->uri->segment(4);


    }

    function whichActiveIP_get(){
        $cab=$this->uri->segment(4);
        $gud=$this->uri->segment(5);
        $this->load->model("Mdls/MdlActiveIPAddr");
        $ip=new MdlActiveIPAddr();
        $ip->addFilter("cabang_id='".$cab."'");
        $ip->addFilter("gudang_id='".$gud."'");
        $ip->addFilter("jenis='kasir'");
        $tmpi=$ip->lookupAll()->result();
        $result=0;
        if(sizeof($tmpi)>0){
            $result=array(
                "cabangID"=>$tmpi[0]->cabang_id,
                "gudangID"=>$tmpi[0]->gudang_id,
                "cabangName"=>$tmpi[0]->cabang_nama,
                "gudangName"=>$tmpi[0]->gudang_nama,
                "ipaddr"=>lgCountMinutes($tmpi[0]->last_active)<5?$tmpi[0]->ipaddr:0,
            );
        }
        $this->response($result, 200);
    }

}