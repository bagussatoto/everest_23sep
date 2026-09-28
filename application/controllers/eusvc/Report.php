<?php

defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
class Report extends REST_Controller
{
    function __construct($config = 'rest'){
        parent::__construct($config);
        $this->load->database();
        $this->load->model("MdlTransaksi");
    }

    public function askDaySales_get()
    {

        $cabID = $this->uri->segment(4);
        $date = $this->uri->segment(5);
        if(strlen($date)<1){
            $date=date("Y-m-d");
        }
        $tr=new MdlTransaksi();
//        $tr->addFilter("jenis='".$jenis."'");
        $tr->addFilter("jenis='582'");
        $tr->addFilter("transaksi.fulldate='$date'");
        $tr->addFilter("transaksi.cabang_id='".$cabID."'");

        $tmp=$tr->lookupHistories(200, 200, 1)->result();
        $amount=0;
        if(sizeof($tmp)>0){
            foreach($tmp as $row){
                $amount+=$row->transaksi_nilai;
            }

        }
        $this->response($amount, 200);
    }

    public function askYesterdaySales_get()
    {

        $cabID = $this->uri->segment(4);
        $date = $this->uri->segment(5);
        if(strlen($date)<1){
            $date=date("Y-m-d");
        }
        $date=$this->getDateNumberBefore($date);
        $tr=new MdlTransaksi();
//        $tr->addFilter("jenis='".$jenis."'");
        $tr->addFilter("jenis='582'");
        $tr->addFilter("transaksi.fulldate='$date'");
        $tr->addFilter("transaksi.cabang_id='".$cabID."'");

        $tmp=$tr->lookupHistories(200, 200, 1)->result();
        $amount=0;
        if(sizeof($tmp)>0){
            foreach($tmp as $row){
                $amount+=$row->transaksi_nilai;
            }

        }
        $this->response($amount, 200);
    }

    public function askMonthSales_get()
    {

        $cabID = $this->uri->segment(4);
        $month = $this->uri->segment(5);
        $month = $this->getMonthNumber($month);
//        $lastMonth = $this->getMonthNumberBefore($month);
        $tr=new MdlTransaksi();

        $tr->addFilter("jenis='582'");
        if($month==date("Y-m")){
            $this->db->where("fulldate>='" . date("Y-m-01") . "'");
            $this->db->where("fulldate<='" . date("Y-m-d") . "'");
        }else{
            $this->db->where("fulldate>='" . date("$month-01") . "'");
            $this->db->where("fulldate<='" . date("$month-31") . "'");
        }

        $tr->addFilter("transaksi.cabang_id='".$cabID."'");
        $tmp=$tr->lookupHistories(200, 200, 1)->result();
        $result=array(
            "amount"=>0,
            "average"=>0,
        );
        $dates=array();
        if(sizeof($tmp)>0){
            foreach($tmp as $row){
                $result['amount']+=$row->transaksi_nilai;
                if(!in_array($row->fulldate,$dates)){
                    $dates[]=$row->fulldate;
                }
            }
            $result['average']=($result['amount']/sizeof($dates));

        }

        $this->response($result, 200);
    }
    public function askLastMonthSales_get()
    {

        $cabID = $this->uri->segment(4);
        $month = $this->uri->segment(5);
//        $month = $this->getMonthNumber($month);
        $month = $this->getMonthNumberBefore($month);
        $tr=new MdlTransaksi();

        $tr->addFilter("jenis='582'");
        if($month==date("Y-m")){
            $this->db->where("fulldate>='" . date("Y-m-01") . "'");
            $this->db->where("fulldate<='" . date("Y-m-d") . "'");
        }else{
            $this->db->where("fulldate>='" . date("$month-01") . "'");
            $this->db->where("fulldate<='" . date("$month-31") . "'");
        }

        $tr->addFilter("transaksi.cabang_id='".$cabID."'");
        $tmp=$tr->lookupHistories(200, 200, 1)->result();
        $result=array(
            "amount"=>0,
            "average"=>0,
        );
        $dates=array();
        if(sizeof($tmp)>0){
            foreach($tmp as $row){
                $result['amount']+=$row->transaksi_nilai;
                if(!in_array($row->fulldate,$dates)){
                    $dates[]=$row->fulldate;
                }
            }
            $result['average']=($result['amount']/sizeof($dates));

        }

        $this->response($result, 200);
    }

