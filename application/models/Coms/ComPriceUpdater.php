<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2019
 * Time: 4:03 PM
 */
class ComPriceUpdater extends MdlMother
{

    public function __construct()
    {
//        parent::__construct();
    }

    public function pair($inparams)
    {
        arrPrint($inparams);
        $this->load->model("Mdls/MdlDataHistory");
        $this->load->model("Mdls/MdlHargaProduk2");
        $h = new MdlHargaProduk2();
        $t = new MdlDataHistory();
        $this->inParams = $inparams;
        foreach ($this->inParams as $lCtr => $paramAsli) {

            $jenis = $paramAsli['static']['jenis'];
            $CID = $paramAsli['static']['cabang_id'];
            $pID = $paramAsli['static']['produk_id'];
            $key_value = $paramAsli['static']['key_value'];
            $value = $paramAsli['static'][$key_value];
            $cekpreVal = $this->preValue($CID, $pID, $key_value);

            if (sizeof($cekpreVal) > 0) {
                if ($cekpreVal == $value) {

                }
                else {
                    $update = array(
                        "nilai" => $value,
                        "oleh_id" => $this->session->login['id'],
                        "oleh_nama" => $this->session->login['nama'],
                        "dtime" => dtimeNow(),
                    );
                    $history = array(
                        "produk_id" => $pID,
                        "nilai" => $value,
                        "dtime" => date("Y-m-d H:i:s"),
                        "oleh_id" => $this->session->login['id'],
                        "oleh_nama" => $this->session->login['nama'],
                        "jenis" => $jenis,
                        "jenis_value" => $key_value,
                        "cabang_id" => $CID,
                    );
                    $where = array(
                        "id" => $cekpreVal['id'],
                    );
                    $insertID = $h->updateData($where, $update) or die("failed to update data");
                    showLast_query("pink");
                    $resultIds[] = $insertID;

                    $data_id = $pID;
                    $this->load->model("Mdls/" . "MdlDataHistory");
                    $hTmp = new MdlDataHistory();
                    $tmpHData = array(
                        "orig_id" => $insertID,
                        "mdl_name" => "MdlHargaProduk2",
                        "mdl_label" => "HargaProduk2",
                        "old_content" => base64_encode(serialize($cekpreVal)),
                        "old_content_intext" => print_r($cekpreVal, true),
                        "new_content" => base64_encode(serialize($history)),
                        "new_content_intext" => print_r($history, true),
                        "label" => "price",
                        "oleh_id" => $this->session->login['id'],
                        "oleh_name" => $this->session->login['nama'],
                        "data_id" => $data_id,
                        "cabang_id" => $CID,
                    );
                    $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                    showLast_query("hijau");
                }
            }
            else {
                //insertbaru bro
                $insert = array(
                    "jenis" => $jenis,
                    "jenis_value" => $key_value,
                    "produk_id" => $pID,
                    "cabang_id" => $CID,
                    "nilai" => $value,
                    "dtime" => date("Y-m-d H:i:s"),
                    "oleh_id" => $this->session->login['id'],
                    "oleh_nama" => $this->session->login['nama'],
                );
                $resultIds[] = $h->addData($insert) or die("failed to add new data");
                showLast_query("biru");
            }
//
//            foreach ($tmp as $pID => $tempVal) {
//                foreach ($tempVal as $key_value => $value) {
//                }
//                $oldData = $cekpreVal;
//            }
        }

        return true;
    }

    public function preValue($cid, $pid, $key_value)
    {

        $this->load->model("Mdls/MdlHargaProduk2");
        $m = new MdlHargaProduk2;
        $m->addFilter("cabang_id='$cid'");
        $m->addFilter("produk_id='$pid'");


        $m->addFilter("jenis_value='$key_value'");
        $temp = $m->lookUpAll()->result();
        showLast_query("orange");
        $preValue = array();
        if (sizeof($temp) > 0) {
            foreach ($temp[0] as $col => $val) {
                $preValue[$col] = $val;
            }
        }


        return $preValue;
    }

    public function exec()
    {

        return true;
    }
}