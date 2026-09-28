<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 03/04/2019
 * Time: 13.50
 */

class TestMongo extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Mdls/MdlMongoMother");

    }

    public function index()
    {
        $m = new MdlMongoMother();
//        $m->setTableName("test");//aka collection
        $this->load->model("MdlTransaksi");
        $t = new MdlTransaksi();
        $t->setFilters(array());
        $t->addFilter("cli_loop='1'");
        $f = $t->lookupDataCount();
        cekHere($f);



        //curent need
        $t->setFilters(array());
        $t->addFilter("cli_loop='0'");
        $g = $t->lookupDataCount();

        $need = $g-$f;
        cekHere("total data :".$g." sukses diambil : ".$f. " sisa data :" .$need);

//        $arrayData = array(
//            "name"=>"jono",
//            "place" =>"boolang",
//            "glace" =>"higra",
//        );
//        $m->addData($arrayData);
//        $tr = $m->lookUpMainTransaksi();
//        cekLime(__debugInfo());
//        arrPrint($tr->toArray());
        //test insert dulu

//
//foreach($tr as $gg){
//    arrPrint($gg);
//}
//        var_dump($tr);
        cekBiru();
    }

    public function View()
    {

    }

    public function GrabDataV1()
    {
        $starttime = microtime(true);
        $this->load->model("MdlTransaksi");
        $m = new MdlMongoMother();
        $t = new MdlTransaksi();

        $t->setFilters(array());
        $this->db->order_by("id", "desc");
//        $this->db->setSortBy(array("kolom"=>"id","mode"=>"desc"));
        $t->addFilter("cli_loop='0'");
        $this->db->limit(1);
        $er = $t->lookupAll()->result();
        $collMaster = $t->getFields();
        $coll = $collMaster['main'];
//        "indexing_details"
        if (sizeof($er) > 0) {
            $transksi_main = (array)$er[0];
            //region insert table transaksi
            $m->setTableName("transaksi");
            $m->addData($transksi_main);

            //endregion

            $link_id = $er[0]->link_id;
            $trID = $er[0]->id;
            cekMerah($trID);
            $indexingDetail = blobDecode($er[0]->indexing_details);
            $indexingID = blobDecode($er[0]->indexing_registry);

            //region transaksi_data
            $t->setFilters(array());
            if(is_array($indexingDetail) && sizeof($indexingDetail)>0){
                $t->addFilter("id in (" . implode(",", $indexingDetail) . ")");

            }else{
                $t->addFilter("transaksi_id='$trID'");
            }
            $t->setTableName("transaksi_data");
            $tmpChl = $t->lookupAll()->result();
            if(sizeof($tmpChl)> 0){
                $m->setTableName("transaksi_data");
                foreach ($tmpChl as $detTmp) {
                    $tm = (array)$detTmp;
                    $m->addData($tm);
//                    cekHitam("transksi data");
//                    arrPrint($tm);
                    //insertke table transaksi data mongo
                }
            }
            //endregion transaksi data

            //region transaksi main values dandetil values
            if($link_id > 0){
//                echo "no detilvalue dan main value karena link_id > 0 ";
            }else{
                cekBiru("transaksi_values");
                $t->setFilters(array());
                $detVal = $t->lookupMainValuesByTransID($trID)->result();

                if (sizeof($detVal) > 0) {
                    $m->setTableName("transaksi_values");
                    foreach ($detVal as $detTmp) {
                        cekHitam("transaksi_values");
                        $tm = (array)$detTmp;
                        $m->addData($tm);
                        //insertke table transaksi data mongo
                    }
                }
                //detil values
                //link_id > 0gak punya detil values
                $t->setFilters(array());
                $detVal2 = $t->lookupDetailValuesByTransID($trID)->result();
//                arrPrint($detVal2);
//                matiHEre();
                if (sizeof($detVal2) > 0) {
//                    cekBiru("biru ***");
                    $m->setTableName("transaksi_data_values");
                    foreach ($detVal2 as $detTmp) {
                        $tm = (array)$detTmp;
                        $m->addData($tm);
//                        cekHitam("transaksi_data_values");
//                        arrPrint($tm);
                    }
                }
            }
            //endregion


            //region registry
            $t->setFilters(array());
            if(is_array($indexingID) && sizeof($indexingID)>0){
                $t->addFilter("id in (" . implode(",", $indexingID) . ")");
                $reg = $t->lookupRegistries()->result();
//                arrPrint($reg);
                if(sizeof($reg)> 0){
                    $m->setTableName("transaksi_registry");
                    foreach($reg as $regTmp){
                        unset ($regTmp->values_intext);
//                        unset ($regTmp->indexing);
//                        unset ($regTmp->trash);
                        $m->addData((array)$regTmp);
//                        arrPrint((array)$regTmp);
                    }
                }

            }

            $t->setFilters(array());
            $signDatTmp = $t->lookupSignaturesByMasterID($trID)->result();
            if (sizeof($signDatTmp) > 0) {
                $m->setTableName("transaksi_sign");
                foreach ($signDatTmp as $signData) {
                    $sign = (array)$signData;
                    $m->addData($sign);
//                    cekHitam("transaksi_sign");
//                    arrPrint($sign);
                }
            }

            $update = array(
                "cli_loop" => "1",
            );
            $where = array(
                "id" => $trID,
            );
//            matiHEre();
            $this->db->trans_start();
            $t->setTableName('transaksi');
            $t->setFilters(array());
            $t->updateData($where, $update);
//            cekLime($this->db->last_query());
//            matiHere("hooppp");
            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
            $endtime = microtime(true); // Bottom of page
            $val =$endtime-$starttime;
//            echo("Grabing data Mongo ".$val." Seconds");
//        matiHere();
//            $t->setFilters(array());
//            $t->addFilter("cli_loop='1'");
//            $f = $t->lookupDataCount();
//            //curent need
//            $t->setFilters(array());
////            $t->addFilter("cli_loop='0'");
//            $g = $t->lookupDataCount();
//
//            $need = $g-$f;
//            echo("<hr>"."total data :".$g." sukses diambil : ".$f. " sisa data :" .$need);
            echo " current execute data id $trID ->lanjut brooo execution time $val";
        } else {
            cekHitam("data habis brooo");
            echo ("data habis broo");
//            matiHEre();
        }

    }

    public function GrabData()
    {
        $starttime = microtime(true);
        $this->load->model("MdlTransaksi");
        $m = new MdlMongoMother();
        $t = new MdlTransaksi();

//        $t->setFilters(array());
//        matiHere("mau ngapain broo");
        //region ini transksi
//        $t->setTableName('transaksi');
//        $t->setFilters(array());
//        $t->setSortBy(array());
//        $t->addFilter("cli_loop='0");
//        $this->db->limit(1000);
//////        $this->db->order_by("id", "asc");
//        $er = $t->LookUpAll()->result();
//        cekLime($this->db->last_query());
//        matiHEre();
        //endregion
        //region transalksi data values kelar
//        $t->setTableName("transaksi_data_values");
//        $t->setSortBy(array());
//        $t->setFilters(array());
//        $t->addFilter("cli_loop='0'");
//        $this->db->limit(2000);
// //        $this->db->order_by("id", "asc");
//        $er = $t->lookupAll()->result();
//        arrPrint($er);

        //endregion
        //region transaksi values
//        $t->setTableName("transaksi_values");
//        $t->setSortBy(array());
//        $t->setFilters(array());
//        $t->addFilter("transaksi_id='90605'");
//        // $t->addFilter("cli_loop='0'");
//        // $this->db->limit(3000);
// //        $this->db->order_by("id", "asc");
//        $er = $t->lookupAll()->result();


        //endregion
//        region transaksi_registry
//        $t->setTableName("transaksi_registry");
//        $t->setSortBy(array());
//        $t->setFilters(array());
//         $t->addFilter("transaksi_id='90605'");
//         // $t->addFilter("trash='0'");
//        // $t->addFilter("mongo='0'");
//        // $this->db->limit(1000);
// //        $this->db->order_by("id", "asc");
//        $er = $t->lookupAll()->result();
//        cekhitam(sizeof($er));
//        arrPrint(sizeof($er));
//matiHEre("hoop**");
        //endregion
        //region transksi sign
//        $t->setTableName("transaksi_sign");
//        $t->setSortBy(array());
//        $t->setFilters(array());
//        $t->addFilter("cli_loop='0'");
//        $this->db->limit(2000);
//        $this->db->order_by("id", "asc");
//        $er = $t->lookupAll()->result();
        //endregion
        //region ini transksi_data
        // $t->setTableName('transaksi_data');
        // $t->setFilters(array());
        // $t->setSortBy(array());
        // $t->addFilter("transaksi_id='90605'");
        // // $t->addFilter("mongo='0'");
        // // $this->db->limit(2000);
        // $er = $t->LookUpAll()->result();
//cekHitam($this->db->last_query());
//cekHitam(sizeof($er));

//        matiHEre();
        //endregion
//        arrPrint($er);
//        cekLime($this->db->last_query());
//        matiHEre("diee dulu");
//        $collMaster = $t->getFields();
//        $coll = $collMaster['main'];
        if (sizeof($er) > 0) {
            $ids = array();
            $m->setTableName("transaksi_values");
            foreach($er as $transksi_main_0){
                $ids[]=$transksi_main_0->id;
//                $fill = array_filter((array)$transksi_main_0);
                $fill = (array)$transksi_main_0;

//                $transksi_main = json_decode(json_encode($transksi_main_0), true);
                //region insert table transaksi
                $m->addData($fill);
                //endregion
            }

//             $update = array(
// //                "cli_loop" => "1",
// //                "parent_id" => "0",
// //                "indexing" => "0",
//                 "mongo" => "1",
//             );
//             $where = array(
//                 "id in" => "('" . implode("','", $ids) . "') ",
//             );
//            matiHEre();
//             $this->db->trans_start();

            // $t->setTableName('transaksi_data');
            // $t->setFilters(array());
            // $t->addFilter("id in ('" . implode("','", $ids) . "')");
            // $t->updateDataIn($update);
//            cekLime($this->db->last_query());
//            $endtime = microtime(true); // Bottom of page
//            $val =$endtime-$starttime;

//matiHere();
//             $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

//            $t->setFilters(array());
//            $t->addFilter("cli_loop='1'");
//            $f = $t->lookupDataCount();
//            //curent need
//            $t->setFilters(array());
////            $t->addFilter("cli_loop='0'");
//            $g = $t->lookupDataCount();
//            $need = $g-$f;
//            echo("total data :".$g." sukses diambil : ".$f. " sisa data :" .$need);
            $endtime = microtime(true); // Bottom of page
            $val =$endtime-$starttime;
            echo "  execute time[ ". $val." ] =>lanjut brooo";
        } else {
            cekHitam("data habis brooo");
            echo ("data habis boss lanjut table lain");
//            matiHEre();
        }
//        watch -n 1 /usr/bin/php /var/www/san/index.php TestMongo GrabData
        //watch -n 1 /usr/bin/php /var/www/mong_san/index.php TestMongo GrabData
    }

    public function injectMongo()
    {
        $this->load->model("MdlTransaksi");
        $m = new MdlMongoMother();
        $t = new MdlTransaksi();
        //region ini transksi_data
        //region ini transksi_data
        $t->setTableName('transaksi_sign');
        $t->setFilters(array());
        $t->setSortBy(array());
       $t->addFilter("transaksi_id='79336'");
//        $this->db->limit(1000);
//        $this->db->order_by("id", "asc");
        $er = $t->LookUpAll()->result();
        cekLime($this->db->last_query());
        //endregion
//        arrPrint($er);
        matiHEre();
        if (sizeof($er) > 0) {
            $ids = array();
            $m->setTableName("transaksi_sign");
            foreach ($er as $transksi_main_0) {
                $ids[] = $transksi_main_0->id;
//                $fill = array_filter((array)$transksi_main_0);
                $fill = (array)$transksi_main_0;
//arrPrint($fill);
//                $transksi_main = json_decode(json_encode($transksi_main_0), true);
                //region insert table transaksi
                $m->addData($fill);
                //endregion
            }


//matiHere();
//            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

//            $t->setFilters(array());
//            $t->addFilter("cli_loop='1'");
//            $f = $t->lookupDataCount();
//            //curent need
//            $t->setFilters(array());
////            $t->addFilter("cli_loop='0'");
//            $g = $t->lookupDataCount();
//            $need = $g-$f;
//            echo("total data :".$g." sukses diambil : ".$f. " sisa data :" .$need);
//            $endtime = microtime(true); // Bottom of page
//            $val =$endtime-$starttime;
//            echo "  execute time[ ". ." ] =>lanjut brooo";
            cekHitam("selesai");
        } else {
            cekHitam("data habis brooo");
            echo("data habis boss lanjut table lain");
//            matiHEre();
        }
    }
}