    function getMonthNumber($date=""){
        if($date==""){
            $date=date("Y-m-d");
        }
        $this->db->select("DATE_FORMAT('$date', \"%Y-%m\") as this_month");
        $q=$this->db->get();
        return $q->result()[0]->this_month;
    }
    function getMonthNumberBefore($date){
        if($date==""){
            $date=date("Y-m-d");
        }
        $this->db->select("DATE_FORMAT(last_day('$date' - interval 1 month), \"%Y-%m\") as last_month");
        $q=$this->db->get();
        return $q->result()[0]->last_month;
    }
    function getDateNumberBefore($date){
        if($date==""){
            $date=date("Y-m-d");
        }
        $this->db->select(" subdate(current_date, 1) as last_date");
        $q=$this->db->get();
        return $q->result()[0]->last_date;
    }

    public function getListBranchAvail_get(){

        $tr=new MdlTransaksi();
        $tr->addFilter("jenis='582'");
        $tr->addFilter("GROUP BY cabang_id");
        $tmp=$tr->lookupAll()->result();

        $result=array();
        if(sizeof($tmp)>0){
            foreach($tmp as $row){
                $result[$row->cabang_id] = isset($row->cabang_nama) ? $row->cabang_nama : "pusat/dataOld";
            }
        }
//        arrPrint($result);
        $this->response($result, 200);

    }

