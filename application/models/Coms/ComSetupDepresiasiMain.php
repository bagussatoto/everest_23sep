<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComSetupDepresiasiMain extends CI_Model
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
//         arrPrint($this->inParams);
//         matiHere();
        if (sizeof($this->inParams) > 0) {
            $lCounter = 1;
//            foreach ($this->inParams as $lCtr => $paramAsli) {
//                $lCounter++;
                foreach ($this->inParams['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                $this->outParams[$lCounter]['dtime_perolehan'] = date("Y-m-d");
                $defaultOlehID = isset($this->inParams['static']['oleh_id']) ? $this->inParams['static']['oleh_id'] : 0;
                $defaultTransID = isset($this->inParams['static']['transaksi_id']) ? $this->inParams['static']['transaksi_id'] : 0;
                $defaultGudangID = $this->inParams['static']['gudang_id'];

                $_preValue = $this->cekPreValue($this->inParams['static']['jenis'], $this->inParams['static']['cabang_id'], $this->inParams['static']['extern_id'], $defaultGudangID);
                cekLime("$_preValue + ".$this->inParams['static']['harga_perolehan']);
                if ($_preValue != null) {
                    $this->outParams[$lCounter]["harga_perolehan"] = ($this->inParams['static']['harga_perolehan'] + $_preValue);
//                    $this->outParams[$lCounter]["economic_life_time"] = ($this->inParams['static']['economic_life_time'] + $_preValue['economic_life_time']);
                    $this->outParams[$lCounter]["mode"] = "update";
                    if ($this->outParams[$lCounter]["harga_perolehan"] < 1) {
                        $msg = "*".$this->inParams['static']['produk_id']."* transaksi gagal, karena " . html_escape($this->inParams['static']['nama']) . " harga " . $this->inParams['static']['harga_perolehan'] . ", belum di isi";
//                        $msg .= json_encode($this->outParams);
                        die(lgShowAlert($msg));
                    }
                }

//            }
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
//        $l->addFilter("gudang_id='$gudang_id'");
        $l->addFilter("extern_id='$produk_id'");


        $tmp = $l->lookupAll()->result();
//         cekMerah($this->db->last_query() . " # " . count($tmp));
//                arrPrint($tmp);
//matiHEre();
        if (sizeof($tmp) > 0) {
            $result = $tmp[0]->harga_perolehan;
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
                    case "update":
                        $insertIDs[] = $l->updateData(array(
                            "cabang_id"    => $params['cabang_id'],
//                            "gudang_id"    => $params['gudang_id'],
                            "extern_id"    => $params['extern_id'],
                            "jenis"         =>$params['jenis'],
//                            "state"        => $params['state'],
//                            "oleh_id"      => $params['oleh_id'],
//                            "transaksi_id" => $params['transaksi_id'],
                        ), $params);
                        break;
                    default:
//                       matiHEre("asset not setup on");
                        cekLime("not setup yet");
                        break;
                }
                 cekBiru($this->db->last_query());

            }
//                        mati_disini();
            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return true;
            }

        }
        else {
//            matiHere("nothing to write down here setup depresiasi not define yet!");
            return true;
        }

    }
}