<?php


class CliTransaksiLocker extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();


//        $this->load->model("MdlTransaksi");
//        $this->load->model("Mdls/MdlCliLogTime");
//        $this->load->model("Coms/ComJurnal");

    }

    public function run_cliTransaksiLocker()
    {
//        header("refresh:5");
//        mati_disini();
        $startDate = dtimeNow();

        $tabel = "stock_locker_transaksi";
        $arrWhere = array(
            "state" => "hold",
            "jumlah>" => "0",
        );
        $this->db->where($arrWhere);
        $tmp = $this->db->get($tabel)->result();
        showLast_query("biru");
        cekBiru(sizeof($tmp));
//        arrPrintWebs($tmp);

        if (sizeof($tmp) > 0) {
            $this->db->trans_start();

            foreach($tmp as $spec){
                $id = $spec->id;
                $jml = 0;
                $where = array(
                    "id" => $id,
                );
                $data = array(
                    "jumlah" => "0",
                );
                $this->db->where($where);
                $this->db->update($tabel, $data);
                showLast_query("merah");
            }



//            mati_disini("...tes cli transaksi locker releaser... ");


            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        }


    }

    /*
     * untuk cek locker yang gak balik saat cancel / reject
     */
    public function runCleansingLockerHold(){
        $this->load->helper("he_route");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlNotifLocker");
        $t = new MdlTransaksi();
        $n = new MdlNotifLocker();
        $l = new MdlLockerStock();
        $l->addFilter("jumlah>'0'");
        $l->addFilter("transaksi_id>'0'");
        $l->addFilter("state='hold'");
        $tmp = $l->lookUpAll()->result();
//        arrprint(count($tmp));
//        arrPrint($tmp);
        $listID = array();
        $tempData=array();
        if(count($tmp)>0){
            foreach($tmp as $tmp_0){
                $listID[]=$tmp_0->transaksi_id;
                $tempData[$tmp_0->transaksi_id][$tmp_0->produk_id] =array(
                    "produk_id"=>$tmp_0->produk_id,
                    "nama"=>$tmp_0->nama,
                    "jumlah"=>$tmp_0->jumlah,
                    "cabang_id"=>$tmp_0->cabang_id,
                    "gudang_id"=>$tmp_0->gudang_id,
                );
            }
        }

        $t->addFilter("id in ('".implode("','",$listID)."')");
        $t->addFilter("trash_4='1'");
        $temp = $t->lookUpAll()->result();
        $deleteData = array();
        if(count($temp)>0){
            foreach($temp as $temp0){
                $deleteData[$temp0->id]=$temp0->nomer;
                $deleteDataNotif[$temp0->id]=$temp0->id;
            }
        }

        $t->addFilter("transaksi_id in ('".implode("','",$deleteDataNotif)."')");
        $aa =$n->lookUpAll()->result();
        $oldNotif = array();
        if(count($aa)>0){
//            arrprint($aa);
            foreach ($aa as $bbb){
                $oldNotif[$bbb->transaksi_id]=$bbb->transaksi_id;
            }
        }
//        arrprint($oldNotif);
        if(count($oldNotif)>0){
            foreach($oldNotif as $old_trid =>$oldids){
                if(isset($deleteData[$old_trid])){
                    unset($deleteData[$old_trid]);
                }
            }
        }
//        matiHEre();
        $path = APPPATH;
        $tempAppath = explode("/",$path);
        $domain = $tempAppath[3];
        $datas = array();
        $this->db->trans_start();
        if(count($deleteData)>0){
            foreach($deleteData as $trID =>$nomer){
                if(isset($tempData[$trID])){

                    $pesan_tele2 = '*' . 'WARNING ' . $domain.'*' . PHP_EOL . PHP_EOL;


                    $pesan_tele2 .= '*' . 'TRID ' . $trID.'*' . PHP_EOL . PHP_EOL;
                    $pesan_tele2 .= '*' . 'NOMER ' . $nomer.'*' . PHP_EOL . PHP_EOL;
                    $produk_datas = "";
                    foreach($tempData[$trID] as $prID =>$prData){
                        $datas[$trID][] = $prData + array("transaksi_id"=>$trID,"nomer"=>$nomer);
                        $produk_datas .= '*' . 'CABANGID ' . $prData["cabang_id"].'*' . PHP_EOL . PHP_EOL;
                        $produk_datas .= '*' . 'GUDANGID ' . $prData["gudang_id"].'*' . PHP_EOL . PHP_EOL;
                        $produk_datas .= '*' . 'PID ' . $prData["produk_id"]." ".$prData["nama"]. " ".' jml : '.$prData["jumlah"].'*' . PHP_EOL . PHP_EOL;
                        $produk_datas .= '*' . 'PRODUK ' .$prData["nama"]. '*' . PHP_EOL . PHP_EOL;



                    }
                    $pesan_tele2 .=$produk_datas;
                    $pesan_tele2 .= '*' . 'STOK LOCKER HOLD  TIDAK SINKRON segera dicek.' . '*' . PHP_EOL;
                    $pesan_tele2 .= '*' . 'Transaksi sudah di cancel/reject .' . date("Y-m-d H:i") . '*' . PHP_EOL;
                    $insertData = array(
                        "transaksi_id"=>$trID,
                        "msg"=>$pesan_tele2,
                        "status"=>1,
                    );
//                    matiHere();
                    $n->addData($insertData);
//                    cekHitam($this->db->last_query());
                    $chat_id_warnet = "-1001457771609"; //teknis
                    //                    $chat_id = $chatID[$id_op]; //id OP
                    $token = "2006804072:AAF1qUtWoF88THjnMdDkXmPAhY0XnRYaGPs"; //mayagraha
                    $arrData2 = array(
                        "tele_chat_id" => "$chat_id_warnet",
                        "tele_token" => "$token",
                        "tele_conten_jenis" => "text",
                        "tele_isi" => "$pesan_tele2",
                        "address_img" => "",
                        "tipe" => "tele",
                        "status" => "1",
                        "dtime" => date("Y-m-d H:i"),
                        "fulldate" => date("Y-m-d"),
                    );
//                        $m->addData($arrData2);
                    kirim_tele_route($pesan_tele2, $chat_id_warnet, $token);
                }
            }
        }
        else{
            echo "tidak ada locker yang geseh karena cancel";
        }
//        matiHere(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
//        cekHitam($this->db->last_query());
//        arrprint($datas);
    }


}