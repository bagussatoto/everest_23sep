<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 6/2/2019
 * Time: 7:54 PM
 */
class StockLocker extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }
    function viewCurrentLockers(){
        $cabID=$this->session->login['cabang_id'];
        $whID=$this->session->login['gudang_id'];
        $this->load->model("Mdls/MdlLockerStock");
        $q=isset($_GET['q'])&&strlen($_GET['q'])?$_GET['q']:"";
        $lo=new MdlLockerStock();
        $lo->addFilter("cabang_id='$cabID'");
        $lo->addFilter("gudang_id='$whID'");
        $this->db->where("transaksi_id='0'");
        $this->db->group_start();
            $this->db->where("state='active'");
            $this->db->or_where("state='hold'");
        $this->db->group_end();

        if(strlen($q)>0){
            $tmp=$lo->lookupByKeyword($q)->result();
        }else{
            $tmp=$lo->lookupAll()->result();
        }


//        cekbiru($this->db->last_query());
        $items=array();
        $states=array(
            "active",
            "hold",
        );
        $stocks=array();
        $stockItems=array();
        if(sizeof($tmp)>0){
            foreach($tmp as $row){

                if(!array_key_exists($row->produk_id,$items)){
                    $items[$row->produk_id]=$row->nama;
                }
                if(!isset($stocks[$row->produk_id])){
                    $stocks[$row->produk_id]=array();
                }
                if(!isset($stocks[$row->produk_id][$row->state])){
                    $stocks[$row->produk_id][$row->state]=0;
                }
                $stocks[$row->produk_id][$row->state]+=$row->jumlah;
            }
        }

//        arrprint($stocks);

        $headerFields=array();
        if(sizeof($items)>0){
            $tmpItem=array();
            $headerFields=array("name"=>"item name");
            foreach($items as $iID=>$iName){
                $tmpItem['id']=$iID;
                $tmpItem['name']=$iName;
                foreach($states as $stName){
                    $tmpItem[$stName]=isset($stocks[$iID][$stName])?$stocks[$iID][$stName]:0;
                    $headerFields[$stName]=$stName;
                }
                $tmpItem['link']="";
                $stockItems[]=$tmpItem;
            }
        }


//        $data=array(
//            "states"=>$states,
//            "items"=>$items,
//            "stocks"=>$stocks,
//        );
        $data = array(
            "mode"         => "saldo",
            "title"        => "active stocks",
            "subTitle"     => "current stocks".(strlen($q)>0?" matched '$q'":""),
            "items"        => $stockItems,
            //            "headerFields" => $balConfig['viewedColumns'],
            "headerFields" => $headerFields,
            "thisPage"=>base_url().get_class($this)."/".$this->uri->segment(2),
            "thisURL"=>base_url().get_class($this)."/".$this->uri->segment(2)."?",
            "q"=>$q,

            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",


        );
        $this->load->view("ledger", $data);

    }

    /**
     * Heartbeat endpoint untuk memperpanjang masa berlaku kunci transaksi (Short-Lived Lock)
     * Dipanggil secara periodik (setiap 30 detik) oleh client.
     */
    public function heartbeat()
    {
        if (!headers_sent()) {
            header("Content-Type: application/json; charset=UTF-8");
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Pragma: no-cache");
        }

        if (!isset($this->session->login['id'])) {
            echo json_encode(array(
                "success" => 0,
                "status" => "unauthorized",
                "message" => "Sesi login tidak valid.",
            ));
            return;
        }

        $rawInput = file_get_contents('php://input');
        $inputJson = !empty($rawInput) ? json_decode($rawInput, true) : array();

        $transaksi_id = $this->input->get_post('transaksi_id');
        if (empty($transaksi_id) && isset($inputJson['transaksi_id'])) {
            $transaksi_id = $inputJson['transaksi_id'];
        }

        $lock_token = $this->input->get_post('lock_token');
        if (empty($lock_token) && isset($inputJson['lock_token'])) {
            $lock_token = $inputJson['lock_token'];
        }

        if (empty($transaksi_id)) {
            echo json_encode(array(
                "success" => 0,
                "status" => "error",
                "message" => "Parameter transaksi_id wajib diisi.",
            ));
            return;
        }

        $myId = $this->session->login['id'];
        $dtimeNow = dtimeNow();
        $ttlSeconds = 60;
        $expiresAt = date("Y-m-d H:i:s", time() + $ttlSeconds);

        $this->load->model("Mdls/MdlLockerTransaksi");
        $lt = new MdlLockerTransaksi();
        $lt->setFilters(array());
        $lt->addFilter("transaksi_id='$transaksi_id'");
        $lt->addFilter("state='hold'");
        $lt->addFilter("jumlah='1'");
        $records = $lt->lookupAll()->result();

        if (sizeof($records) === 0) {
            echo json_encode(array(
                "success" => 0,
                "status" => "lost",
                "message" => "Kunci transaksi tidak ditemukan atau sudah dilepas.",
            ));
            return;
        }

        $activeHolder = $records[0];
        if ($activeHolder->oleh_id != $myId) {
            echo json_encode(array(
                "success" => 0,
                "status" => "taken",
                "holder_name" => $activeHolder->oleh_nama,
                "holder_id" => $activeHolder->oleh_id,
                "message" => "Kunci transaksi telah diambil alih oleh " . $activeHolder->oleh_nama . ".",
            ));
            return;
        }

        if (!empty($lock_token) && !empty($activeHolder->lock_token) && $activeHolder->lock_token !== $lock_token) {
            echo json_encode(array(
                "success" => 0,
                "status" => "token_mismatch",
                "message" => "Token kunci tidak sesuai.",
            ));
            return;
        }

        $updateData = array(
            "heartbeat_at" => $dtimeNow,
            "expires_at" => $expiresAt,
            "owner_session_id" => session_id(),
            "owner_ip" => $this->input->ip_address(),
        );
        if (empty($activeHolder->lock_token) && !empty($lock_token)) {
            $updateData['lock_token'] = $lock_token;
        }
        if (empty($activeHolder->locked_at)) {
            $updateData['locked_at'] = $dtimeNow;
        }

        $this->db->where(array(
            "id" => $activeHolder->id,
            "transaksi_id" => $transaksi_id,
            "state" => "hold",
        ))->update("stock_locker_transaksi", $updateData);

        echo json_encode(array(
            "success" => 1,
            "status" => "renewed",
            "transaksi_id" => $transaksi_id,
            "lock_token" => !empty($activeHolder->lock_token) ? $activeHolder->lock_token : $lock_token,
            "heartbeat_at" => $dtimeNow,
            "expires_at" => $expiresAt,
            "ttl" => $ttlSeconds,
        ));
    }

    /**
     * Release endpoint untuk melepaskan kunci transaksi (saat batal atau navigasi keluar)
     * Dapat dipanggil via AJAX biasa atau navigator.sendBeacon
     */
    public function release()
    {
        if (!headers_sent()) {
            header("Content-Type: application/json; charset=UTF-8");
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Pragma: no-cache");
        }

        if (!isset($this->session->login['id'])) {
            echo json_encode(array(
                "success" => 0,
                "status" => "unauthorized",
                "message" => "Sesi login tidak valid.",
            ));
            return;
        }

        $rawInput = file_get_contents('php://input');
        $inputJson = !empty($rawInput) ? json_decode($rawInput, true) : array();

        $transaksi_id = $this->input->get_post('transaksi_id');
        if (empty($transaksi_id) && isset($inputJson['transaksi_id'])) {
            $transaksi_id = $inputJson['transaksi_id'];
        }

        $lock_token = $this->input->get_post('lock_token');
        if (empty($lock_token) && isset($inputJson['lock_token'])) {
            $lock_token = $inputJson['lock_token'];
        }

        if (empty($transaksi_id)) {
            echo json_encode(array(
                "success" => 0,
                "status" => "error",
                "message" => "Parameter transaksi_id wajib diisi.",
            ));
            return;
        }

        $myId = $this->session->login['id'];
        $dtimeNow = dtimeNow();

        $this->db->where(array(
            "transaksi_id" => $transaksi_id,
            "oleh_id" => $myId,
            "state" => "hold",
        ))->update("stock_locker_transaksi", array(
            "jumlah" => "0",
            "released_at" => $dtimeNow,
            "released_by" => $myId,
            "expires_at" => null,
            "lock_token" => null,
        ));

        echo json_encode(array(
            "success" => 1,
            "status" => "released",
            "transaksi_id" => $transaksi_id,
            "message" => "Kunci transaksi berhasil dilepas.",
        ));
    }
}
// END OF COMPLETE REPEATED LOGIC