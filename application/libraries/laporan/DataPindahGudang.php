<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class DataPindahGudang
{
    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->helper("he_mass_table");
        //
        // $this->modul_path = base_url() . "penjualan/";
        // $this->jenisTr = "4666";
        // $this->jenisTrs = array("582spo", "382spo");
        // $this->default_limit = 100;

// mati_disini(__LINE__ . __FILE__ . __DIR__);
    }

    public function produkOutstanding($get_date2, $get_condites = "")
    {


        $this->ci->load->model("Coms/ComRekeningTransaksiPembantu");
        $ps = new ComRekeningTransaksiPembantu();

        $sortings = array(
            "kolom" => "id",
            "mode"  => "desc",
        );
        $jenisTr = "582spo";
        $ps->setSortBy($sortings);
        // $ps->setJenisTr($jenisTr);
        // $ps->setJenisTr("582");
        $condites = array(
            "rekening" => "582pkd",
            // "qty_kredit_lap >" => "0",
            // "year(dtime) >" => "2020",
            "periode"  => "forever",
        );

        // $this->db->where($condites);
        // $this->db->group_by("extern_id");
        $this->ci->db->order_by("id", "asc");
        $this->ci->db->where($condites);
        $src_000 = $ps->fetchCache("persediaan_produk");
        // $src_000 = $ps->callOutstanding("persediaan_produk");
        // $reqData_000 = $src_000['raw'];
        // showLast_query("kuning");
        // cekBiru(sizeof($src_000));
        // cekBiru($masterData_ori);
        // cekHijau(ipadd());
        // arrPrint($src_000);
        // mati_disini(__LINE__);
        $masterIds = array();
        $produkIds = array();
        $reqData_000 = array();
        $reqData_now = array();
        foreach ($src_000 as $item_0) {
            $mt_id = $item_0->master_id;
            $ext_id = $item_0->extern_id;
            $q_kredit_lap = $item_0->qty_kredit_lap * 1;
            $v_kredit_lap = $item_0->kredit_lap * 1;


            // --------PRODUK-------------------------------------------------------------
            if (!isset($srcProduk[$ext_id]['sum_qty_kredit'])) {
                $srcProduk[$ext_id]['sum_qty_kredit'] = 0;
            }
            $srcProduk[$ext_id]['sum_qty_kredit'] += $q_kredit_lap;

            if (!isset($srcProduk[$ext_id]['sum_kredit'])) {
                $srcProduk[$ext_id]['sum_kredit'] = 0;
            }
            $srcProduk[$ext_id]['sum_kredit'] += $v_kredit_lap;


            $masterIds[$mt_id] = $mt_id;
            $produkIds[$ext_id] = $ext_id;
            unset($item_0->kredit_lap);
            if ($q_kredit_lap > 0) {
                $val_kredit_lap = $v_kredit_lap;
            }
            else {
                $val_kredit_lap = 0;
            }

            $reqData_000[$mt_id][$ext_id] = (array)$item_0;

            $dataPrevs = array(
                'prev_qty_debet'  => 0,
                'prev_qty_kredit' => 0,
                'prev_kredit'     => 0,

            );
            $dataPrevs['now_qty_kredit'] = $q_kredit_lap;
            $dataPrevs['now_kredit'] = $val_kredit_lap;
            $dataPrevs['kredit_lap'] = $val_kredit_lap;

            $reqData_now[] = (array)$item_0 + $dataPrevs;
        }

        /* ----------------------------------------------------------
         * 582spo
         * ----------------------------------------------------------*/
        $src_spos = array();
        if(sizeof($masterIds) > 0){

            $condites = array(
                "rekening" => "582spo",
                "periode"  => "forever",
            );

            $this->ci->db->where($condites);
            $this->ci->db->where_in('master_id', $masterIds);
            // $this->db->group_by("extern_id");
            $this->ci->db->order_by("id", "asc");
            // $this->db->where($condites);
            $src_spos = $ps->fetchCache("persediaan_produk");
        }

        foreach ($src_spos as $item_spo) {
            //    qty_debet_lap
            $spo_mast_id = $item_spo->master_id;
            $spo_ext_id = $item_spo->extern_id;
            $spo_debet = $item_spo->debet_lap * 1;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['spo_qty_debet_lap'] = $item_spo->qty_debet_lap;
            $spo_datas[$item_spo->master_id][$item_spo->extern_id]['spo_debet_lap'] = $spo_debet;

        }

        /* -----------------------------------------------------------
        * produk spek
        * -----------------------------------------------------------*/
        // $produkIds = "";
        // $transaksiIds = "";
        $prSpeks = array();
        if(sizeof($produkIds) > 0){
            $this->ci->load->model("Mdls/MdlProduk");
            $pr = new MdlProduk();
            $prSpeks = $pr->callSpecs($produkIds);
        }

        /* --------------------------------------------------------------------------
         * pengabungan
         * --------------------------------------------------------------------------*/
        $otproduk = array();
        foreach ($prSpeks as $produk_id => $prSpek) {

            $outProduk = $srcProduk[$produk_id];
            $otproduk[] = (array)$prSpek + $outProduk;

        }

        $otraws = array();
        foreach ($reqData_now as $outraw) {
            // arrPrintPink($outraw);
            // break;
            $prod_id = $outraw['extern_id'];
            $mast_id = $outraw['master_id'];
            $oleh_id = $outraw['oleh_id'];
            $seller_id = $outraw['seller_id'];
            $customer_id = $outraw['customer_id'];
            $cabang_id = $outraw['cabang_id'];
            $data_spo = isset($spo_datas[$mast_id][$prod_id]) ? $spo_datas[$mast_id][$prod_id] : array();
            // arrPrint($data_spo);
            // cekMerah(__LINE__);
            $otraws[] = (isset($prSpeks[$prod_id]) ? (array)$prSpeks[$prod_id] : array()) + $outraw + $data_spo;
        }

        // cekBiru(sizeof($reqData_now));
        // cekBiru($reqData_000);
        // mati_disini(__LINE__);
        /* ---------------------------------------------------------------------
        * filter khusus
        * ---------------------------------------------------------------------*/
        // arrPrintHijau($_GET);

            if (isset($_GET['ky'])) {
                $get_condites = array(
                    $_GET['ky'] => $_GET[$_GET['ky']]
                );
            $this->ci->db->where($get_condites);
        }

        $bl_yglalu = previousMonth($get_date2);
        $bl_yglalu_t = formatTanggal($bl_yglalu, 'Y-m-t');
        // cekHere($bl_yglalu . " " . formatTanggal($bl_yglalu, 'Y-m-t'));
        $condites = array(
            // "date(dtime)>=" => $get_date1,
            "date(dtime)" => $bl_yglalu_t,
        );
        $this->ci->db->where($condites);
        $src_001 = $ps->callOutstandingBulanan("persediaan_produk");
        $dt_gylalus = $src_001['raw'];
        // cekKuning(sizeof($dt_gylalus));

        // arrPrint($dt_gylalus);
        // mati_disini(__LINE__);

        $dBulanan = array();
        foreach ($dt_gylalus as $itembl) {
            $masterDatum['prev_qty_debet'] = $itembl['qty_debet_lap'] * 1;
            $masterDatum['prev_qty_kredit'] = $itembl['qty_kredit_lap'] * 1;
            $masterDatum['prev_kredit'] = $itembl['kredit_lap'] * 1;
            $masterDatum['now_qty_kredit'] = 0;
            $masterDatum['now_kredit'] = 0;
            $masterDatum['spo_qty_debet_lap'] = 0;
            $masterDatum['spo_debet_lap'] = 0;
            $masterDatum['qty_debet_lap'] = 0;
            $masterDatum['debet_lap'] = 0;
            $masterDatum['qty_kredit_lap'] = $itembl['qty_kredit_lap'] * 1;
            $masterDatum['kredit_lap'] = $itembl['kredit_lap'] * 1;

            $dBulanan[] = $masterDatum + $itembl;
        }
        // arrPrintPink($src_001['raw']);
        // cekPink(sizeof($dBulanan));
        // arrPrintPink($dBulanan);
        // arrPrintPink($otraws);

        $main_datas = array_merge($otraws, $dBulanan);

        return $main_datas;
    }


    public function produkMoved($date1,$date2,$cabang_id,$condites=""){
        $this->ci->load->model("Coms/ComRekeningPembantuProduk");
        $this->ci->load->model("Mdls/MdlProduk");
        // $date_1 = date("Y-m-d",$date1);
        // $date_2 = date("Y-m-d",$date2);

        //untuk testing
        // $date_1 = "2022-06-01";
        // $date_2 = "2022-06-31";

        //endregion
        $p= new MdlProduk();
        $m = new ComRekeningPembantuProduk();
// matiHEre($date1." ".$date2);
        $m->setJenisTr("1587");
        $m->setFilters(array());
        if(!empty($date1)){
            $m->addFilter("date(dtime) >='$date1'");
            $m->addFilter("date(dtime) <='$date2'");
            $m->addFilter("cabang_id ='$cabang_id'");
            $this->ci->db->order_by("id","asc");
        }
        else{

        }

        /*
         * rekening persediaan produk  wajib diganti coa code jika sudah pindah mode COA
         */
        $tempMoved = $m->callMovementProduk("persediaan produk");//catetan rekening siap" ganti ke coa ya
        // arrPrint($tempMoved);
        // matiHere();
        //tambah data duang tujuan dari transaksi
        // cekMErah($this->ci->db->last_query());
// arrPrint($tempMoved);
        return $tempMoved;
        // cekMErah(sizeof($tempMoved));
    }

    public function produkGudangMoved($date1,$date2,$cabang_id,$limit=""){

        //return array produk[dataProduk][produk_id][fields]=>value fields
        //returm array produk[dataGudang][produk_id][gudang_id][] = data
        $fieldProduk = array(
            "extern_id"=>"extern_id",
            "extern_nama"=>"extern_nama",
            "kode" =>"kode",
            "label" =>"label",
            "no_part" =>"no_part",
        );

        $gudangFields = array(
            "qty_debet_awal"=>"qty_debet",
            "qty_debet"=>"qty_debet",
            "qty_debet_akhir"=>"qty_debet",
            "debet_awal"=>"debet",
            "debet"=>"debet",
            "debet_akhir"=>"debet",
            "qty_kredit"=>"qty_kredit",
            "qty_kredit_awal"=>"qty_kredit",
            "qty_kredit_akhir"=>"qty_kredit",
            "kredit_awal"=>"kredit",
            "kredit"=>"kredit",
            "kredit_akhir"=>"kredit",
            "dtime"=>"dtime",
            "transaksi_id"=>"transaksi_id",
            "transaksi_no"=>"transaksi_no",
        );
        $this->ci->load->model("Coms/ComRekeningPembantuProduk");
        $this->ci->load->model("Mdls/MdlProduk");
        $p= new MdlProduk();
        $m = new ComRekeningPembantuProduk();
        // matiHEre($date1." ".$date2);
        $m->setJenisTr("1587");
        $m->setFilters(array());
        if(!empty($date1)){
            $m->addFilter("date(dtime) >='$date1'");
            $m->addFilter("date(dtime) <='$date2'");
            $m->addFilter("cabang_id ='$cabang_id'");
            $this->ci->db->order_by("id","asc");
        }
        else{
            // $this->ci->db->limit($limit);
            // $this->ci->db->order_by("id","asc");
// matiHEre($date1."||".__LINE__);
        }

        $tempMoved = $m->callMovementProduk("persediaan produk");//catetan rekening siap" ganti ke coa ya
        // arrPrint($tempMoved);
        // matiHere();
        //tambah data duang tujuan dari transaksi
        // cekMErah($this->ci->db->last_query());
        // arrPrint($tempMoved);
        $produk = array();
        $produks = array();
        foreach($tempMoved["data"] as $xx=> $dataTemp){
            // arrPrint($dataTemp);
            foreach($fieldProduk as $key =>$keyLabel){
                $produks["produkData"][$dataTemp["extern_id"]][$key]=isset($dataTemp[$key]) ? $dataTemp[$key]:"";
            }
            $temp = array();
            foreach($gudangFields as $keyGud =>$gudLabel){
                $temp[$keyGud]=$dataTemp[$keyGud];
            }
            $produk["gudangData"][$dataTemp["extern_id"]][$dataTemp["gudang_id"]][]=$temp;
        }
        // arrPrint($produk);

        foreach($produk["gudangData"] as $PID =>$tempGudangData){
            // arrPrint($tempGudangData);

            foreach($tempGudangData as $Gid =>$ListProdukMoved){
                $prev_data = $ListProdukMoved[0]["qty_debet_awal"];

                if(!isset($produks["gudangData"][$PID][$Gid]["sum_qty_debet_awal"])){
                    $produks["gudangData"][$PID][$Gid]["sum_qty_debet_awal"] = 0;
                }
                $produks["gudangData"][$PID][$Gid]["sum_qty_debet_awal"] = $prev_data;
                $totalDebet_qty = 0;
                $totalKredit_qty = 0;
                foreach($ListProdukMoved as $ii =>$temp){
                    $qty_debet = $temp["qty_debet"];
                    $value_debet = $temp["debet"];
                    $value_debet_akhir = $temp["debet_akhir"];
                    $qty_kredit = $temp["qty_kredit"];
                    $qty_kredit_akhir = $temp["qty_kredit_akhir"];
                    $value_kredit = $temp["kredit"];
                    $value_kredit_akhir = $temp["kredit_akhir"];
                    $totalDebet_qty +=$qty_debet;
                    $totalKredit_qty +=$qty_kredit;
                    // $qty_debet_akhir = $temp["qty_debet_akhir"];
                    // $qty_debet_akhir = $temp["qty_debet_akhir"];
                    $value_debet = $temp["debet"];
                    $value_debet_akhir = $temp["debet_akhir"];
                    $qty_kredit = $temp["qty_kredit"];
                    $qty_kredit_akhir = $temp["qty_kredit_akhir"];
                    $value_kredit = $temp["kredit"];
                    $value_kredit_akhir = $temp["kredit_akhir"];



                    if(!isset($produks["gudangData"][$PID][$Gid]["sum_qty_debet"])){
                        $produks["gudangData"][$PID][$Gid]["sum_qty_debet"]=0;
                    }

                    if(!isset($produks["gudangData"][$PID][$Gid]["sum_debet"])){
                        $produks["gudangData"][$PID][$Gid]["sum_debet"]=0;
                    }
                    if(!isset($produks["gudangData"][$PID][$Gid]["sum_debet_akhir"])){
                        $produks["gudangData"][$PID][$Gid]["sum_debet_akhir"]=0;
                    }


                    if(!isset($produks["gudangData"][$PID][$Gid]["sum_qty_kredit"])){
                        $produks["gudangData"][$PID][$Gid]["sum_qty_kredit"]=0;
                    }
                    if(!isset($produks["gudangData"][$PID][$Gid]["sum_qty_kredit_akhir"])){
                        $produks["gudangData"][$PID][$Gid]["sum_qty_kredit_akhir"]=0;
                    }
                    if(!isset($produks["gudangData"][$PID][$Gid]["sum_kredit"])){
                        $produks["gudangData"][$PID][$Gid]["sum_kredit"]=0;
                    }
                    if(!isset($produks["gudangData"][$PID][$Gid]["sum_kredit_akhir"])){
                        $produks["gudangData"][$PID][$Gid]["sum_kredit_akhir"]=0;
                    }
                    //summarry data mutasi

                    $produks["gudangData"][$PID][$Gid]["sum_qty_debet"] +=$qty_debet;
                    $produks["gudangData"][$PID][$Gid]["sum_debet"]+=$value_debet;
                    $produks["gudangData"][$PID][$Gid]["sum_debet_akhir"]+=$value_debet_akhir;
                    $produks["gudangData"][$PID][$Gid]["sum_qty_kredit"] +=$qty_kredit;
                    $produks["gudangData"][$PID][$Gid]["sum_qty_kredit_akhir"]+=$qty_kredit_akhir;
                    $produks["gudangData"][$PID][$Gid]["sum_kredit"]+=$value_kredit;
                    $produks["gudangData"][$PID][$Gid]["sum_kredit_akhir"]+=$value_kredit_akhir;
                }
                if(!isset($produks["gudangData"][$PID][$Gid]["sum_qty_debet_akhir"])){
                    $produks["gudangData"][$PID][$Gid]["sum_qty_debet_akhir"]=0;
                }
                if(!isset($produks["gudangData"][$PID][$Gid]["sum_qty_kredit"])){
                    $produks["gudangData"][$PID][$Gid]["sum_qty_kredit"]=0;
                }
                $produks["gudangData"][$PID][$Gid]["sum_qty_debet_akhir"] += ($prev_data+$totalDebet_qty)-$totalKredit_qty;
                $produks["gudangData"][$PID][$Gid]["sum_qty_kredit"]=$totalKredit_qty;



            }
        }

        return $produks;
        // arrPrint($produks);
        // cekMErah($this->ci->db->last_query());
        // matiHEre("$date1|| $date2 ".__LINE__);
    }
}