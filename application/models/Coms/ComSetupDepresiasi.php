<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComSetupDepresiasi extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

        //        "jenis",
        "extern_id",
        "cabang_id",
        "extern_nama",
        "gudang_id",
        "rekening_main",
        "rekening_details",
        "gudang_id",
        "harga_perolehan",
        "economic_life_time",
        "residual_value",
        "repeat",
        "jenis",
        "dtime_start",

    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
         arrPrint($inParams);
//         matiHere();
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                $this->outParams[$lCounter]['dtime_perolehan'] = date("Y-m-d");
                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
                $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
                $defaultGudangID = $paramAsli['static']['gudang_id'];

                $_preValue = $this->cekPreValue($paramAsli['static']['jenis'], $paramAsli['static']['cabang_id'], $paramAsli['static']['extern_id'], $defaultGudangID);
                if ($_preValue != null) {
//arrPrint($_preValue);
//matiHEre();
                    $this->outParams[$lCounter]["harga_perolehan"] = ($paramAsli['static']['harga_perolehan'] + $_preValue['harga_perolehan']);
                    $this->outParams[$lCounter]["economic_life_time"] = ($paramAsli['static']['economic_life_time'] + $_preValue['economic_life_time']);
                    $this->outParams[$lCounter]["mode"] = "update";

                    if ($this->outParams[$lCounter]["harga_perolehan"] < 1) {
                        $msg = "*".$paramAsli['static']['produk_id']."* transaksi gagal, karena " . html_escape($paramAsli['static']['nama']) . " harga " . $paramAsli['static']['harga_perolehan'] . ", belum di isi";
//                        $msg .= json_encode($this->outParams);
                        die(lgShowAlert($msg));
                    }
                }
                else {
                    if ($this->outParams[$lCounter]["harga_perolehan"] < 1) {

                        $msg = "*".$paramAsli['static']['produk_id']."* transaksi gagal, karena " . html_escape($paramAsli['static']['nama']) . " harga " . $paramAsli['static']['harga_perolehan'] . ", belum di isi";
//                        $msg .= json_encode($this->outParams);
                        die(lgShowAlert($msg));
                    }

                    //                    $this->writeMode = "new";
                    $this->outParams[$lCounter]["mode"] = "new";
                }
            }
        }

        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            return false;
        }

    }

    private function cekPreValue($jenis, $cabang_id, $produk_id,$gudang_id)
    {

        $this->load->model("Mdls/MdlSetupDepresiasi");
        $l = new MdlSetupDepresiasi();

        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("gudang_id='$gudang_id'");
        $l->addFilter("extern_id='$produk_id'");


        $tmp = $l->lookupAll()->result();
//         cekMerah($this->db->last_query() . " # " . count($tmp));
//                arrPrint($tmp);
//matiHEre();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = array(
                    "harga_perolehan" =>$row->harga_perolehan,
                    "economic_life_time"=>$row->economic_life_time,
                );
            }
        }
        else {
            $result = null;
        }

        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlsetupDepresiasi");
                $l = new MdlsetupDepresiasi();
                $insertIDs = array();
                $mode = $params['mode'];
                unset($params['mode']);
                switch ($mode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        cekLime($this->db->last_query());
//                        matiHEre();
                        break;
                    case "update":
//                        arrPrint($params);
//                        matiHere("under maintenance");
                        $insertIDs[] = $l->updateData(array(
                            "cabang_id"    => $params['cabang_id'],
                            "gudang_id"    => $params['gudang_id'],
                            "extern_id"    => $params['extern_id'],
                            "jenis"         =>$params['jenis'],
//                            "state"        => $params['state'],
//                            "oleh_id"      => $params['oleh_id'],
//                            "transaksi_id" => $params['transaksi_id'],
                        ), $params);
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
//                 cekBiru($this->db->last_query());

            }
//                        mati_disini();
            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
        else {
            die("nothing to write down here");

            return false;
        }

    }
}