    public function askDaysOfSales_get()
    {
//        $cabID = $this->uri->segment(4);
        $days = sizeof($this->uri->segment(5)) ? $this->uri->segment(5) : 14;

        function getLastNDays($days, $format = 'Y-m-d'){
            $m = date("m"); $de= date("d"); $y= date("Y");
            $dateArray = array();
            for($i=0; $i<=$days-1; $i++){
                $dateArray[] = '' . date($format, mktime(0,0,0,$m,($de-$i),$y)) . '';
            }
            return array_reverse($dateArray);
        }

        $availTgl = array();
        foreach(getLastNDays($days, 'Y-m-d') as $k=>$tgl){
            $availTgl[] = $tgl;
        }

        $tr=new MdlTransaksi();
        $tr->addFilter("jenis='582'");

        $current = current($availTgl);
        $end = end($availTgl);

        if($current===$end){
            $this->db->where("transaksi.fulldate>='" . $current . "'");
            $this->db->where("transaksi.fulldate<='" . $current . "'");
        }
        else{
            $this->db->where("transaksi.fulldate>='" . $current . "'");
            $this->db->where("transaksi.fulldate<='" . $end . "'");
        }

        $tmp=$tr->lookupAll()->result();

        $arrData = array();
        $arrResult = array();
        $arrCabang = array();
        $amount=0;
        if(sizeof($tmp)>0){
            $arrDataTgl = array();
            foreach($tmp as $row){
                if(!isset($arrDataTgl['sum_harian'][$row->cabang_id][$row->fulldate])){
                    $arrDataTgl['sum_harian'][$row->cabang_id][$row->fulldate] = 0;
                }
                $arrCabang[$row->cabang_id] = $row->cabang_nama;
                $arrDataTgl['sum_harian'][$row->cabang_id][$row->fulldate] += $row->transaksi_nilai;
                $arrDataTgl['count'][$row->cabang_id][$row->fulldate][] = $row->transaksi_nilai;
                $arrDataTgl['trxList'][$row->cabang_id][$row->fulldate][] = array("transaksi_nilai"=>$row->transaksi_nilai, "nomer_top"=>$row->nomer_top);
            }
            foreach($arrCabang as $cabId=>$cabNama){
                foreach($availTgl as $k=>$tgls){
                    $arrData[$cabId][$tgls]['jml_inv_jual'] = isset($arrDataTgl['count'][$cabId][$tgls]) ? count($arrDataTgl['count'][$cabId][$tgls]) : 0;
                    $arrData[$cabId][$tgls]['total_jual'] = isset($arrDataTgl['sum_harian'][$cabId][$tgls]) ? $arrDataTgl['sum_harian'][$cabId][$tgls] : 0;
                    $arrData[$cabId][$tgls]['rata2_jual'] = isset($arrDataTgl['sum_harian'][$cabId][$tgls]) && count($arrDataTgl['count'][$cabId][$tgls])>0 ? ($arrDataTgl['sum_harian'][$cabId][$tgls]/count($arrDataTgl['count'][$cabId][$tgls])) : 0.00;
                    $arrData[$cabId][$tgls]['min_jual'] = isset($arrDataTgl['count'][$cabId][$tgls]) ? min($arrDataTgl['count'][$cabId][$tgls]) : 0;
                    $arrData[$cabId][$tgls]['max_jual'] = isset($arrDataTgl['count'][$cabId][$tgls]) ? max($arrDataTgl['count'][$cabId][$tgls]) : 0;
                    $arrData[$cabId][$tgls]['hari_jual'] = date('l', strtotime($tgls));
                    $arrData[$cabId][$tgls]['label'] = date('d', strtotime($tgls));
                    $arrData[$cabId][$tgls]['trxList'] = isset($arrDataTgl['trxList'][$cabId][$tgls]) ? $arrDataTgl['trxList'][$cabId][$tgls] : array();;
                }
            }
        }

        $arrResult = array(
            "dateAvail" => $availTgl,
            "cabang" => $arrCabang,
            "data" => $arrData,
        );

        arrprint($arrResult);
        //        $this->response($arrResult, 200);


    }
    public function askMonthsOfSales_get()
    {
        $cabID = $this->uri->segment(4);
        $months = sizeof($this->uri->segment(5)) ? $this->uri->segment(5) : 24;

        function getLastNMonths($months, $format = 'Y-m'){
            $m = date("m"); $de= date("d"); $y= date("Y");
            $monthArray = array();
            for($i=0; $i<=$months-1; $i++){
                $monthArray[] = '' . date($format, strtotime("-$i month") )  . '';
            }
            return array_reverse($monthArray);
        }

        $availBln = array();
        foreach(getLastNMonths($months, 'Y-m') as $k=>$month){
            $availBln[] = $month;
        }


        $tr=new MdlTransaksi();
        $tr->addFilter("jenis='582'");

        $current = current($availBln);

        $current = date("Y-m-d", strtotime("$current-1"));

        $end = end($availBln);
        $end = date("Y-m-t", strtotime("$end-1"));


        $period = new DatePeriod(
            new DateTime($current),
            new DateInterval('P1D'),
            new DateTime($end)
        );

//        cekHere($current . " - " . $end);
//        foreach ($period as $key => $value) {
//            cekHere( $value->format('Y-m-d') );
//        }


//        if($current===$end){
//            $this->db->where("transaksi.fulldate>='" . $current . "'");
//            $this->db->where("transaksi.fulldate<='" . $current . "'");
//        }
//        else{
//            $this->db->where("transaksi.fulldate>='" . $current . "'");
//            $this->db->where("transaksi.fulldate<='" . $end . "'");
//        }
//
//        $tr->addFilter("transaksi.cabang_id='".$cabID."'");
        $tmp=$tr->lookupHistories(200, 200, 1)->result();


        $arrTmpData = array();
        $arrResult = array();


        if(sizeof($tmp)>0){
            foreach($tmp as $row){

                    foreach ($row as $key1 => $value) {

//                        if ($key == 'transaksi_nilai'){
//                        foreach ($period as $key2 => $tgl) {
//                            cekHijau($row->fulldate);
//                            if($row->fulldate){
//                                if( !isset($arrTmpData[$row->fulldate]['transaksi_nilai']) ){
//                                    $arrTmpData[$row->fulldate]['transaksi_nilai'] = 0;
//                                }
//                                $arrTmpData[$row->fulldate]['transaksi_nilai'] += $value;
//                                $arrTmpData['invoice'][$row->fulldate][] = $value;
//                            }
//                            else{
//                                if( !isset($arrTmpData[$tgl->format('Y-m-d')]['transaksi_nilai']) ){
//                                    $arrTmpData[$tgl->format('Y-m-d')]['transaksi_nilai'] = 0;
//                                }
//                                $arrTmpData[$tgl->format('Y-m-d')]['transaksi_nilai'] = 0;
//                                $arrTmpData['invoice'][$tgl->format('Y-m-d')] = 0;
//                            }
//                        }
                    }
//                }
            }

//            foreach ($period as $key => $value) {
//                if( !isset($arrResult[$value->format('Y-m-d')]) ){
//                    $arrResult[$value->format('Y-m-d')] = array();
//                }
//                if( isset($arrTmpData[$value->format('Y-m-d')]) ){
//                    $arrResult[$value->format('Y-m-d')] = $arrTmpData[$value->format('Y-m-d')];
//                }
//                arrPrint($arrTmpData[$value->format('Y-m-d')]);
//            }

        }

//        arrPrint($arrTmpData);

    }

