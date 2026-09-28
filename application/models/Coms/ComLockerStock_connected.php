<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComLockerStock_connected extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

//                                "jenis",
                                "produk_id",
                                "cabang_id",
                                "nama",
                                "satuan",
                                "state",
                                "jumlah",
                                "oleh_id",
                                "oleh_nama",
                                "transaksi_id",
                                "nomer",
                                "gudang_id",
    );

    private $memenuhiSyarat;

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
                $lCounter++;
                if(isset($paramAsli['static']['singleReference']) && $paramAsli['static']['singleReference']>0)
                {
                    $this->memenuhiSyarat=true;
                    foreach ($paramAsli['static'] as $key => $value) {
                        if (in_array($key, $this->outFields)) {
                            $this->outParams[$lCounter][$key] = $value;
                        }
                    }
                    $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
                    $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
                    $defaultGudangID = $paramAsli['static']['gudang_id'];

                    $_preValue = $this->cekPreValue($paramAsli['static']['jenis'], $paramAsli['static']['cabang_id'], $paramAsli['static']['produk_id'], $paramAsli['static']['state'], $defaultOlehID, $defaultTransID, $defaultGudangID);
                    if ($_preValue != null) {
                        $this->writeMode = "update";
                        $this->outParams[$lCounter]["jumlah"] = ($paramAsli['static']['jumlah'] + $_preValue);

                        if ($this->outParams[$lCounter]["jumlah"] < 0) {
//                            $msg = "Transaksi gagal, karena " . $paramAsli['static']['nama'] . "state " . $paramAsli['static']['state'] . ", terdeteksi minus: " . $this->outParams[$lCounter]["jumlah"];
                            $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . $paramAsli['static']['state'] . ", needed: ".$paramAsli['static']['jumlah'].", avail: " . $_preValue;
                            die(lgShowAlert($msg));
                        }
                    } else {
                        $this->writeMode = "new";
                    }
                }else{
                    //==cuekin, kalau bukan dari referensi
                    $this->memenuhiSyarat=false;
                }
            }
        }

        //==kalau tidak berasal dari koneksi, cuekin saja (selalu true
        if($this->memenuhiSyarat){
            if (sizeof($this->outParams) > 0) {
                return true;
            } else {
                return false;
            }
        }else{
            return true;
        }



    }

    private function cekPreValue($jenis, $cabang_id, $produk_id, $state = "active", $olehID = 0, $transaksiID = 0, $gudang_id)
    {

        $this->load->model("Mdls/MdlLockerStock");
        $l = new MdlLockerStock();


        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("gudang_id='$gudang_id'");
        $l->addFilter("produk_id='$produk_id'");
        $l->addFilter("state='$state'");
        $l->addFilter("oleh_id='$olehID'");
        $l->addFilter("transaksi_id='$transaksiID'");

        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->jumlah;
            }
        } else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {
        cekHijau("write mode: " . $this->writeMode);

        if($this->memenuhiSyarat){
            cekHijau("MEMENUHI SYARAT");
            if (sizeof($this->outParams) > 0) {
                foreach ($this->outParams as $ctr => $params) {
                    $this->load->model("Mdls/MdlLockerStock");
                    $l = new MdlLockerStock();
                    $insertIDs = array();
                    switch ($this->writeMode) {
                        case "new":
                            $insertIDs[] = $l->addData($params);
                            break;
                        case "update":
                            $insertIDs[] = $l->updateData(array(
                                "cabang_id"    => $params['cabang_id'],
                                "gudang_id"    => $params['gudang_id'],
                                "produk_id"    => $params['produk_id'],
                                "state"        => $params['state'],
                                "oleh_id"      => $params['oleh_id'],
                                "transaksi_id" => $params['transaksi_id'],
                            ), $params);
                            break;
                        default:
                            die("unknown writemode!");
                            break;
                    }
                    cekBiru($this->db->last_query());

                }
                if (sizeof($insertIDs) > 0) {
                    return true;
                } else {
                    return false;
                }

            } else {
                die("nothing to write down here");
                return false;
            }
        }else{
            cekMerah("TIDAK MEMENUHI SYARAT");
            return true;
        }


    }
}