    public function askMonthsOfSalesTmp_get()
    {

        $cabID = $this->uri->segment(4);
        $month = $this->uri->segment(5);
//        $month = $this->getMonthNumber($month);
//        $lastMonth = $this->getMonthNumberBefore($month);
        $tr=new MdlTransaksi();

        $tr->addFilter("jenis='582'");
//        if($month==date("Y-m")){
//            $this->db->where("fulldate>='" . date("Y-m-01") . "'");
//            $this->db->where("fulldate<='" . date("Y-m-d") . "'");
//        }else{
//            $this->db->where("fulldate>='" . date("$month-01") . "'");
//            $this->db->where("fulldate<='" . date("$month-31") . "'");
//        }

//        $tr->addFilter("transaksi.cabang_id='".$cabID."'");
//        $tmp=$tr->lookupHistories(1000, 1000, 1)->result();
        $tmp=$tr->lookupAll()->result();

//        cekHere( $this->db->last_query() );



        $arrData=array();

        $result = array(
            "cabang" => array(),
            "data" => array()
        );

        $dates=array();
        $cabang=array();

        if(sizeof($tmp)>0){

            $tmpData=array();
            foreach($tmp as $row){

                if(!isset($tmpData['sum_all_percabang'][$row->cabang_id])){
                    $tmpData['sum_all_percabang'][$row->cabang_id] = 0;
                }

                if(!isset($tmpData['count'][$row->cabang_id])){
                    $tmpData['count'][$row->cabang_id] = array();
                }

                if(!isset($tmpData['inv'][$row->cabang_id][date('Y-m', strtotime($row->fulldate))])){
                    $tmpData['inv'][$row->cabang_id][date('Y-m', strtotime($row->fulldate))] = array();
                }

                if(!isset($tmpData['inv_sum'][$row->cabang_id][date('Y-m', strtotime($row->fulldate))])){
                    $tmpData['inv_sum'][$row->cabang_id][date('Y-m', strtotime($row->fulldate))] = 0;
                }


                $tmpData['sum_all_percabang'][$row->cabang_id]+=$row->transaksi_nilai;
                $tmpData['count'][$row->cabang_id][] = $row->transaksi_nilai;

                $tmpData['inv_sum'][$row->cabang_id][date('Y-m', strtotime($row->fulldate))] += $row->transaksi_nilai;
                $tmpData['inv'][$row->cabang_id][date('Y-m', strtotime($row->fulldate))][] = $row->transaksi_nilai;

                $cabang[$row->cabang_id] = $row->cabang_nama;
            }

            foreach($cabang as $cabId => $cabNama){
                $arrData[$cabId]['jml_inv_jual'] = isset($tmpData['count'][$cabId]) ? count($tmpData['count'][$cabId]) : 0;
                $arrData[$cabId]['total_jual'] = isset($tmpData['sum_all_percabang'][$cabId]) ? $tmpData['sum_all_percabang'][$cabId] : 0.00;
                $arrData[$cabId]['rata2_jual'] = isset($tmpData['sum_all_percabang'][$cabId]) && count($tmpData['count'][$cabId])>0 ? ($tmpData['sum_all_percabang'][$cabId]/count($tmpData['count'][$cabId])) : 0.00;
                $arrData[$cabId]['min_jual'] = isset($tmpData['count'][$cabId]) ? min($tmpData['count'][$cabId]) : 0.00;
                $arrData[$cabId]['max_jual'] = isset($tmpData['count'][$cabId]) ? max($tmpData['count'][$cabId]) : 0.00;
                $arrData[$cabId]['trx_list'] = $tmpData['inv'][$cabId];
                $arrData[$cabId]['inv_sum'] = $tmpData['inv_sum'][$cabId];
            }

        }

        $result = array(
            "cabang" => $cabang,
            "data" => $arrData,
        );
        arrPrint($result);
    }
}